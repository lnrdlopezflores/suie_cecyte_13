<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use App\Models\User;

class TwoFactorController extends Controller
{
    /**
     * 1. Pantalla de activación: genera secret y QR
     */
    public function showSetup(Request $request)
    {
        $user = Auth::user();
        $google2fa = new Google2FA();

        // Si aún no tiene un secreto o no está confirmado, generar uno nuevo
        if (!$user->google2fa_enabled || empty($user->google2fa_secret)) {
            $secretKey = $google2fa->generateSecretKey();
            $user->google2fa_secret = encrypt($secretKey);
            $user->google2fa_enabled = false;
            $user->save();
        } else {
            $secretKey = decrypt($user->google2fa_secret);
        }

        // Generar la URL otpauth:// compatible con Google Authenticator
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            'SUIE CECyTE 13',
            $user->username . ' (' . ($user->email ?? 'usuario') . ')',
            $secretKey
        );

        // Renderizar el QR en formato SVG limpio (sin extensiones gráficas pesadas)
        $renderer = new ImageRenderer(
            new RendererStyle(220),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrImageSvg = $writer->writeString($qrCodeUrl);

        return view('cpanel.auth.two-factor-setup', [
            'qrCodeSvg' => $qrImageSvg,
            'secretKey' => $secretKey,
            'isEnabled' => $user->google2fa_enabled
        ]);
    }

    /**
     * 2. Confirmar activación con el primer código de 6 dígitos
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $user = Auth::user();
        $google2fa = new Google2FA();
        $secretKey = decrypt($user->google2fa_secret);

        // Validar código (con ventana de tolerancia de 1 bloque = 30 seg)
        $valid = $google2fa->verifyKey($secretKey, $request->input('code'), 1);

        if (!$valid) {
            return back()->withErrors(['code' => 'El código de 6 dígitos es incorrecto o ha expirado.']);
        }

        $user->google2fa_enabled = true;
        $user->save();

        return back()->with('success', '¡Google Authenticator activado con éxito en tu cuenta!');
    }

    /**
     * 3. Desactivar 2FA
     */
    public function disable(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
        ]);

        $user = Auth::user();

        if (!\Illuminate\Support\Facades\Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña es incorrecta.']);
        }

        $user->google2fa_enabled = false;
        $user->google2fa_secret = null;
        $user->save();

        session()->forget('2fa_passed');

        return back()->with('success', 'Google Authenticator ha sido desvinculado de tu cuenta.');
    }

    /**
     * 4. Vista de desafío en el Login
     */
    public function showChallenge()
    {
        if (!session()->has('2fa_user_id')) {
            return redirect()->route('login');
        }

        return view('cpanel.auth.two-factor-challenge');
    }

    /**
     * 5. Verificar código en el Login
     */
    public function verifyChallenge(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $userId = session('2fa_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::findOrFail($userId);
        $google2fa = new Google2FA();
        $secretKey = decrypt($user->google2fa_secret);

        $valid = $google2fa->verifyKey($secretKey, $request->input('code'), 1);

        if (!$valid) {
            return back()->withErrors(['code' => 'Código de verificación incorrecto. Abre tu app Google Authenticator e ingresa el código vigente.']);
        }

        // Marcar 2FA como completado en esta sesión e iniciar sesión
        session()->forget('2fa_user_id');
        session(['2fa_passed' => true]);

        Auth::login($user);
        request()->session()->regenerate();

        return $this->redirectPorRol($user);
    }

    private function redirectPorRol($user)
    {
        return match (strtolower($user->rol)) {
            'administrador'   => redirect()->route('usuarios.index'),
            'coordinador'     => redirect()->route('cargas.index'),
            'control escolar' => redirect()->route('cargas.index'),
            'docente'         => redirect()->route('docentes.index'),
            'estudiante'      => redirect()->route('indexalumnos.index'),
            'orientador'      => redirect()->route('orientador.index'),
            default           => redirect('/'),
        };
    }
}

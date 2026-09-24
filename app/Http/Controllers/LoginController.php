<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /**
     * Procesar el inicio de sesión con protección anti-SQLi y anti-fuerza bruta.
     */
    public function login(Request $request)
    {
        // 1. Limpieza y validación tipada estricta (limita longitud para mitigar DoS/fuerza bruta)
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'max:72'],
        ]);

        $username = trim($credentials['username']);
        $password = $credentials['password'];

        // 2. Control de intentos fallidos (Máximo 5 intentos por IP + Usuario en 1 minuto)
        $throttleKey = Str::transliterate(Str::lower($username).'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'username' => ["Demasiados intentos fallidos. Intenta nuevamente en {$seconds} segundos."],
            ]);
        }

        // 3. Consulta parametrizada segura con PDO (Protegida nativamente contra SQLi)
        $user = DB::table('usuarios')->where('username', $username)->first();

        // 4. Verificación de estatus activo si el usuario existe
        if ($user && empty($user->activo)) {
            RateLimiter::hit($throttleKey);
            throw ValidationException::withMessages([
                'username' => ['El acceso a esta cuenta ha sido suspendido. Contacte a la administración.'],
            ]);
        }

        // 5. Intento de autenticación seguro (Laravel vincula los parámetros automáticamente)
        if (Auth::attempt(['username' => $username, 'password' => $password, 'activo' => 1])) {
            RateLimiter::clear($throttleKey);

            $userAuth = Auth::user();

            // 6. Interceptor Google Authenticator (2FA)
            if (!empty($userAuth->google2fa_enabled) && $userAuth->google2fa_enabled) {
                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();
                $request->session()->put('2fa_user_id', $userAuth->id);

                return redirect()->route('2fa.challenge');
            }

            // Sesión regular sin 2FA
            $request->session()->regenerate();
            $request->session()->put('2fa_passed', true);

            return $this->getRedireccionPorRol($userAuth->rol);
        }

        // 7. Registro de intento fallido
        RateLimiter::hit($throttleKey);

        throw ValidationException::withMessages([
            'username' => ['La clave de usuario o contraseña introducida es incorrecta.'],
        ]);
    }

    public function redirectByRol()
    {
        if (Auth::check()) {
            return $this->getRedireccionPorRol(Auth::user()->rol);
        }

        return redirect()->to('/');
    }

    private function getRedireccionPorRol(string $rol)
    {
        return match (strtolower(trim($rol))) {
            'coordinador'     => redirect()->route('coordinador.dashboard'),
            'orientador'      => redirect()->route('asistencias.criticas'),
            'control escolar' => redirect()->route('alumnos.index'),
            'docente'         => redirect()->route('dashboardDocente.index'),
            'estudiante'      => redirect()->route('indexalumnos.index'),
            'administrador'   => redirect()->route('usuarios.index'),
            default           => redirect()->to('/'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->forget(['2fa_passed', '2fa_user_id']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to('/');
    }
}
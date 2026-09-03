<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /**
     * Procesar el inicio de sesión y redireccionar según el Rol o solicitar 2FA.
     */
    public function login(Request $request)
    {
        // 1. Validar datos de entrada
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // 2. Buscar primero al usuario para auditar su estatus
        $user = DB::table('usuarios')->where('username', $credentials['username'])->first();

        if ($user) {
            // Si el usuario existe pero está dado de baja (activo = 0)
            if (!$user->activo) {
                throw ValidationException::withMessages([
                    'username' => ['El acceso a esta cuenta ha sido suspendido. Contacte al Administrador Central.'],
                ]);
            }

            // 3. Si está activo, intentar autenticar con las credenciales
            if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
                
                $userAuth = Auth::user();

                // =====================================================================
                // 4. INTERCEPTOR GOOGLE AUTHENTICATOR (2FA)
                // =====================================================================
                if (!empty($userAuth->google2fa_enabled) && $userAuth->google2fa_enabled) {
                    // Desconectar sesión momentánea de Laravel
                    Auth::logout();

                    // Guardar ID temporal en sesión para validar el código de 6 dígitos
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    $request->session()->put('2fa_user_id', $userAuth->id);

                    // Redirigir a la pantalla del código OTP
                    return redirect()->route('2fa.challenge');
                }

                // Si NO tiene 2FA activo, marcar como completado
                $request->session()->regenerate();
                $request->session()->put('2fa_passed', true);

                // 5. Redirección por Match de Rol
                return $this->getRedireccionPorRol($userAuth->rol);
            }
        }

        // 6. Si no existe el usuario o la contraseña es errónea
        throw ValidationException::withMessages([
            'username' => ['La clave de usuario o contraseña introducida es incorrecta.'],
        ]);
    }

    /**
     * Redirige al panel correspondiente si ya está autenticado.
     */
    public function redirectByRol()
    {
        if (Auth::check()) {
            return $this->getRedireccionPorRol(Auth::user()->rol);
        }

        return redirect()->to('/');
    }

    /**
     * Auxiliar centralizado para redirecciones de rol en SUIE.
     */
    private function getRedireccionPorRol(string $rol)
    {
        return match ($rol) {
            'Coordinador'     => redirect()->route('coordinador.dashboard'),
            'Orientador'      => redirect()->route('orientacion.asistencias'),
            'Control Escolar' => redirect()->route('alumnos.index'),
            'Docente'         => redirect()->route('dashboardDocente.index'),
            'Estudiante'      => redirect()->route('indexalumnos.index'),
            'administrador'   => redirect()->route('usuarios.index'),
            default           => redirect()->to('/'),
        };
    }

    /**
     * Cerrar sesión del sistema y limpiar sesiones de 2FA.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->forget(['2fa_passed', '2fa_user_id']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to('/');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB; // <-- AÑADE ESTA LÍNEA


class LoginController extends Controller
{
    /**
     * Procesar el inicio de sesión y redireccionar según el Rol.
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

            // 3. Si está activo, intentar autenticar con las credenciales completas
            if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
                
                $request->session()->regenerate();
                $userAuth = Auth::user();

                // 4. Redirección por Match de Rol
                return match ($userAuth->rol) {
                    'Coordinador'     => redirect()->route('coordinacion.dashboard'),
                    'Orientador'      => redirect()->route('orientacion.asistencias'),
                    'Control Escolar' => redirect()->route('alumnos.index'),
                    'Docente'         => redirect()->route('dashboardDocente.index'),
                    'Estudiante'      => redirect()->route('indexalumnos.index'),
                    'administrador'   => redirect()->route('usuarios.index'),
                    default           => redirect()->to('/'),
                };
            }
        }

        // 5. Si no existe el usuario o la contraseña es errónea
        throw ValidationException::withMessages([
            'username' => ['La clave de usuario o contraseña introducida es incorrecta.'],
        ]);
    }

    public function redirectByRol()
    {
        // Si el usuario ya está autenticado, hacemos el Match de redirección
        if (Auth::check()) {
            return match (Auth::user()->rol) {
                'Coordinador'     => redirect()->route('coordinacion.dashboard'),
                'Orientador'      => redirect()->route('orientacion.asistencias'),
                'Control Escolar' => redirect()->route('alumnos.index'),
                'Docente'         => redirect()->route('dashboardDocente.index'),
                'Estudiante'      => redirect()->route('indexalumnos.index'),
                'administrador'   => redirect()->route('usuarios.index'),
                default           => redirect()->to('/'),
            };
        }

        // Si es un invitado (no está logueado), lo mandamos a la landing page principal
        return redirect()->to('/');
    }

    /**
     * Cerrar sesión del sistema.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to('/');
    }
}
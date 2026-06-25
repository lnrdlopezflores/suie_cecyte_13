<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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

        // 2. Intentar autenticar al usuario (verificando que esté activo)
        // Pasamos 'username' y 'password'. Auth::attempt se encarga de buscar el usuario
        // y pasar el 'password' por el validador de Bcrypt de forma automática.
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password'], 'activo' => 1])) {
            
            // Regenerar la sesión por seguridad frente a fijación de sesiones
            $request->session()->regenerate();

            // 3. Obtener el usuario autenticado
            $user = Auth::user();

            // 4. Redirección automática por Match de Rol
            return match ($user->rol) {
                'Coordinador'     => redirect()->route('coordinacion.dashboard'),
                'Orientador'      => redirect()->route('orientacion.asistencias'),
                'Control Escolar' => redirect()->route('control-escolar.dashboard'),
                'Docente'         => redirect()->route('dashboardDocente.index'),
                'Estudiante'      => redirect()->route('alumno.titulacion'),
                default           => redirect()->to('/'),
            };
        }

        // 5. Si la autenticación falla o está inactivo
        throw ValidationException::withMessages([
            'username' => ['Las credenciales proporcionadas no coinciden con nuestros registros o el usuario está inactivo.'],
        ]);
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
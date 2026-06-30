<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRol
{
    /**
     * Maneja una solicitud entrante.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->to('/');
        }

        // 2. Obtener el rol del usuario logueado
        $userRol = Auth::user()->rol;

        // 3. Evaluar si su rol está dentro de los permitidos para la ruta
        if (in_array($userRol, $roles)) {
            return $next($request);
        }

        // 4. Si no tiene permisos, abortar con un error 403 (No autorizado)
        abort(403, 'No tienes privilegios para acceder a esta sección del SUIE.');
    }
}

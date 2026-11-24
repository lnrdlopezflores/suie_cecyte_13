<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Password;

class PasswordResetRolController extends Controller
{
    /**
     * Detecta el rol del usuario y devuelve su plantilla y ruta de destino.
     */
    private function resolverContextoRol($user): array
    {
        // Normaliza el rol desde columna 'rol', 'role', 'tipo_usuario' o relación
        $rolRaw = strtolower(trim((string) (
            $user->rol
            ?? $user->role
            ?? $user->tipo
            ?? $user->tipo_usuario
            ?? ''
        )));

        // Si el rol viene por tabla pivote o catálogo (ej. rol_id)
        if (empty($rolRaw) && isset($user->rol_id) && Schema::hasTable('roles')) {
            $rolDb = DB::table('roles')->where('id', $user->rol_id)->value('nombre');
            $rolRaw = strtolower(trim((string) $rolDb));
        }

        // Mapeo de Roles -> Plantillas y Rutas Inicio
        if (str_contains($rolRaw, 'alum') || str_contains($rolRaw, 'estud')) {
            return [
                'rol_label'  => 'Estudiante',
                'plantilla'  => 'cpanel/plantillaestudiante',
                'ruta_panel' => 'indexalumnos.index',
            ];
        }

        if (str_contains($rolRaw, 'docen') || str_contains($rolRaw, 'prof') || str_contains($rolRaw, 'maest')) {
            return [
                'rol_label'  => 'Docente',
                'plantilla'  => 'cpanel/plantilladocente',
                'ruta_panel' => 'docente.dashboard', // Ajusta al nombre exacto de tu ruta docente
            ];
        }

        if (str_contains($rolRaw, 'finan') || str_contains($rolRaw, 'contad') || str_contains($rolRaw, 'caja')) {
            return [
                'rol_label'  => 'Finanzas y Caja',
                'plantilla'  => 'cpanel/plantillafinanzas', // Ajusta si tu archivo tiene otro nombre
                'ruta_panel' => 'contador.reportes.index',
            ];
        }

        if (str_contains($rolRaw, 'orienta') || str_contains($rolRaw, 'psico')) {
            return [
                'rol_label'  => 'Orientación Educativa',
                'plantilla'  => 'cpanel/plantillaorientacion', // Ajusta si tu archivo tiene otro nombre
                'ruta_panel' => 'asistencias.criticas',
            ];
        }

        // Respaldo para Control Escolar / Administrador / Dirección
        return [
            'rol_label'  => 'Administración / Control Escolar',
            'plantilla'  => view()->exists('cpanel.plantillaadmin') ? 'cpanel/plantillaadmin' : 'cpanel/plantilladocente',
            'ruta_panel' => 'login',
        ];
    }

    /**
     * Muestra la vista de restablecimiento de contraseña adaptada a la plantilla del rol.
     */
    public function edit()
    {
        $user = Auth::user();
        $contexto = $this->resolverContextoRol($user);

        // Detectar si es un cambio obligatorio por primer ingreso
        $esPrimerIngreso = (bool) (
            $user->primer_ingreso
            ?? $user->cambiar_password
            ?? $user->must_change_password
            ?? session('primer_ingreso', false)
        );

        // Verificar que la plantilla exista; si no, la vista usará un layout limpio sin sidebar
        $plantillaBlade = view()->exists(str_replace('/', '.', $contexto['plantilla']))
            ? $contexto['plantilla']
            : null;

        return view('cpanel/auth/cambiar_password', [
            'plantilla'       => $plantillaBlade,
            'rolLabel'        => $contexto['rol_label'],
            'rutaPanel'       => $contexto['ruta_panel'],
            'esPrimerIngreso' => $esPrimerIngreso,
        ]);
    }

    /**
     * Valida y actualiza la contraseña en la base de datos.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $contexto = $this->resolverContextoRol($user);

        $esPrimerIngreso = (bool) (
            $user->primer_ingreso
            ?? $user->cambiar_password
            ?? $user->must_change_password
            ?? session('primer_ingreso', false)
        );

        // Reglas de validación (si no es primer ingreso, exige contraseña actual)
        $rules = [
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)->letters()->numbers(),
            ],
        ];

        if (!$esPrimerIngreso) {
            $rules['current_password'] = ['required', 'string'];
        }

        $request->validate($rules, [
            'current_password.required' => 'Debes ingresar tu contraseña actual.',
            'password.required'         => 'Ingresa una nueva contraseña.',
            'password.min'              => 'La contraseña debe tener al menos 8 caracteres.',
            'password.letters'          => 'La contraseña debe incluir al menos una letra.',
            'password.numbers'          => 'La contraseña debe incluir al menos un número.',
            'password.confirmed'        => 'La confirmación de la nueva contraseña no coincide.',
        ]);

        // Verificar contraseña actual cuando es un cambio voluntario
        if (!$esPrimerIngreso && !Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'La contraseña actual ingresada es incorrecta.'])
                ->withInput();
        }

        // Evitar que vuelva a poner exactamente la misma contraseña inicial/actual
        if (Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['password' => 'La nueva contraseña no puede ser igual a tu contraseña anterior.'])
                ->withInput();
        }

        // Actualizar contraseña y limpiar bandera de primer ingreso según columnas existentes
        $tablaUsuarios = $user->getTable();
        $columnas = Schema::getColumnListing($tablaUsuarios);

        $datosActualizar = [
            'password' => Hash::make($request->password),
        ];

        if (in_array('primer_ingreso', $columnas)) {
            $datosActualizar['primer_ingreso'] = 0;
        }
        if (in_array('cambiar_password', $columnas)) {
            $datosActualizar['cambiar_password'] = 0;
        }
        if (in_array('must_change_password', $columnas)) {
            $datosActualizar['must_change_password'] = 0;
        }
        if (in_array('password_changed_at', $columnas)) {
            $datosActualizar['password_changed_at'] = now();
        }

        DB::table($tablaUsuarios)
            ->where('id', $user->id)
            ->update($datosActualizar);

        $request->session()->forget(['primer_ingreso', 'cambiar_password']);

        // Redirigir al panel principal de su rol
        $rutaDestino = \Illuminate\Support\Facades\Route::has($contexto['ruta_panel'])
            ? route($contexto['ruta_panel'])
            : url('/');

        return redirect($rutaDestino)->with('success', '¡Tu contraseña ha sido actualizada correctamente!');
    }
}

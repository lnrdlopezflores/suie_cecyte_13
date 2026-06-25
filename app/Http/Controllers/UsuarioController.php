<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $rol = $request->input('rol');

        // Inicializamos el Query Builder sobre tu tabla usuarios
        $query = DB::table('usuarios');

        // Aplicamos filtro de búsqueda si el administrador escribió algo
        if (!empty($buscar)) {
            $query->where('username', 'LIKE', '%' . $buscar . '%');
        }

        // Aplicamos filtro por Rol si se seleccionó una opción del combo
        if (!empty($rol)) {
            $query->where('rol', $rol);
        }

        // Recuperamos los datos paginados
        $usuarios = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('cpanel/usuarios/indexusuario', compact('usuarios'));
    }

    /**
     * Cambia el estado (activo/inactivo) del usuario seleccionado.
     */
    public function toggleStatus($id)
    {
        // 1. Buscar al usuario actual
        $user = DB::table('usuarios')->where('id', $id)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'El usuario solicitado no existe.');
        }

        // Evitar que el administrador se autosuspenda si está logueado con esta clave
        if (auth()->user()->id == $id) {
            return redirect()->back()->with('error', 'No puedes suspender tu propia cuenta administrativa.');
        }

        // 2. Invertir el estatus binario
        $nuevoEstatus = $user->activo == 1 ? 0 : 1;

        DB::table('usuarios')
            ->where('id', $id)
            ->update([
                'activo' => $nuevoEstatus,
                'updated_at' => now()
            ]);

        $msg = $nuevoEstatus ? 'reactivado' : 'suspendido temporalmente';

        return redirect()
            ->back()
            ->with('success', 'El usuario "' . $user->username . '" ha sido ' . $msg . ' con éxito.');
    }
    /**
     * Muestra el formulario de registro y la bitácora paginada.
     */
    public function create()
    {
        // Recuperamos los usuarios ordenados por fecha de creación decreciente (últimos primero)
        $usuarios = DB::table('usuarios')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('cpanel/usuarios/createusuario', compact('usuarios'));
    }

    /**
     * Almacena el nuevo usuario encriptando la credencial.
     */
    public function store(Request $request)
    {
        // Validamos estrictamente basándonos en tu estructura ENUM de MySQL
        $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:usuarios,username'],
            'password' => ['required', 'string', 'min:6'],
            'rol'      => ['required', 'in:Estudiante,Docente,Orientador,Control Escolar,coordinador,administrador'],
        ], [
            'username.unique' => 'Esta matrícula o clave de empleado ya se encuentra registrada en el SUIE.',
            'rol.in'          => 'El rol seleccionado no es válido dentro de la configuración matricular.'
        ]);

        // Insertamos usando Query Builder y la fachada Hash por seguridad
        DB::table('usuarios')->insert([
            'username'   => $request->input('username'),
            'password'   => Hash::make($request->input('password')), // BCrypt directo
            'rol'        => $request->input('rol'),
            'activo'     => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()
            ->route('usuarios.create')
            ->with('success', 'El usuario "' . $request->input('username') . '" ha sido incorporado al sistema de forma exitosa.');
    }
}

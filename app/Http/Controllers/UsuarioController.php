<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function index()
    {
        return $this->cargarVista();
    }

    public function create()
    {
        return $this->cargarVista();
    }

    private function cargarVista()
    {
        $usuarios = DB::table('usuarios')
            ->leftJoin('alumnos', 'usuarios.id', '=', 'alumnos.usuario_id')
            ->leftJoin('docentes', 'usuarios.id', '=', 'docentes.usuario_id')
            ->select(
                'usuarios.*',
                DB::raw('COALESCE(alumnos.nombre, docentes.nombre) as nombre'),
                DB::raw('COALESCE(alumnos.apellido_paterno, docentes.apellido_paterno) as apellido_paterno')
            )
            ->orderBy('usuarios.id', 'desc')
            ->paginate(10);

        return view('cpanel.usuarios.createusuario', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username'         => ['required', 'string', 'max:50', 'unique:usuarios,username'],
            'password'         => ['required', 'string', 'min:6'],
            'rol'              => ['required', 'string', 'in:Estudiante,Docente,Orientador,Control Escolar,Coordinador,administrador'],
            'nombre'           => ['required', 'string', 'max:150'],
            'apellido_paterno' => ['required', 'string', 'max:150'],
            'apellido_materno' => ['nullable', 'string', 'max:150'],
            'nombre_tutor'     => ['nullable', 'string', 'max:200'],
            'telefono_tutor'   => ['nullable', 'string', 'max:50'],
            'correo'           => ['nullable', 'email', 'max:150'],
            'telefono'         => ['nullable', 'string', 'max:50'],
        ]);

        DB::transaction(function () use ($request) {
            $rol = $request->input('rol');

            $usuarioId = DB::table('usuarios')->insertGetId([
                'username'   => $request->input('username'),
                'password'   => Hash::make($request->input('password')),
                'rol'        => $rol,
                'activo'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $nombreCifrado  = encrypt($request->input('nombre'));
            $paternoCifrado = encrypt($request->input('apellido_paterno'));
            $maternoCifrado = $request->filled('apellido_materno') ? encrypt($request->input('apellido_materno')) : null;

            if ($rol === 'Estudiante') {
                DB::table('alumnos')->insert([
                    'usuario_id'       => $usuarioId,
                    'grupo_id'         => null,
                    'nombre'           => $nombreCifrado,
                    'apellido_paterno' => $paternoCifrado,
                    'apellido_materno' => $maternoCifrado,
                    'nombre_tutor'     => $request->filled('nombre_tutor') ? encrypt($request->input('nombre_tutor')) : null,
                    'telefono_tutor'   => $request->filled('telefono_tutor') ? encrypt($request->input('telefono_tutor')) : null,
                ]);
            } elseif ($rol === 'Docente') {
                DB::table('docentes')->insert([
                    'usuario_id'       => $usuarioId,
                    'nombre'           => $nombreCifrado,
                    'apellido_paterno' => $paternoCifrado,
                    'apellido_materno' => $maternoCifrado,
                    'correo'           => $request->filled('correo') ? encrypt($request->input('correo')) : null,
                    'telefono'         => $request->filled('telefono') ? encrypt($request->input('telefono')) : null,
                ]);
            }
        });

        return redirect()->back()->with('success', 'Usuario y expediente registrados exitosamente.');
    }

    /**
     * Alterna el estatus del usuario entre Activo (1) y Suspendido (0).
     */
    public function toggleStatus($id)
    {
        if (Auth::id() == $id) {
            return redirect()->back()->with('error', 'No puedes suspender tu propia cuenta de administrador.');
        }

        $usuario = DB::table('usuarios')->where('id', $id)->first();

        if (!$usuario) {
            return redirect()->back()->with('error', 'El usuario especificado no existe.');
        }

        $nuevoEstatus = $usuario->activo ? 0 : 1;

        DB::table('usuarios')->where('id', $id)->update([
            'activo'     => $nuevoEstatus,
            'updated_at' => now(),
        ]);

        $accion = $nuevoEstatus ? 'reactivado' : 'suspendido';
        return redirect()->back()->with('success', "El usuario {$usuario->username} ha sido {$accion} correctamente.");
    }
}
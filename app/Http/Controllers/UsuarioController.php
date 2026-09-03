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
        ->leftJoin('administrador', 'usuarios.id', '=', 'administrador.usuario_id')
        ->select(
            'usuarios.*',
            DB::raw('COALESCE(alumnos.nombre, docentes.nombre, administrador.nombre) as nombre'),
            DB::raw('COALESCE(alumnos.apellido_paterno, docentes.apellido_paterno, administrador.apaterno) as apellido_paterno'),
            DB::raw('COALESCE(alumnos.apellido_materno, docentes.apellido_materno, administrador.amaterno) as apellido_materno')
        )
        ->orderBy('usuarios.id', 'desc')
        ->paginate(10);

        return view('cpanel.usuarios.createusuario', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:usuarios,username',
            'password' => 'required|string|min:6',
            'rol'      => 'required|string',
        ]);

        DB::transaction(function () use ($request) {
            $usuarioId = DB::table('usuarios')->insertGetId([
                'username'   => trim($request->username),
                'password'   => bcrypt($request->password),
                'rol'        => $request->rol,
                'activo'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $rolLimpio = strtolower(trim($request->rol));

            // 1. Expediente Alumno
            if ($rolLimpio === 'estudiante') {
                DB::table('alumnos')->insert([
                    'usuario_id'       => $usuarioId,
                    'nombre'           => $request->nombre,
                    'apellido_paterno' => $request->apellido_paterno,
                    'apellido_materno' => $request->apellido_materno,
                    'nombre_tutor'     => $request->nombre_tutor,
                    'telefono_tutor'   => $request->telefono_tutor,
                    'activo'           => 1,
                ]);
            }
            // 2. Expediente Docente
            elseif ($rolLimpio === 'docente') {
                DB::table('docentes')->insert([
                    'usuario_id'       => $usuarioId,
                    'nombre'           => $request->nombre,
                    'apellido_paterno' => $request->apellido_paterno,
                    'apellido_materno' => $request->apellido_materno,
                    'correo'           => $request->correo,
                    'telefono'         => $request->telefono,
                    'activo'           => 1,
                ]);
            }
            // 3. Expediente Administrador (columnas: nombre, apaterno, amaterno, usuario_id)
            elseif ($rolLimpio === 'administrador') {
                DB::table('administrador')->insert([
                    'usuario_id' => $usuarioId,
                    'nombre'     => $request->nombre,
                    'apaterno'   => $request->apellido_paterno,
                    'amaterno'   => $request->apellido_materno,
                ]);
            }
        });

        return redirect()->route('usuarios.index')->with('success', 'Usuario y expediente registrados correctamente.');
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

    
    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min'       => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        DB::table('usuarios')->where('id', $id)->update([
            'password'   => Hash::make($request->input('password')),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Contraseña actualizada correctamente.');
    }
}
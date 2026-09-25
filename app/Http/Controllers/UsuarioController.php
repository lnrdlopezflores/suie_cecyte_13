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
            ->leftJoin('coordinador', 'usuarios.id', '=', 'coordinador.usuario_id')
            ->leftJoin('orientador', 'usuarios.id', '=', 'orientador.usuario_id')
            ->leftJoin('control_escolar', 'usuarios.id', '=', 'control_escolar.usuario_id')
            ->leftJoin('finanzas', 'usuarios.id', '=', 'finanzas.usuario_id')
            ->select(
                'usuarios.*',
                DB::raw('COALESCE(alumnos.nombre, docentes.nombre, administrador.nombre, coordinador.nombre, orientador.nombre, control_escolar.nombre, finanzas.nombre) as per_nombre'),
                DB::raw('COALESCE(alumnos.apellido_paterno, docentes.apellido_paterno, administrador.apaterno, coordinador.apaterno, orientador.apaterno, control_escolar.apaterno, finanzas.apaterno) as per_apaterno'),
                DB::raw('COALESCE(alumnos.apellido_materno, docentes.apellido_materno, administrador.amaterno, finanzas.amaterno) as per_amaterno'),
                DB::raw('COALESCE(docentes.telefono, coordinador.telefono, orientador.telefono, control_escolar.telefono, finanzas.telefono) as per_telefono')
            )
            ->orderBy('usuarios.id', 'desc')
            ->paginate(10);

        // Desencriptar datos de forma segura
        $usuarios->getCollection()->transform(function ($user) {
            $nom = $this->desencriptarDato($user->per_nombre);
            $pat = $this->desencriptarDato($user->per_apaterno);
            $mat = $this->desencriptarDato($user->per_amaterno);
            $tel = $this->desencriptarDato($user->per_telefono);

            $nombreCompleto = trim("{$pat} {$mat} {$nom}");
            if (empty($nombreCompleto)) {
                $nombreCompleto = trim("{$nom} {$pat}");
            }

            $user->nombre_completo = !empty($nombreCompleto) ? $nombreCompleto : null;
            $user->telefono_contacto = !empty($tel) ? $tel : null;
            
            $letra1 = !empty($nom) ? substr($nom, 0, 1) : substr($user->username, 0, 1);
            $letra2 = !empty($pat) ? substr($pat, 0, 1) : substr($user->username, 1, 1);
            $user->iniciales = strtoupper($letra1 . $letra2);

            return $user;
        });

        return view('cpanel.usuarios.createusuario', compact('usuarios'));
    }

    private function desencriptarDato(?string $val): string
    {
        if (empty($val)) return '';
        try {
            if (is_string($val) && (str_starts_with($val, 'ey') || strlen($val) > 50)) {
                return decrypt($val);
            }
        } catch (\Throwable $e) {}
        return str_replace(' (Plain)', '', $val);
    }

    public function store(Request $request)
    {
        // 1. Validación con lista blanca incluyendo Finanzas
        $request->validate([
            'username'         => 'required|string|max:50|unique:usuarios,username',
            'password'         => 'required|string|min:6|max:72',
            'rol'              => 'required|string|in:Estudiante,Docente,Orientador,Control Escolar,Coordinador,administrador,Finanzas',
            'nombre'           => 'required|string|max:150',
            'apellido_paterno' => 'required|string|max:150',
            'apellido_materno' => 'nullable|string|max:150',
            'telefono'         => 'nullable|string|max:20',
            'correo'           => 'nullable|email|max:150',
            'nombre_tutor'     => 'nullable|string|max:150',
            'telefono_tutor'   => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request) {
            // Alta en la tabla base usuarios
            $usuarioId = DB::table('usuarios')->insertGetId([
                'username'   => trim($request->username),
                'password'   => Hash::make($request->password),
                'rol'        => $request->rol,
                'email'      => $request->filled('correo') ? trim($request->correo) : null,
                'activo'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $rolNormalizado = strtolower(trim($request->rol));

            // Inserción en la tabla de perfil según el rol
            switch ($rolNormalizado) {
                case 'estudiante':
                    DB::table('alumnos')->insert([
                        'usuario_id'       => $usuarioId,
                        'nombre'           => trim($request->nombre),
                        'apellido_paterno' => trim($request->apellido_paterno),
                        'apellido_materno' => trim($request->apellido_materno ?? ''),
                        'nombre_tutor'     => trim($request->nombre_tutor ?? ''),
                        'telefono_tutor'   => trim($request->telefono_tutor ?? ''),
                        'activo'           => 1,
                    ]);
                    break;

                case 'docente':
                    DB::table('docentes')->insert([
                        'usuario_id'       => $usuarioId,
                        'nombre'           => trim($request->nombre),
                        'apellido_paterno' => trim($request->apellido_paterno),
                        'apellido_materno' => trim($request->apellido_materno ?? ''),
                        'correo'           => trim($request->correo ?? ''),
                        'telefono'         => trim($request->telefono ?? ''),
                        'activo'           => 1,
                    ]);
                    break;

                case 'administrador':
                    DB::table('administrador')->insert([
                        'usuario_id' => $usuarioId,
                        'nombre'     => trim($request->nombre),
                        'apaterno'   => trim($request->apellido_paterno),
                        'amaterno'   => trim($request->apellido_materno ?? ''),
                    ]);
                    break;

                case 'coordinador':
                    DB::table('coordinador')->insert([
                        'usuario_id' => $usuarioId,
                        'nombre'     => trim($request->nombre),
                        'apaterno'   => trim($request->apellido_paterno),
                        'telefono'   => trim($request->telefono ?? ''),
                    ]);
                    break;

                case 'orientador':
                    DB::table('orientador')->insert([
                        'usuario_id' => $usuarioId,
                        'nombre'     => trim($request->nombre),
                        'apaterno'   => trim($request->apellido_paterno),
                        'telefono'   => trim($request->telefono ?? ''),
                    ]);
                    break;

                case 'control escolar':
                    DB::table('control_escolar')->insert([
                        'usuario_id' => $usuarioId,
                        'nombre'     => trim($request->nombre),
                        'apaterno'   => trim($request->apellido_paterno),
                        'telefono'   => trim($request->telefono ?? ''),
                    ]);
                    break;

                case 'finanzas':
                    DB::table('finanzas')->insert([
                        'usuario_id' => $usuarioId,
                        'nombre'     => trim($request->nombre),
                        'apaterno'   => trim($request->apellido_paterno),
                        'amaterno'   => trim($request->apellido_materno ?? ''),
                        'telefono'   => trim($request->telefono ?? ''),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    break;
            }
        });

        return redirect()->route('usuarios.index')->with('success', 'Usuario y expediente institucional registrados correctamente.');
    }

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
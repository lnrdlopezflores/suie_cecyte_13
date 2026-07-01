<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class AlumnosAdminController extends Controller
{
    /**
     * Muestra el catálogo de alumnos con filtros para el administrador (Descifrado dinámico).
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $activo = $request->input('activo');

        $query = DB::table('alumnos')
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->leftJoin('grupos', 'alumnos.grupo_id', '=', 'grupos.id')
            ->select(
                'alumnos.id as alumno_id',
                'alumnos.usuario_id',
                'alumnos.nombre',
                'alumnos.apellido_paterno',
                'alumnos.apellido_materno',
                'alumnos.nombre_tutor',
                'alumnos.telefono_tutor',
                'usuarios.username', // Matrícula (No se cifra para poder buscar por ella)
                'usuarios.activo',
                'grupos.semestre',
                'grupos.grupo',
                'grupos.especialidad'
            );

        if ($activo !== null && $activo !== '') {
            $query->where('usuarios.activo', $activo);
        }

        // NOTA: La búsqueda por nombre/apellido por LIKE sql ordinario no funcionará con datos cifrados.
        // Se mantiene la búsqueda limpia por matrícula (username).
        if (!empty($buscar)) {
            $query->where('usuarios.username', 'LIKE', '%' . $buscar . '%');
        }

        $alumnosPaginados = $query->orderBy('alumnos.id', 'desc')->paginate(15);

        // Transformamos los resultados del paginador para descifrar los strings
        $alumnosPaginados->getCollection()->transform(function ($alumno) {
            try {
                $alumno->nombre = decrypt($alumno->nombre);
                $alumno->apellido_paterno = decrypt($alumno->apellido_paterno);
                $alumno->apellido_materno = $alumno->apellido_materno ? decrypt($alumno->apellido_materno) : null;
                $alumno->nombre_tutor = decrypt($alumno->nombre_tutor);
                $alumno->telefono_tutor = decrypt($alumno->telefono_tutor);
            } catch (DecryptException $e) {
                // Si hay datos viejos en la BD sin cifrar, los muestra tal cual para evitar romper la vista
                $alumno->nombre = $alumno->nombre . ' (Sin Cifrar)';
            }
            return $alumno;
        });

        return view('cpanel/Alumnos/indexalumnos', ['alumnos' => $alumnosPaginados]);
    }

    public function create()
    {
        $usuariosDisponibles = DB::table('usuarios')
            ->leftJoin('alumnos', 'usuarios.id', '=', 'alumnos.usuario_id')
            ->where('usuarios.rol', '=', 'Estudiante')
            ->whereNull('alumnos.usuario_id')
            ->select('usuarios.id', 'usuarios.username', 'usuarios.created_at')
            ->orderBy('usuarios.username', 'asc')
            ->get();

        return view('cpanel/Alumnos/createalumnos', compact('usuariosDisponibles'));
    }

    /**
     * Almacena el expediente aplicando cifrado AES-256 a los datos personales.
     */
    public function store(Request $request)
    {
        $request->validate([
            'usuario_id'       => ['required', 'integer', 'unique:alumnos,usuario_id'],
            'nombre'           => ['required', 'string', 'max:50'],
            'apellido_paterno' => ['required', 'string', 'max:50'],
            'apellido_materno' => ['nullable', 'string', 'max:50'],
            'nombre_tutor'     => ['required', 'string', 'max:100'],
            'telefono_tutor'   => ['required', 'string', 'max:15'],
        ], [
            'usuario_id.unique' => 'Esta cuenta credencial ya tiene un expediente matricular asociado.'
        ]);

        // Guardar cifrando los datos mediante la APP_KEY del sistema
        DB::table('alumnos')->insert([
            'usuario_id'       => $request->input('usuario_id'),
            'grupo_id'         => null,
            'nombre'           => encrypt($request->input('nombre')),
            'apellido_paterno' => encrypt($request->input('apellido_paterno')),
            'apellido_materno' => $request->input('apellido_materno') ? encrypt($request->input('apellido_materno')) : null,
            'nombre_tutor'     => encrypt($request->input('nombre_tutor')),
            'telefono_tutor'   => encrypt($request->input('telefono_tutor')),
        ]);

        return redirect()
            ->route('AdAlumnos.index')
            ->with('success', 'El expediente del alumno ha sido guardado y cifrado de manera correcta.');
    }
}

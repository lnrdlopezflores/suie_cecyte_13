<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlumnosAdminController extends Controller
{
    /**
     * Muestra el catálogo de alumnos con filtros para el administrador.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $activo = $request->input('activo');

        // Construcción de la consulta unificando alumnos, usuarios y grupos
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
                'usuarios.username', // Matrícula
                'usuarios.activo',
                'grupos.semestre',
                'grupos.grupo',
                'grupos.especialidad'
            );

        // Filtro de auditoría por estatus de cuenta (activo/inactivo)
        if ($activo !== null && $activo !== '') {
            $query->where('usuarios.activo', $activo);
        }

        // Barra de búsqueda descriptiva
        if (!empty($buscar)) {
            $query->where(function ($q) use ($buscar) {
                $q->where('alumnos.nombre', 'LIKE', '%' . $buscar . '%')
                  ->orWhere('alumnos.apellido_paterno', 'LIKE', '%' . $buscar . '%')
                  ->orWhere('usuarios.username', 'LIKE', '%' . $buscar . '%');
            });
        }

        // Ordenar alfabéticamente por apellido paterno
        $alumnos = $query->orderBy('alumnos.apellido_paterno', 'asc')->paginate(15);

        return view('cpanel/Alumnos/indexalumnos', compact('alumnos'));
    }

    public function create()
    {
        // Removido 'usuarios.email' para acoplarse al esquema real de la base de datos
        $usuariosDisponibles = DB::table('usuarios')
            ->leftJoin('alumnos', 'usuarios.id', '=', 'alumnos.usuario_id')
            ->where('usuarios.rol', '=', 'Estudiante') // Asegura que coincida con tu ENUM (Ej: Estudiante)
            ->whereNull('alumnos.usuario_id')
            ->select('usuarios.id', 'usuarios.username', 'usuarios.created_at') // Limpio
            ->orderBy('usuarios.username', 'asc')
            ->get();

        return view('cpanel/Alumnos/createalumnos', compact('usuariosDisponibles'));
    }

    /**
     * Almacena el expediente del alumno en la base de datos (con grupo_id por defecto NULL).
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

        // Insertar registro con Query Builder. El campo grupo_id se queda como null de forma nativa.
        DB::table('alumnos')->insert([
            'usuario_id'       => $request->input('usuario_id'),
            'grupo_id'         => null, // Inscribe sin grupo asignado inicialmente
            'nombre'           => $request->input('nombre'),
            'apellido_paterno' => $request->input('apellido_paterno'),
            'apellido_materno' => $request->input('apellido_materno'),
            'nombre_tutor'     => $request->input('nombre_tutor'),
            'telefono_tutor'   => $request->input('telefono_tutor'),
        ]);

        return redirect()
            ->route('AdAlumnos.index')
            ->with('success', 'El expediente del alumno ha sido guardado de manera correcta.');
    }
}

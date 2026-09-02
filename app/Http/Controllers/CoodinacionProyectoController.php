<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoodinacionProyectoController extends Controller
{
    public function index(Request $request)
    {
        $carrera = $request->input('carrera');
        $buscar  = $request->input('buscar');

        // 1. Unir proyectos con los alumnos y sus respectivos grupos para obtener la especialidad
        $query = DB::table('proyectos_titulacion')
            ->join('proyecto_alumno', 'proyectos_titulacion.id', '=', 'proyecto_alumno.proyecto_id')
            ->join('alumnos', 'proyecto_alumno.alumno_id', '=', 'alumnos.id')
            ->join('grupos', 'alumnos.grupo_id', '=', 'grupos.id')
            ->leftJoin('docentes as asesor', 'proyectos_titulacion.docente_asesor_id', '=', 'asesor.id')
            ->leftJoin('usuarios as asesor_user', 'asesor.usuario_id', '=', 'asesor_user.id')
            ->select(
                'proyectos_titulacion.*',
                'grupos.especialidad',
                'asesor.nombre as asesor_nombre',
                'asesor.apellido_paterno as asesor_paterno',
                'asesor_user.username as asesor_username'
            )
            ->distinct();

        // 2. Filtro por Carrera / Especialidad usando la columna real 'grupos.especialidad'
        if (!empty($carrera)) {
            $query->where('grupos.especialidad', $carrera);
        }

        // 3. Filtro de Búsqueda
        if (!empty($buscar)) {
            $query->where(function ($q) use ($buscar) {
                $q->where('proyectos_titulacion.titulo', 'LIKE', '%' . $buscar . '%')
                  ->orWhere('proyectos_titulacion.resumen', 'LIKE', '%' . $buscar . '%');
            });
        }

        $proyectos = $query->orderBy('proyectos_titulacion.id', 'desc')->paginate(10);

        // 4. Cargar los integrantes de cada proyecto
        $proyectoIds = $proyectos->pluck('id')->toArray();
        $todosIntegrantes = DB::table('proyecto_alumno')
            ->join('alumnos', 'proyecto_alumno.alumno_id', '=', 'alumnos.id')
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->whereIn('proyecto_alumno.proyecto_id', $proyectoIds)
            ->select(
                'proyecto_alumno.proyecto_id',
                'alumnos.id as alumno_id',
                'alumnos.nombre',
                'alumnos.apellido_paterno',
                'usuarios.username'
            )
            ->get()
            ->groupBy('proyecto_id');

        $proyectos->getCollection()->transform(function ($proyecto) use ($todosIntegrantes) {
            $proyecto->integrantes = $todosIntegrantes->get($proyecto->id, collect());
            return $proyecto;
        });

        // 5. Conteo de aprobados para métricas rápidas
        $totalAprobados = DB::table('proyectos_titulacion')->where('estatus', 'Aprobado')->count();

        return view('cpanel.coordinacion.proyectosregistrados', compact('proyectos', 'totalAprobados'));
    }
}

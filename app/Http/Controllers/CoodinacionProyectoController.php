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

        $query = DB::table('proyectos_titulacion')
            ->leftJoin('docentes as asesor', 'proyectos_titulacion.docente_asesor_id', '=', 'asesor.id')
            ->leftJoin('usuarios as asesor_user', 'asesor.usuario_id', '=', 'asesor_user.id')
            ->select(
                'proyectos_titulacion.*',
                'asesor.nombre as asesor_nombre',
                'asesor.apellido_paterno as asesor_paterno',
                'asesor_user.username as asesor_username'
            );

        // Filtro por Carrera/Especialidad
        if (!empty($carrera)) {
            $query->where('proyectos_titulacion.especialidad', $carrera);
        }

        // Búsqueda por título o resumen
        if (!empty($buscar)) {
            $query->where(function ($q) use ($buscar) {
                $q->where('proyectos_titulacion.titulo', 'LIKE', '%' . $buscar . '%')
                  ->orWhere('proyectos_titulacion.resumen', 'LIKE', '%' . $buscar . '%');
            });
        }

        $proyectos = $query->orderBy('proyectos_titulacion.id', 'desc')->paginate(10);

        // Cruce de integrantes por cada proyecto
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

        $totalAprobados = DB::table('proyectos_titulacion')->where('estatus', 'Aprobado')->count();

        return view('cpanel.coordinacion.proyectosregistrados', compact('proyectos', 'totalAprobados'));
    }
}

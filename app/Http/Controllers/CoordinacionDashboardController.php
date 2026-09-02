<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoordinacionDashboardController extends Controller
{
    public function index()
    {
        // 1. Consulta base de proyectos vinculados a su carrera real
        $proyectosBase = DB::table('proyectos_titulacion')
            ->join('proyecto_alumno', 'proyectos_titulacion.id', '=', 'proyecto_alumno.proyecto_id')
            ->join('alumnos', 'proyecto_alumno.alumno_id', '=', 'alumnos.id')
            ->join('grupos', 'alumnos.grupo_id', '=', 'grupos.id')
            ->select('proyectos_titulacion.id', 'proyectos_titulacion.estatus', 'grupos.especialidad')
            ->distinct()
            ->get();

        $totalProyectos = $proyectosBase->count();

        // 2. Conteo por los 3 estados principales
        $totalAprobados  = $proyectosBase->where('estatus', 'Aprobado')->count();
        $totalLiberados  = $proyectosBase->filter(function($p) {
            return in_array($p->estatus, ['Liberado', 'Liberado_Exposicion']);
        })->count();
        $totalRechazados = $proyectosBase->where('estatus', 'Rechazado')->count();
        $totalRevision   = $proyectosBase->filter(function($p) {
            return !in_array($p->estatus, ['Aprobado', 'Liberado', 'Liberado_Exposicion', 'Rechazado']);
        })->count();

        // 3. Segmentación por Carrera para la gráfica comparativa
        // Animación Digital
        $animacionAprobados  = $proyectosBase->where('especialidad', 'Animación Digital')->where('estatus', 'Aprobado')->count();
        $animacionLiberados  = $proyectosBase->where('especialidad', 'Animación Digital')->filter(fn($p) => in_array($p->estatus, ['Liberado', 'Liberado_Exposicion']))->count();
        $animacionRechazados = $proyectosBase->where('especialidad', 'Animación Digital')->where('estatus', 'Rechazado')->count();

        // Química Industrial
        $quimicaAprobados  = $proyectosBase->where('especialidad', 'Química Industrial')->where('estatus', 'Aprobado')->count();
        $quimicaLiberados  = $proyectosBase->where('especialidad', 'Química Industrial')->filter(fn($p) => in_array($p->estatus, ['Liberado', 'Liberado_Exposicion']))->count();
        $quimicaRechazados = $proyectosBase->where('especialidad', 'Química Industrial')->where('estatus', 'Rechazado')->count();

        // 4. Últimos 5 proyectos registrados para la tabla rápida
        $ultimosProyectos = DB::table('proyectos_titulacion')
            ->join('proyecto_alumno', 'proyectos_titulacion.id', '=', 'proyecto_alumno.proyecto_id')
            ->join('alumnos', 'proyecto_alumno.alumno_id', '=', 'alumnos.id')
            ->join('grupos', 'alumnos.grupo_id', '=', 'grupos.id')
            ->select('proyectos_titulacion.*', 'grupos.especialidad')
            ->distinct()
            ->orderBy('proyectos_titulacion.id', 'desc')
            ->limit(5)
            ->get();

        return view('cpanel/coordinacion/dashboard', compact(
            'totalProyectos',
            'totalAprobados',
            'totalLiberados',
            'totalRechazados',
            'totalRevision',
            'animacionAprobados',
            'animacionLiberados',
            'animacionRechazados',
            'quimicaAprobados',
            'quimicaLiberados',
            'quimicaRechazados',
            'ultimosProyectos'
        ));
    }
}

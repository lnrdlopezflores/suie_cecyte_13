<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardDocenteController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Obtener el registro del docente autenticado
        $docente = DB::table('docentes')
            ->where('usuario_id', $user->id)
            ->first();

        if (!$docente) {
            $materias = collect();
            $clasesHoy = 0;
            return view('cpanel/orientacion/indexdocente', compact('materias', 'clasesHoy'));
        }

        // 2. Obtener el día de la semana actual en español (Ej: "Lunes", "Martes", "Miércoles")
        $diaSemanaActual = Carbon::now()->locale('es')->isoFormat('dddd');
        $diaSemanaActual = ucfirst($diaSemanaActual);

        // 3. Consultar la carga académica (SIN 'ca.dias_clase' para evitar el error SQL)
        $materias = DB::table('carga_academica as ca')
            ->leftJoin('materias as m', 'ca.materia_id', '=', 'm.id')
            ->leftJoin('grupos as g', 'ca.grupo_id', '=', 'g.id')
            ->where('ca.docente_id', $docente->id)
            ->select(
                'ca.id',
                'm.nombre',
                'g.semestre',
                'g.grupo',
                'g.especialidad',
                'ca.horario',
                'ca.aula',
                DB::raw('(SELECT COUNT(DISTINCT alumno_id) FROM asistencias WHERE carga_academica_id = ca.id AND estatus = "Falta" GROUP BY alumno_id HAVING COUNT(*) > 4) as alumnos_criticos_count')
            )
            ->get();

        // 4. Calcular dinámicamente las clases del día buscando el día de la semana dentro de 'horario'
        $clasesHoy = $materias->filter(function ($materia) use ($diaSemanaActual) {
            return !empty($materia->horario) && str_contains(mb_strtolower($materia->horario), mb_strtolower($diaSemanaActual));
        })->count();

        return view('cpanel/orientacion/indexdocente', compact('materias', 'clasesHoy'));
    }

    public function descargarHorarioPdf()
{
    $user = Auth::user();
    
    $docente = DB::table('docentes')
        ->where('usuario_id', $user->id)
        ->first();

    if (!$docente) {
        return back()->with('error', 'No se encontró el registro docente.');
    }

    // Nombre descifrado del docente
    $nombreDocente = $docente->nombre;
    if (is_string($nombreDocente) && (str_starts_with($nombreDocente, 'ey') || strlen($nombreDocente) > 50)) {
        try {
            $nombreDocente = decrypt($nombreDocente);
        } catch (\Throwable $e) {
            $nombreDocente = str_replace(' (Plain)', '', $nombreDocente);
        }
    }

    // Cargas académicas asignadas
    $cargas = DB::table('carga_academica as ca')
        ->leftJoin('materias as m', 'ca.materia_id', '=', 'm.id')
        ->leftJoin('grupos as g', 'ca.grupo_id', '=', 'g.id')
        ->where('ca.docente_id', $docente->id)
        ->select(
            'ca.id',
            'm.nombre as materia',
            'm.clave',
            'g.semestre',
            'g.grupo',
            'g.especialidad',
            'ca.horario',
            'ca.aula'
        )
        ->get();

    $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

    return view('cpanel/reportes/horario_pdf', compact('nombreDocente', 'cargas', 'diasSemana'));
}
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AlumnoMateriasController extends Controller
{
    /**
     * Despliega la carga académica y asistencias del alumno.
     */
    public function index()
    {
        $userId = Auth::id();

        // 1. Obtener los datos base del alumno y su grupo asignado
        $alumno = DB::table('alumnos')
            ->where('usuario_id', $userId)
            ->first();

        if (!$alumno || is_null($alumno->grupo_id)) {
            return view('alumno.materias_index', [
                'materias' => [],
                'grupoInfo' => null
            ]);
        }

        $grupoInfo = DB::table('grupos')->where('id', $alumno->grupo_id)->first();

        // 2. Traer las materias ligadas a la carga académica del grupo con contadores de asistencias
        $materias = DB::table('carga_academica')
            ->join('materias', 'carga_academica.materia_id', '=', 'materias.id')
            ->join('docentes', 'carga_academica.docente_id', '=', 'docentes.id')
            ->select(
                'carga_academica.id as carga_id',
                'carga_academica.aula',
                'carga_academica.horario',
                'materias.nombre as materia_nombre',
                'materias.clave',
                'materias.horas_semanales',
                'docentes.nombre as docente_nombre',
                'docentes.apellido_paterno as docente_apellido',
                
                // Subconsulta: Total de clases evaluadas en el ciclo para el alumno
                DB::raw("(SELECT COUNT(*) FROM asistencias WHERE carga_academica_id = carga_academica.id AND alumno_id = {$alumno->id}) as total_clases"),
                
                // Subconsulta: Total de faltas registradas
                DB::raw("(SELECT COUNT(*) FROM asistencias WHERE carga_academica_id = carga_academica.id AND alumno_id = {$alumno->id} AND estatus = 'Falta') as total_faltas")
            )
            ->where('carga_academica.grupo_id', $alumno->grupo_id)
            ->orderBy('materias.nombre', 'asc')
            ->get();

        // 3. Formatear y calcular los porcentajes de asistencia inline antes de enviar a la vista
        foreach ($materias as $materia) {
            if ($materia->total_clases > 0) {
                $asistenciasEfectivas = $materia->total_clases - $materia->total_faltas;
                $materia->porcentaje_asistencia = round(($asistenciasEfectivas / $materia->total_clases) * 100);
            } else {
                $materia->porcentaje_asistencia = 100; // Si no hay pases de lista aún, se asume 100%
            }
        }

        return view('cpanel/alumnos/materiaalumno', compact('materias', 'grupoInfo'));
    }
}
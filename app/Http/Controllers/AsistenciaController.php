<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsistenciaController extends Controller
{
    /**
     * Muestra la hoja de pase de asistencia para la carga académica seleccionada.
     */
    public function tomarAsistencia($cargaId)
    {
        // 1. Obtener los datos de contexto del grupo y materia de esta carga académica
        $carga = DB::table('carga_academica')
            ->join('materias', 'carga_academica.materia_id', '=', 'materias.id')
            ->join('grupos', 'carga_academica.grupo_id', '=', 'grupos.id')
            ->select('carga_academica.id', 'materias.nombre', 'grupos.semestre', 'grupos.grupo', 'carga_academica.grupo_id')
            ->where('carga_academica.id', $cargaId)
            ->first();

        if (!$carga) {
            return redirect()->route('docente.dashboard')->with('error', 'Carga académica no válida.');
        }

        // 2. Extraer alumnos asignados al grupo calculando faltas históricas y asistencias totales en este módulo
        $alumnos = DB::table('alumnos')
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->select(
                'alumnos.id as alumno_id',
                'alumnos.nombre',
                'alumnos.apellido_paterno',
                'alumnos.apellido_materno',
                'usuarios.username', // Matrícula
                // Subconsulta para totalizar clases en esta carga académica
                DB::raw("(SELECT COUNT(*) FROM asistencias WHERE asistencias.alumno_id = alumnos.id AND asistencias.carga_academica_id = $cargaId) as clases_totales"),
                // Subconsulta para obtener las faltas acumuladas en esta materia
                DB::raw("(SELECT COUNT(*) FROM asistencias WHERE asistencias.alumno_id = alumnos.id AND asistencias.carga_academica_id = $cargaId AND asistencias.estatus = 'Falta') as faltas_acumuladas")
            )
            ->where('alumnos.grupo_id', $carga->grupo_id)
            ->orderBy('alumnos.apellido_paterno', 'asc')
            ->get();

        return view('cpanel/orientacion/asistencia', compact('carga', 'alumnos'));
    }

    /**
     * Procesa y guarda de forma masiva los estados de asistencia tomados por el docente.
     */
    public function guardarAsistencia(Request $request, $cargaId)
    {
        $request->validate([
            'fecha' => 'required|date',
            'periodo' => 'required|string',
        ]);

        $fecha = $request->input('fecha');
        // Obtener la matriz mapeada del array que viene desde los checkbox de la vista
        $asistenciasEnviadas = $request->input('asistencias', []);

        // Obtener la lista total de alumnos de este grupo para procesar las ausencias (checkboxes desmarcados)
        $carga = DB::table('carga_academica')->where('id', $cargaId)->first();
        $alumnoIds = DB::table('alumnos')->where('grupo_id', $carga->grupo_id)->pluck('id');

        // Transacción de base de datos para garantizar consistencia atómica masiva
        DB::transaction(function () use ($alumnoIds, $asistenciasEnviadas, $cargaId, $fecha) {
            foreach ($alumnoIds as $id) {
                // Si el ID del alumno existe en el request, se marca como Asistencia, de lo contrario es Falta
                $estatus = array_key_exists($id, $asistenciasEnviadas) ? 'Asistencia' : 'Falta';

                // updateOrInsert evita registros duplicados si el docente guarda dos veces el mismo día
                DB::table('asistencias')->updateOrInsert(
                    [
                        'carga_academica_id' => $cargaId,
                        'alumno_id'          => $id,
                        'fecha'              => $fecha
                    ],
                    [
                        'estatus'            => $estatus,
                        'observacion'        => $estatus === 'Falta' ? 'Pase de lista regular' : null
                    ]
                );
            }
        });

       return redirect()->route('dashboardDocente.index')->with('success', 'El registro de asistencias se ha guardado exitosamente.');
    }
}

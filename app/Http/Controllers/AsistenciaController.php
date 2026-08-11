<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

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

        // 2. Extraer alumnos asignados al grupo calculando faltas históricas y asistencias totales
        $alumnos = DB::table('alumnos')
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->select(
                'alumnos.id as alumno_id',
                'alumnos.nombre',
                'alumnos.apellido_paterno',
                'alumnos.apellido_materno',
                'usuarios.username', // Matrícula
                DB::raw("(SELECT COUNT(*) FROM asistencias WHERE asistencias.alumno_id = alumnos.id AND asistencias.carga_academica_id = $cargaId) as clases_totales"),
                DB::raw("(SELECT COUNT(*) FROM asistencias WHERE asistencias.alumno_id = alumnos.id AND asistencias.carga_academica_id = $cargaId AND asistencias.estatus = 'Falta') as faltas_acumuladas")
            )
            ->where('alumnos.grupo_id', $carga->grupo_id)
            ->orderBy('alumnos.apellido_paterno', 'asc')
            ->get();

        // 3. Descifrado dinámico de la lista de alumnos
        $alumnos->transform(function ($alumno) {
            try {
                if (is_string($alumno->nombre) && (strpos($alumno->nombre, 'ey') === 0 || strlen($alumno->nombre) > 50)) {
                    $alumno->nombre           = decrypt($alumno->nombre);
                    $alumno->apellido_paterno = decrypt($alumno->apellido_paterno);
                    $alumno->apellido_materno = $alumno->apellido_materno ? decrypt($alumno->apellido_materno) : null;
                }
            } catch (\Throwable $e) {
                // Se preserva el valor original en texto plano
            }
            return $alumno;
        });

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
        $asistenciasEnviadas = $request->input('asistencias', []);

        $carga = DB::table('carga_academica')->where('id', $cargaId)->first();
        $alumnoIds = DB::table('alumnos')->where('grupo_id', $carga->grupo_id)->pluck('id');

        DB::transaction(function () use ($alumnoIds, $asistenciasEnviadas, $cargaId, $fecha) {
            foreach ($alumnoIds as $id) {
                $estatus = array_key_exists($id, $asistenciasEnviadas) ? 'Asistencia' : 'Falta';

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

    /**
     * Genera el reporte de alumnos en condición crítica de inasistencias (<80%).
     */
    public function reporteCritico(Request $request)
    {
        $grupoId = $request->input('grupo_id');

        // 1. Catálogo para el selector de grupos
        $gruposActivos = DB::table('grupos')
            ->where('estatus_egreso', 'Regular')
            ->orderBy('semestre', 'asc')
            ->get();

        // 2. Query base de extracción matricular
        $query = DB::table('alumnos')
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->join('grupos', 'alumnos.grupo_id', '=', 'grupos.id')
            ->join('carga_academica', 'grupos.id', '=', 'carga_academica.grupo_id')
            ->join('materias', 'carga_academica.materia_id', '=', 'materias.id')
            ->select(
                'alumnos.id as alumno_id',
                'alumnos.nombre',
                'alumnos.apellido_paterno',
                'alumnos.apellido_materno',
                'alumnos.nombre_tutor',
                'alumnos.telefono_tutor',
                'usuarios.username',
                'grupos.semestre',
                'grupos.grupo',
                'grupos.especialidad',
                'materias.nombre as materia_nombre',
                'materias.clave',
                'carga_academica.id as carga_id',
                DB::raw('(SELECT COUNT(*) FROM asistencias WHERE carga_academica_id = carga_academica.id AND alumno_id = alumnos.id) as total_clases'),
                DB::raw('(SELECT COUNT(*) FROM asistencias WHERE carga_academica_id = carga_academica.id AND alumno_id = alumnos.id AND estatus = "Falta") as total_faltas')
            );

        if (!empty($grupoId)) {
            $query->where('grupos.id', $grupoId);
        }

        // 3. Procesar descifrado y filtros matemáticos sobre la colección
        $alumnosCollection = $query->get()->map(function($alumno) {
            // Descifrado dinámico de la identidad del alumno y su tutor
            try {
                if (is_string($alumno->nombre) && (strpos($alumno->nombre, 'ey') === 0 || strlen($alumno->nombre) > 50)) {
                    $alumno->nombre           = decrypt($alumno->nombre);
                    $alumno->apellido_paterno = decrypt($alumno->apellido_paterno);
                    $alumno->apellido_materno = $alumno->apellido_materno ? decrypt($alumno->apellido_materno) : null;
                    $alumno->nombre_tutor     = decrypt($alumno->nombre_tutor);
                    $alumno->telefono_tutor   = decrypt($alumno->telefono_tutor);
                }
            } catch (\Throwable $e) {
                // Conservar en texto plano si no se encuentra cifrado
            }

            // Cálculo del porcentaje de asistencia
            if ($alumno->total_clases > 0) {
                $asistencias = $alumno->total_clases - $alumno->total_faltas;
                $alumno->porcentaje_asistencia = ($asistencias / $alumno->total_clases) * 100;
            } else {
                $alumno->porcentaje_asistencia = 100.0;
            }

            return $alumno;
        })->filter(function($alumno) {
            // Filtrar únicamente alumnos en condición crítica (< 80%)
            return $alumno->porcentaje_asistencia < 80.0;
        })->sortBy('porcentaje_asistencia');

        // 4. Paginación manual de la colección
        $perPage = 15;
        $currentPage = Paginator::resolveCurrentPage() ?: 1;
        $currentItems = $alumnosCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $alumnos = new LengthAwarePaginator(
            $currentItems,
            $alumnosCollection->count(),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath(), 'pageName' => 'page']
        );

        return view('cpanel/orientacion/alertaInasistencia', compact('alumnos', 'gruposActivos'));
    }

    /**
     * Construye y redirige la notificación vía WhatsApp para el tutor legal del estudiante.
     */
    public function enviarAlertaTutor(Request $request)
    {
        $request->validate([
            'alumno_id' => ['required', 'integer', 'exists:alumnos,id'],
            'carga_id'  => ['required', 'integer', 'exists:carga_academica,id'],
        ]);

        // 1. Extraer la información del estudiante y su tutor
        $datos = DB::table('alumnos')
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->join('grupos', 'alumnos.grupo_id', '=', 'grupos.id')
            ->join('carga_academica', 'grupos.id', '=', 'carga_academica.grupo_id')
            ->join('materias', 'carga_academica.materia_id', '=', 'materias.id')
            ->select(
                'alumnos.nombre', 'alumnos.apellido_paterno', 'alumnos.nombre_tutor', 'alumnos.telefono_tutor',
                'usuarios.username', 'grupos.semestre', 'grupos.grupo',
                'materias.nombre as materia_nombre',
                DB::raw('(SELECT COUNT(*) FROM asistencias WHERE carga_academica_id = carga_academica.id AND alumno_id = alumnos.id) as total_clases'),
                DB::raw('(SELECT COUNT(*) FROM asistencias WHERE carga_academica_id = carga_academica.id AND alumno_id = alumnos.id AND estatus = "Falta") as total_faltas')
            )
            ->where('alumnos.id', $request->input('alumno_id'))
            ->where('carga_academica.id', $request->input('carga_id'))
            ->first();

        if (!$datos) {
            return redirect()->back()->withErrors(['error' => 'No se encontraron datos consistentes para la alerta.']);
        }

        // 2. Descifrar información sensible antes de construir el mensaje
        try {
            if (is_string($datos->nombre) && (strpos($datos->nombre, 'ey') === 0 || strlen($datos->nombre) > 50)) {
                $datos->nombre           = decrypt($datos->nombre);
                $datos->apellido_paterno = decrypt($datos->apellido_paterno);
                $datos->nombre_tutor     = decrypt($datos->nombre_tutor);
                $datos->telefono_tutor   = decrypt($datos->telefono_tutor);
            }
        } catch (\Throwable $e) {
            // Mantiene el valor en texto plano
        }

        // 3. Calcular el porcentaje real de asistencias
        if ($datos->total_clases > 0) {
            $asistenciasEfectivas = $datos->total_clases - $datos->total_faltas;
            $porcentaje = round(($asistenciasEfectivas / $datos->total_clases) * 100, 1);
        } else {
            $porcentaje = 100.0;
        }

        // 4. Limpiar el teléfono para la API de WhatsApp
        $telefonoLimpio = preg_replace('/[^0-9]/', '', $datos->telefono_tutor);
        if (strlen($telefonoLimpio) == 10) {
            $telefonoLimpio = '52' . $telefonoLimpio; // Prefijo de México
        }

        // 5. Construcción del texto institucional
        $mensaje = "Estimado(a) {$datos->nombre_tutor},\n\n";
        $mensaje .= "Le informamos desde la Oficina de Orientación Educativa del plantel que el alumno(a) *{$datos->nombre} {$datos->apellido_paterno}* (Matrícula: {$datos->username}), inscrito en el {$datos->semestre}° \"{$datos->grupo}\", registra actualmente un porcentaje crítico de asistencia del *{$porcentaje}%* en la asignatura de *{$datos->materia_nombre}* debido a {$datos->total_faltas} faltas acumuladas.\n\n";
        $mensaje .= "Le recordamos que el mínimo reglamentario para conservar el derecho a evaluación ordinaria es del *80%*. Solicitamos su valioso apoyo para conversar con su tutorado. Saludos cordiales.";

        // 6. Redirección a WhatsApp API
        $urlWhatsApp = "https://wa.me/{$telefonoLimpio}?text=" . urlencode($mensaje);

        return redirect()->away($urlWhatsApp);
    }
}
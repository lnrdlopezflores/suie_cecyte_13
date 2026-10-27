<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    /**
     * Muestra la hoja de pase de asistencia para la carga académica seleccionada.
     */
    public function tomarAsistencia($cargaId)
    {
        // 1. Obtener los datos de contexto del grupo y materia de esta carga académica
        $carga = DB::table('carga_academica as ca')
            ->leftJoin('materias as m', 'ca.materia_id', '=', 'm.id')
            ->leftJoin('grupos as g', 'ca.grupo_id', '=', 'g.id')
            ->where('ca.id', $cargaId)
            ->select(
                'ca.id',
                'ca.id as carga_id', 
                'm.nombre',
                'm.nombre as materia', 
                'g.id as grupo_id',
                'g.semestre', 
                'g.grupo', 
                'g.especialidad'
            )
            ->first();

        // 2. Validación de seguridad para prevenir la pantalla de error
        if (!$carga) {
            return back()->with('error', 'No se encontró la carga académica especificada (# ' . $cargaId . ').');
        }

        // 3. Consulta de alumnos con subconsultas para totales de clases y faltas
        $alumnos = DB::table('alumnos as a')
            ->join('carga_academica as ca', 'a.grupo_id', '=', 'ca.grupo_id')
            ->leftJoin('usuarios as u', 'a.usuario_id', '=', 'u.id')
            ->where('ca.id', $cargaId)
            ->select(
                'a.id',
                'a.id as alumno_id',
                'u.username',
                'a.nombre',
                'a.apellido_paterno',
                'a.apellido_materno',
                DB::raw('(SELECT COUNT(DISTINCT fecha) FROM asistencias WHERE carga_academica_id = ' . (int)$cargaId . ') as clases_totales'),
                DB::raw('(SELECT COUNT(*) FROM asistencias WHERE carga_academica_id = ' . (int)$cargaId . ' AND alumno_id = a.id AND estatus = "Falta") as faltas_acumuladas')
            )
            ->orderBy('a.apellido_paterno')
            ->orderBy('a.apellido_materno')
            ->orderBy('a.nombre')
            ->get();

        // 4. Descifrado dinámico de la lista de alumnos
        $alumnos->transform(function ($alumno) {
            try {
                if (is_string($alumno->nombre) && (strpos($alumno->nombre, 'ey') === 0 || strlen($alumno->nombre) > 50)) {
                    $alumno->nombre           = decrypt($alumno->nombre);
                    $alumno->apellido_paterno = decrypt($alumno->apellido_paterno);
                    $alumno->apellido_materno = $alumno->apellido_materno ? decrypt($alumno->apellido_materno) : null;
                }
            } catch (\Throwable $e) {
                // Conservar texto plano si no está cifrado
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
        if (!$carga) {
            return back()->with('error', 'Carga académica inválida.');
        }

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

        $gruposActivos = DB::table('grupos')
            ->where('estatus_egreso', 'Regular')
            ->orderBy('semestre', 'asc')
            ->get();

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

        $alumnosCollection = $query->get()->map(function($alumno) {
            try {
                if (is_string($alumno->nombre) && (strpos($alumno->nombre, 'ey') === 0 || strlen($alumno->nombre) > 50)) {
                    $alumno->nombre           = decrypt($alumno->nombre);
                    $alumno->apellido_paterno = decrypt($alumno->apellido_paterno);
                    $alumno->apellido_materno = $alumno->apellido_materno ? decrypt($alumno->apellido_materno) : null;
                    $alumno->nombre_tutor     = decrypt($alumno->nombre_tutor);
                    $alumno->telefono_tutor   = decrypt($alumno->telefono_tutor);
                }
            } catch (\Throwable $e) {
                // Conservar texto plano
            }

            if ($alumno->total_clases > 0) {
                $asistencias = $alumno->total_clases - $alumno->total_faltas;
                $alumno->porcentaje_asistencia = ($asistencias / $alumno->total_clases) * 100;
            } else {
                $alumno->porcentaje_asistencia = 100.0;
            }

            return $alumno;
        })->filter(function($alumno) {
            return $alumno->porcentaje_asistencia < 80.0;
        })->sortBy('porcentaje_asistencia');

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
     * Construye y redirige la notificación vía WhatsApp para el tutor legal.
     */
    public function enviarAlertaTutor(Request $request)
    {
        $request->validate([
            'alumno_id' => ['required', 'integer', 'exists:alumnos,id'],
            'carga_id'  => ['required', 'integer', 'exists:carga_academica,id'],
        ]);

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

        try {
            if (is_string($datos->nombre) && (strpos($datos->nombre, 'ey') === 0 || strlen($datos->nombre) > 50)) {
                $datos->nombre           = decrypt($datos->nombre);
                $datos->apellido_paterno = decrypt($datos->apellido_paterno);
                $datos->nombre_tutor     = decrypt($datos->nombre_tutor);
                $datos->telefono_tutor   = decrypt($datos->telefono_tutor);
            }
        } catch (\Throwable $e) {
            // Texto plano
        }

        if ($datos->total_clases > 0) {
            $asistenciasEfectivas = $datos->total_clases - $datos->total_faltas;
            $porcentaje = round(($asistenciasEfectivas / $datos->total_clases) * 100, 1);
        } else {
            $porcentaje = 100.0;
        }

        $telefonoLimpio = preg_replace('/[^0-9]/', '', $datos->telefono_tutor);
        if (strlen($telefonoLimpio) == 10) {
            $telefonoLimpio = '52' . $telefonoLimpio;
        }

        $mensaje = "Estimado(a) {$datos->nombre_tutor},\n\n";
        $mensaje .= "Le informamos desde la Oficina de Orientación Educativa del plantel que el alumno(a) *{$datos->nombre} {$datos->apellido_paterno}* (Matrícula: {$datos->username}), inscrito en el {$datos->semestre}° \"{$datos->grupo}\", registra actualmente un porcentaje crítico de asistencia del *{$porcentaje}%* en la asignatura de *{$datos->materia_nombre}* debido a {$datos->total_faltas} faltas acumuladas.\n\n";
        $mensaje .= "Le recordamos que el mínimo reglamentario para conservar el derecho a evaluación ordinaria es del *80%*. Solicitamos su valioso apoyo para conversar con su tutorado. Saludos cordiales.";

        $urlWhatsApp = "https://wa.me/{$telefonoLimpio}?text=" . urlencode($mensaje);

        return redirect()->away($urlWhatsApp);
    }

    /**
     * Imprime el reporte mensual en cuadrícula de asistencias (• / /).
     */
public function imprimirReporteMensual(Request $request, $carga_id)
{
    $mes = (int) $request->get('mes', date('m'));
    $anio = (int) $request->get('anio', date('Y'));
    
    // 1. Consultar Carga Académica
    $carga = DB::table('carga_academica as ca')
        ->leftJoin('materias as m', 'ca.materia_id', '=', 'm.id')
        ->leftJoin('grupos as g', 'ca.grupo_id', '=', 'g.id')
        ->where('ca.id', $carga_id)
        ->select(
            'ca.id',
            'ca.id as carga_id', 
            'm.nombre',
            'm.nombre as materia', 
            'g.semestre', 
            'g.grupo', 
            'g.especialidad'
        )
        ->first();

    if (!$carga) {
        return back()->with('error', 'No se encontró la carga académica especificada (# ' . $carga_id . ').');
    }

    // 2. Dias del mes seleccionado
    $diasDelMes = Carbon::createFromDate($anio, $mes, 1)->daysInMonth;

    // 3. Consultar alumnos trayendo 'id' (id de alumnos) y 'usuario_id' por si asistencias usa usuario_id
    $alumnos = DB::table('alumnos as a')
        ->join('carga_academica as ca', 'a.grupo_id', '=', 'ca.grupo_id')
        ->leftJoin('usuarios as u', 'a.usuario_id', '=', 'u.id')
        ->where('ca.id', $carga_id)
        ->select(
            'a.id as alumno_id', 
            'a.usuario_id',
            'u.username', 
            'a.nombre', 
            'a.apellido_paterno', 
            'a.apellido_materno'
        )
        ->orderBy('a.apellido_paterno')
        ->orderBy('a.apellido_materno')
        ->orderBy('a.nombre')
        ->get();

    // 4. Desencriptación de nombres
    foreach ($alumnos as $al) {
        try {
            if (is_string($al->nombre) && (str_starts_with($al->nombre, 'ey') || strlen($al->nombre) > 50)) {
                $al->nombre = decrypt($al->nombre);
            }
            if (is_string($al->apellido_paterno) && (str_starts_with($al->apellido_paterno, 'ey') || strlen($al->apellido_paterno) > 50)) {
                $al->apellido_paterno = decrypt($al->apellido_paterno);
            }
            if (is_string($al->apellido_materno) && (str_starts_with($al->apellido_materno, 'ey') || strlen($al->apellido_materno) > 50)) {
                $al->apellido_materno = decrypt($al->apellido_materno);
            }
        } catch (\Throwable $e) {
            $al->nombre = str_replace(' (Plain)', '', $al->nombre);
            $al->apellido_paterno = str_replace(' (Plain)', '', $al->apellido_paterno);
            $al->apellido_materno = str_replace(' (Plain)', '', $al->apellido_materno);
        }
    }

    // 5. Consultar asistencias del mes flexibilizando la fecha en MySQL
    $asistenciasRaw = DB::table('asistencias')
        ->where('carga_academica_id', $carga_id)
        ->whereMonth('fecha', $mes)
        ->whereYear('fecha', $anio)
        ->get();

    // 6. Mapeo de IDs (Mapeamos tanto 'id' de alumno como 'usuario_id' hacia la misma fila)
    $matrizAsistencias = [];
    $totalesAsistencias = [];
    $mapaAlumnos = []; // Mapea cualquier ID recibido hacia el 'alumno_id' principal

    foreach ($alumnos as $al) {
        $idPrincipal = (int) $al->alumno_id;
        $matrizAsistencias[$idPrincipal] = array_fill(1, $diasDelMes, '');
        $totalesAsistencias[$idPrincipal] = 0;

        // Registrar equivalencias de IDs
        $mapaAlumnos[$idPrincipal] = $idPrincipal;
        if (!empty($al->usuario_id)) {
            $mapaAlumnos[(int) $al->usuario_id] = $idPrincipal;
        }
    }

    // 7. Llenar la matriz
    foreach ($asistenciasRaw as $reg) {
        $dia = (int) date('j', strtotime($reg->fecha));
        $idReg = (int) $reg->alumno_id;

        // Verificar si el ID existe en el mapa de equivalencias
        if (isset($mapaAlumnos[$idReg])) {
            $idTarget = $mapaAlumnos[$idReg];
            $estatus = trim(mb_strtolower($reg->estatus));

            if ($estatus === 'asistencia') {
                $matrizAsistencias[$idTarget][$dia] = '•';
                $totalesAsistencias[$idTarget]++;
            } elseif ($estatus === 'falta') {
                $matrizAsistencias[$idTarget][$dia] = '/';
            } elseif ($estatus === 'justificado') {
                $matrizAsistencias[$idTarget][$dia] = 'J';
            } elseif ($estatus === 'retardo') {
                $matrizAsistencias[$idTarget][$dia] = 'R';
                $totalesAsistencias[$idTarget]++;
            }
        }
    }

    $nombreMes = Carbon::createFromDate($anio, $mes, 1)->translatedFormat('F Y');

    return view('cpanel/reportes/reporteasistencia_pdf', compact(
        'carga',
        'alumnos',
        'diasDelMes',
        'matrizAsistencias',
        'totalesAsistencias',
        'nombreMes',
        'mes',
        'anio'
    ));
}
}
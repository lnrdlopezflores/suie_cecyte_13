<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Encryption\DecryptException;

class OrientacionGraficasController extends Controller
{
    public function index(Request $request)
    {
        $grupoId = $request->input('grupo_id');

        // 1. Obtener grupos activos para el filtro
        $grupos = DB::table('grupos')->orderBy('semestre')->orderBy('grupo')->get();

        // 2. Traer alumnos con su historial de faltas y grupo
        $query = DB::table('alumnos')
            ->join('grupos', 'alumnos.grupo_id', '=', 'grupos.id')
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->leftJoin('asistencias', 'alumnos.id', '=', 'asistencias.alumno_id')
            ->select(
                'alumnos.id',
                'alumnos.nombre',
                'alumnos.apellido_paterno',
                'alumnos.apellido_materno',
                'usuarios.username as matricula',
                'grupos.id as grupo_id',
                'grupos.grupo',
                'grupos.semestre',
                'grupos.especialidad',
                DB::raw("COUNT(CASE WHEN asistencias.estatus = 'Falta' THEN 1 END) as total_faltas"),
                DB::raw("COUNT(asistencias.id) as total_clases")
            )
            ->groupBy(
                'alumnos.id',
                'alumnos.nombre',
                'alumnos.apellido_paterno',
                'alumnos.apellido_materno',
                'usuarios.username',
                'grupos.id',
                'grupos.grupo',
                'grupos.semestre',
                'grupos.especialidad'
            );

        if (!empty($grupoId)) {
            $query->where('grupos.id', $grupoId);
        }

        $estudiantes = $query->get();

        // 3. Coeficientes del Modelo de Regresión Logística (Entrenados/Calibrados para Media Superior)
        // Logit: z = beta_0 + beta_1 * faltas + beta_2 * factor_inasistencia
        $beta0 = -4.10; // Intercepto base (baja probabilidad para estudiantes regulares)
        $beta1 = 0.32;  // Peso por cada falta directa
        $beta2 = 0.055; // Peso por porcentaje de inasistencia

        $conteoBajo = 0;
        $conteoMedio = 0;
        $conteoCritico = 0;
        $riesgoPorGrupo = [];
        $puntosDispersion = [];

        $estudiantesProcesados = $estudiantes->map(function ($est) use (
            $beta0, $beta1, $beta2, 
            &$conteoBajo, &$conteoMedio, &$conteoCritico, &$riesgoPorGrupo, &$puntosDispersion
        ) {
            $nom = $this->desencriptarDato($est->nombre);
            $pat = $this->desencriptarDato($est->apellido_paterno);
            $mat = $this->desencriptarDato($est->apellido_materno);
            $est->nombre_completo = trim("{$pat} {$mat} {$nom}") ?: $est->matricula;

            $faltas = (int) $est->total_faltas;
            $clases = (int) $est->total_clases ?: 30; // Evitar división por cero
            $porcentajeInasistencia = round(($faltas / $clases) * 100, 1);

            // Cálculo Logístico: z = β0 + β1*X1 + β2*X2
            $z = $beta0 + ($beta1 * $faltas) + ($beta2 * $porcentajeInasistencia);
            // Función Sigmoide: P = 1 / (1 + e^-z)
            $probabilidad = 1 / (1 + exp(-$z));
            $probabilidadPct = round($probabilidad * 100, 1);

            $est->probabilidad = $probabilidadPct;
            $est->faltas = $faltas;
            $est->pct_inasistencia = $porcentajeInasistencia;

            // Clasificación de Riesgo
            if ($probabilidadPct >= 70.0) {
                $est->nivel_riesgo = 'Crítico';
                $conteoCritico++;
            } elseif ($probabilidadPct >= 35.0) {
                $est->nivel_riesgo = 'Moderado';
                $conteoMedio++;
            } else {
                $est->nivel_riesgo = 'Bajo';
                $conteoBajo++;
            }

            // Agrupación por salón/carrera
            $claveGrupo = "{$est->semestre}° {$est->grupo} ({$est->especialidad})";
            if (!isset($riesgoPorGrupo[$claveGrupo])) {
                $riesgoPorGrupo[$claveGrupo] = ['criticos' => 0, 'moderados' => 0, 'bajos' => 0];
            }
            if ($est->nivel_riesgo === 'Crítico') $riesgoPorGrupo[$claveGrupo]['criticos']++;
            elseif ($est->nivel_riesgo === 'Moderado') $riesgoPorGrupo[$claveGrupo]['moderados']++;
            else $riesgoPorGrupo[$claveGrupo]['bajos']++;

            // Puntos para curva de dispersión logit (X: Inasistencia %, Y: Probabilidad %)
            $puntosDispersion[] = [
                'x' => $porcentajeInasistencia,
                'y' => $probabilidadPct,
                'nombre' => $est->nombre_completo,
                'faltas' => $faltas,
            ];

            return $est;
        });

        // 4. Curva Logística Teórica Sigmoide (0% al 100% de inasistencia)
        $curvaSigmoide = [];
        for ($i = 0; $i <= 60; $i += 2) {
            $faltasAprox = round(($i / 100) * 30);
            $zTeorico = $beta0 + ($beta1 * $faltasAprox) + ($beta2 * $i);
            $pTeorico = round((1 / (1 + exp(-$zTeorico))) * 100, 1);
            $curvaSigmoide[] = ['x' => $i, 'y' => $pTeorico];
        }

        return view('cpanel.orientacion.graficas-desercion', [
            'grupos'            => $grupos,
            'grupoId'           => $grupoId,
            'totalEstudiantes'  => $estudiantes->count(),
            'conteoBajo'        => $conteoBajo,
            'conteoMedio'       => $conteoMedio,
            'conteoCritico'     => $conteoCritico,
            'curvaSigmoide'     => $curvaSigmoide,
            'puntosDispersion'  => $puntosDispersion,
            'etiquetasGrupos'   => array_keys($riesgoPorGrupo),
            'criticosGrupos'    => array_column($riesgoPorGrupo, 'criticos'),
            'moderadosGrupos'   => array_column($riesgoPorGrupo, 'moderados'),
            'alumnosCriticos'   => $estudiantesProcesados->where('nivel_riesgo', 'Crítico')->sortByDesc('probabilidad')->take(10),
        ]);
    }

    private function desencriptarDato(?string $val): string
    {
        if (empty($val)) return '';
        try {
            if (is_string($val) && (str_starts_with($val, 'ey') || strlen($val) > 50)) {
                return decrypt($val);
            }
        } catch (DecryptException $e) {}
        return str_replace(' (Plain)', '', $val);
    }
}

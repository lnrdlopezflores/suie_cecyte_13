@extends('cpanel/plantillaorientacion')
@section('title', 'Analítica Predictiva de Deserción')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-6 space-y-6 text-xs transition-colors duration-200">

    <!-- CABECERA PRINCIPAL -->
    <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-3xs flex flex-col md:flex-row md:items-center justify-between gap-4 transition-colors">
        <div>
            <div class="flex items-center gap-2 text-custom-primary text-[10px] font-black uppercase tracking-widest mb-1">
                <span class="material-icons-round text-base">batch_prediction</span>
                <span>Orientación Educativa • Modelo Estadístico</span>
            </div>
            <h2 class="text-xl font-black text-slate-900 dark:text-slate-100">
                Modelo de Regresión Logística — Probabilidad de Deserción
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-xs">
                Inferencia basada en la función sigmoide sobre la tasa de inasistencias y faltas acumuladas.
            </p>
        </div>

        <form action="{{ route('orientacion.graficas.index') }}" method="GET" class="flex items-center gap-2 shrink-0">
            <select name="grupo_id" onchange="this.form.submit()"
                    class="bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 rounded-2xl px-3 py-2 text-xs font-semibold focus:ring-1 focus:ring-custom-primary focus:outline-hidden transition-colors cursor-pointer">
                <option value="">Todo el Plantel (General)</option>
                @php
                    if (!empty($grupos)) {
                        foreach ($grupos as $g) {$sel = ((string)($grupoId ?? '') === (string)$g->id) ? 'selected' : '';
                            echo "<option value=\"{$g->id}\" {$sel}>{$g->semestre}° \"{$g->grupo}\" — {$g->especialidad}</option>";
                        }
                    }
                @endphp
            </select>
            @if(!empty($grupoId))
                <a href="{{ route('orientacion.graficas.index') }}" class="p-2 bg-slate-100 dark:bg-slate-800 text-slate-500 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors" title="Ver todo">
                    <span class="material-icons-round text-sm">clear</span>
                </a>
            @endif
        </form>
    </div>

    <!-- TARJETAS DE CONTEO RÁPIDO SEGÚN CLASIFICADOR SIGMOIDE -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white dark:bg-slate-900 p-4.5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-3xs flex items-center gap-3.5">
            <div class="w-11 h-11 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl flex items-center justify-center shrink-0 border border-slate-200 dark:border-slate-700">
                <span class="material-icons-round text-xl">groups</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">Matrícula Evaluada</p>
                <p class="text-base font-black text-slate-900 dark:text-slate-100">{{ $totalEstudiantes ?? 0 }} Alumnos</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-4.5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-3xs flex items-center gap-3.5">
            <div class="w-11 h-11 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center shrink-0 border border-emerald-200 dark:border-emerald-800/80">
                <span class="material-icons-round text-xl">verified</span>
            </div>
            <div>
                <p class="text-[10px] text-emerald-600 dark:text-emerald-400 uppercase font-bold tracking-wider">Riesgo Bajo (P &lt; 35%)</p>
                <p class="text-base font-black text-slate-900 dark:text-slate-100">{{ $conteoBajo ?? 0 }} Alumnos</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-4.5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-3xs flex items-center gap-3.5">
            <div class="w-11 h-11 bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 rounded-xl flex items-center justify-center shrink-0 border border-amber-200 dark:border-amber-800/80">
                <span class="material-icons-round text-xl">warning</span>
            </div>
            <div>
                <p class="text-[10px] text-amber-600 dark:text-amber-400 uppercase font-bold tracking-wider">Riesgo Moderado (35-70%)</p>
                <p class="text-base font-black text-slate-900 dark:text-slate-100">{{ $conteoMedio ?? 0 }} Alumnos</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-4.5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-3xs flex items-center gap-3.5">
            <div class="w-11 h-11 bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 rounded-xl flex items-center justify-center shrink-0 border border-rose-200 dark:border-rose-800/80">
                <span class="material-icons-round text-xl">dangerous</span>
            </div>
            <div>
                <p class="text-[10px] text-rose-600 dark:text-rose-400 uppercase font-bold tracking-wider">Riesgo Crítico (P &ge; 70%)</p>
                <p class="text-base font-black text-slate-900 dark:text-slate-100">{{ $conteoCritico ?? 0 }} Alumnos</p>
            </div>
        </div>

    </div>

    <!-- SECCIÓN DE GRÁFICAS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- GRÁFICA 1: CURVA LOGÍSTICA SIGMOIDE Y PUNTOS REALES (8 Cols) -->
        <div class="lg:col-span-8 bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-3">
                <div class="space-y-0.5">
                    <h3 class="font-black text-slate-900 dark:text-slate-100 text-xs uppercase tracking-wider flex items-center gap-2">
                        <span class="material-icons-round text-custom-primary text-base">show_chart</span>
                        Curva Sigmoide Logística vs Alumnos Mapeados
                    </h3>
                    <p class="text-[11px] text-slate-400">Eje X: % Inasistencia | Eje Y: Probabilidad Estimada de Abandono Escolar (%)</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 font-mono text-[10px] font-bold text-slate-600 dark:text-slate-300">
                    P = 1 / (1 + e^-z)
                </span>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="chartCurvaLogistica"></canvas>
            </div>
        </div>

        <!-- GRÁFICA 2: DISTRIBUCIÓN DE RIESGO DE DESERCIÓN (4 Cols) -->
        <div class="lg:col-span-4 bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-3">
                <h3 class="font-black text-slate-900 dark:text-slate-100 text-xs uppercase tracking-wider flex items-center gap-2">
                    <span class="material-icons-round text-amber-500 text-base">pie_chart</span>
                    Proporción de Vulnerabilidad
                </h3>
            </div>
            <div class="relative h-64 w-full flex items-center justify-center">
                <canvas id="chartDonaRiesgo"></canvas>
            </div>
            <div class="grid grid-cols-3 gap-2 pt-3 border-t border-slate-100 dark:border-slate-800 text-center text-[10px] font-bold">
                <div class="text-emerald-600 dark:text-emerald-400">Bajo: {{ $conteoBajo ?? 0 }}</div>
                <div class="text-amber-600 dark:text-amber-400">Moderado: {{ $conteoMedio ?? 0 }}</div>
                <div class="text-rose-600 dark:text-rose-400">Crítico: {{ $conteoCritico ?? 0 }}</div>
            </div>
        </div>

        <!-- GRÁFICA 3: MATRIZ DE RIESGO CRÍTICO POR GRUPO / ESPECIALIDAD (12 Cols) -->
        <div class="lg:col-span-12 bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-3">
                <div class="space-y-0.5">
                    <h3 class="font-black text-slate-900 dark:text-slate-100 text-xs uppercase tracking-wider flex items-center gap-2">
                        <span class="material-icons-round text-rose-500 text-base">bar_chart</span>
                        Incidencia de Riesgo Crítico y Moderado por Salón
                    </h3>
                    <p class="text-[11px] text-slate-400">Distribución de estudiantes con mayor probabilidad de deserción segmentados por grupo escolar.</p>
                </div>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="chartGrupos"></canvas>
            </div>
        </div>

    </div>

    <!-- TABLA DE ALUMNOS EN FOCO ROJO PREDICTIVO -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
        <div class="p-4 bg-slate-50/60 dark:bg-slate-800/40 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2 font-black text-xs uppercase tracking-wider text-slate-800 dark:text-slate-200">
                <span class="material-icons-round text-rose-600 text-base">warning</span>
                <span>Top 10 Casos Prioritarios para Intervención Inmediata</span>
            </div>
            <a href="{{ route('asistencias.criticas') }}" class="text-custom-primary hover:underline font-bold text-xs flex items-center gap-1">
                <span>Ver alertas y citar tutores</span>
                <span class="material-icons-round text-xs">arrow_forward</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 text-[10px] font-black uppercase text-slate-400">
                        <th class="p-3 pl-6">Estudiante</th>
                        <th class="p-3">Semestre / Grupo</th>
                        <th class="p-3 text-center">Faltas Reales</th>
                        <th class="p-3 text-center">% Inasistencia</th>
                        <th class="p-3 text-center">Probabilidad de Deserción</th>
                        <th class="p-3 text-right pr-6">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs">
                    @php $hayAlumnos = !empty($alumnosCriticos) && count($alumnosCriticos) > 0; @endphp
                    @if($hayAlumnos)
                        @php foreach ($alumnosCriticos as$critico): @endphp
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="p-3 pl-6">
                                    <p class="font-extrabold text-slate-900 dark:text-slate-100">{{ $critico->nombre_completo }}</p>
                                    <span class="font-mono text-[10px] text-slate-400">{{ $critico->matricula }}</span>
                                </td>
                                <td class="p-3">
                                    <span class="font-bold text-slate-700 dark:text-slate-300">{{ $critico->semestre }}° "{{ $critico->grupo }}"</span>
                                    <p class="text-[10px] text-slate-400">{{ $critico->especialidad }}</p>
                                </td>
                                <td class="p-3 text-center font-bold text-slate-700 dark:text-slate-300">
                                    {{ $critico->faltas }}
                                </td>
                                <td class="p-3 text-center font-mono font-black text-rose-600 dark:text-rose-400">
                                    {{ $critico->pct_inasistencia }}%
                                </td>
                                <td class="p-3 text-center">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl font-black font-mono text-xs bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-900/60">
                                        {{ $critico->probabilidad }}%
                                    </span>
                                </td>
                                <td class="p-3 text-right pr-6">
                                    <a href="{{ route('asistencias.criticas', ['grupo_id' => $critico->grupo_id]) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-custom-primary hover:bg-custom-primary-hover text-white rounded-xl font-bold text-xs shadow-3xs transition-colors">
                                        <span class="material-icons-round text-xs">notification_important</span>
                                        <span>Activar Alerta</span>
                                    </a>
                                </td>
                            </tr>
                        @php endforeach; @endphp
                    @else
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400 dark:text-slate-500">
                                No se detectan alumnos en umbral crítico con los parámetros actuales.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(51, 65, 85, 0.4)' : 'rgba(226, 232, 240, 0.7)';
        const textColor = isDark ? '#94a3b8' : '#64748b';
        const primarioHex = localStorage.getItem('suie_primary_color') || '#841B44';

        // 1. GRÁFICA: CURVA SIGMOIDE LOGÍSTICA + DISPERSIÓN DE ESTUDIANTES
        const ctxCurva = document.getElementById('chartCurvaLogistica');
        if (ctxCurva) {
            new Chart(ctxCurva, {
                type: 'scatter',
                data: {
                    datasets: [
                        {
                            type: 'line',
                            label: 'Curva Sigmoide Teórica (Logit)',
                            data: @json($curvaSigmoide ?? []),
                            borderColor: primarioHex,
                            borderWidth: 2.5,
                            fill: false,
                            tension: 0.4,
                            pointRadius: 0,
                        },
                        {
                            type: 'scatter',
                            label: 'Estudiantes Mapeados',
                            data: @json($puntosDispersion ?? []),
                            backgroundColor: function(context) {
                                const val = context.raw ? context.raw.y : 0;
                                if (val >= 70) return '#e11d48';
                                if (val >= 35) return '#f59e0b';
                                return '#10b981';
                            },
                            pointRadius: 4.5,
                            pointHoverRadius: 7,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: { color: textColor, font: { size: 11, weight: '700' } }
                        },
                        tooltip: {
                            backgroundColor: isDark ? '#0f172a' : '#ffffff',
                            titleColor: isDark ? '#f8fafc' : '#0f172a',
                            bodyColor: isDark ? '#cbd5e1' : '#334155',
                            borderColor: isDark ? '#334155' : '#e2e8f0',
                            borderWidth: 1,
                            callbacks: {
                                label: function(ctx) {
                                    if (ctx.raw && ctx.raw.nombre) {
                                        return `${ctx.raw.nombre}: P(Deserción) = ${ctx.raw.y}% (${ctx.raw.faltas} faltas)`;
                                    }
                                    return `Curva Sigmoide: ${ctx.raw ? ctx.raw.y : 0}%`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: { display: true, text: '% de Inasistencia', color: textColor, font: { weight: 'bold' } },
                            grid: { color: gridColor },
                            ticks: { color: textColor, callback: val => val + '%' }
                        },
                        y: {
                            min: 0,
                            max: 100,
                            title: { display: true, text: 'Probabilidad (%)', color: textColor, font: { weight: 'bold' } },
                            grid: { color: gridColor },
                            ticks: { color: textColor, callback: val => val + '%' }
                        }
                    }
                }
            });
        }

        // 2. GRÁFICA: DONA DE CLASIFICACIÓN DE RIESGO
        const ctxDona = document.getElementById('chartDonaRiesgo');
        if (ctxDona) {
            new Chart(ctxDona, {
                type: 'doughnut',
                data: {
                    labels: ['Riesgo Bajo (<35%)', 'Riesgo Moderado (35-70%)', 'Riesgo Crítico (≥70%)'],
                    datasets: [{
                        data: [{{ $conteoBajo ?? 0 }}, {{ $conteoMedio ?? 0 }}, {{ $conteoCritico ?? 0 }}],
                        backgroundColor: ['#10b981', '#f59e0b', '#e11d48'],
                        borderWidth: 2,
                        borderColor: isDark ? '#0f172a' : '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: textColor, boxWidth: 12, font: { size: 10, weight: '600' } }
                        }
                    },
                    cutout: '68%'
                }
            });
        }

        // 3. GRÁFICA: BARRAS DE RIESGO POR GRUPO
        const ctxGrupos = document.getElementById('chartGrupos');
        if (ctxGrupos) {
            new Chart(ctxGrupos, {
                type: 'bar',
                data: {
                    labels: @json($etiquetasGrupos ?? []),
                    datasets: [
                        {
                            label: 'Casos Críticos (P ≥ 70%)',
                            data: @json($criticosGrupos ?? []),
                            backgroundColor: '#e11d48',
                            borderRadius: 6,
                        },
                        {
                            label: 'Casos Moderados (35-70%)',
                            data: @json($moderadosGrupos ?? []),
                            backgroundColor: '#f59e0b',
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: { color: textColor, font: { size: 10, weight: '700' } }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: gridColor },
                            ticks: { color: textColor, font: { size: 9, weight: '600' } }
                        },
                        y: {
                            grid: { color: gridColor },
                            ticks: { color: textColor, stepSize: 1 }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
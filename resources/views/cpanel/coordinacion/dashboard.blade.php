@extends('cpanel/plantillacoordinacion')
@section('title', 'Dashboard de Titulación')

@section('content')
<!-- Importación de Chart.js para las gráficas interactivas -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="flex-1 max-w-7xl w-full mx-auto p-5 md:p-8 space-y-8 text-sm md:text-base transition-colors duration-200">

    <!-- ENCABEZADO Y TÍTULO -->
    <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-custom-light text-custom-primary text-xs font-black rounded-lg uppercase tracking-wider">
                <span class="material-icons-round text-sm">insights</span> Indicadores de Eficiencia
            </span>
            <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-slate-100 flex items-center gap-3">
                Monitoreo de Titulación
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm">
                Auditoría cuantitativa de protocolos registrados, aprobados por sínodo, liberados para exposición y dictámenes emitidos.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('coordinador.proyectos.index') }}" 
               class="px-5 py-3 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold text-xs md:text-sm rounded-2xl shadow-sm transition-all flex items-center gap-2 cursor-pointer">
                <span class="material-icons-round text-base">folder_open</span>
                <span>Ver Proyectos Registrados</span>
            </a>
        </div>
    </div>

    <!-- TARJETAS KPI (ESTADOS PRINCIPALES) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        
        <!-- 1. Total General -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-[11px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Proyectos</p>
                <p class="text-2xl md:text-3xl font-black text-slate-900 dark:text-slate-100">{{ $totalProyectos }}</p>
                <span class="text-[11px] font-bold text-slate-400">En ambas especialidades</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center">
                <span class="material-icons-round text-2xl">folder_special</span>
            </div>
        </div>

        <!-- 2. Aprobados -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-emerald-200/80 dark:border-emerald-900/50 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-[11px] font-extrabold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Aprobados</p>
                <p class="text-2xl md:text-3xl font-black text-emerald-700 dark:text-emerald-300">{{ $totalAprobados }}</p>
                <span class="text-[11px] font-bold text-slate-400">
                    {{ $totalProyectos > 0 ? round(($totalAprobados / $totalProyectos) * 100, 1) : 0 }}% de la matrícula
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-100 dark:border-emerald-900">
                <span class="material-icons-round text-2xl">verified</span>
            </div>
        </div>

        <!-- 3. Liberados -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-blue-200/80 dark:border-blue-900/50 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-[11px] font-extrabold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Liberados p/ Exposición</p>
                <p class="text-2xl md:text-3xl font-black text-blue-700 dark:text-blue-300">{{ $totalLiberados }}</p>
                <span class="text-[11px] font-bold text-slate-400">Listos para defensa</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-blue-900">
                <span class="material-icons-round text-2xl">record_voice_over</span>
            </div>
        </div>

        <!-- 4. Rechazados -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-rose-200/80 dark:border-rose-900/50 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-[11px] font-extrabold text-rose-600 dark:text-rose-400 uppercase tracking-wider">Rechazados</p>
                <p class="text-2xl md:text-3xl font-black text-rose-700 dark:text-rose-300">{{ $totalRechazados }}</p>
                <span class="text-[11px] font-bold text-slate-400">Dictamen negativo</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center border border-rose-100 dark:border-rose-900">
                <span class="material-icons-round text-2xl">cancel</span>
            </div>
        </div>

    </div>

    <!-- SECCIÓN DE LAS 3 GRÁFICAS DEL SISTEMA -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- GRÁFICA 1: DONA DE DISTRIBUCIÓN POR ESTADOS -->
        <div class="bg-white dark:bg-slate-900 p-6 md:p-7 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-between space-y-4">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-custom-primary block mb-1">Gráfica 1 • Proporción</span>
                <h3 class="font-black text-base md:text-lg text-slate-900 dark:text-slate-100 flex items-center gap-2">
                    <span class="material-icons-round text-lg text-custom-primary">pie_chart</span>
                    Distribución de Estados
                </h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Porcentaje comparativo entre los 3 dictámenes del protocolo.</p>
            </div>
            
            <div class="relative w-full aspect-square max-h-64 mx-auto flex items-center justify-center">
                <canvas id="graficaDistribucion"></canvas>
            </div>

            <div class="pt-2 border-t border-slate-100 dark:border-slate-800 grid grid-cols-3 text-center text-xs">
                <div>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block mr-1"></span>
                    <span class="font-bold text-slate-700 dark:text-slate-300">Aprobados</span>
                </div>
                <div>
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500 inline-block mr-1"></span>
                    <span class="font-bold text-slate-700 dark:text-slate-300">Liberados</span>
                </div>
                <div>
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block mr-1"></span>
                    <span class="font-bold text-slate-700 dark:text-slate-300">Rechazados</span>
                </div>
            </div>
        </div>

        <!-- GRÁFICA 2: BARRAS AGRUPADAS POR CARRERA -->
        <div class="bg-white dark:bg-slate-900 p-6 md:p-7 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-between space-y-4">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-600 dark:text-indigo-400 block mb-1">Gráfica 2 • Carreras</span>
                <h3 class="font-black text-base md:text-lg text-slate-900 dark:text-slate-100 flex items-center gap-2">
                    <span class="material-icons-round text-lg text-indigo-600 dark:text-indigo-400">bar_chart</span>
                    Estados por Especialidad
                </h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Animación Digital vs. Química Industrial.</p>
            </div>

            <div class="relative w-full aspect-square max-h-64 mx-auto flex items-center justify-center">
                <canvas id="graficaCarreras"></canvas>
            </div>

            <div class="pt-2 border-t border-slate-100 dark:border-slate-800 flex justify-center gap-4 text-xs">
                <span class="flex items-center gap-1.5 font-bold text-slate-600 dark:text-slate-300">
                    <span class="w-3 h-3 rounded-md bg-indigo-500"></span> Animación
                </span>
                <span class="flex items-center gap-1.5 font-bold text-slate-600 dark:text-slate-300">
                    <span class="w-3 h-3 rounded-md bg-emerald-500"></span> Química
                </span>
            </div>
        </div>

        <!-- GRÁFICA 3: RADAR / EFICACIA GLOBAL DEL PROCESO -->
        <div class="bg-white dark:bg-slate-900 p-6 md:p-7 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-between space-y-4">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-600 dark:text-emerald-400 block mb-1">Gráfica 3 • Métricas de Cierre</span>
                <h3 class="font-black text-base md:text-lg text-slate-900 dark:text-slate-100 flex items-center gap-2">
                    <span class="material-icons-round text-lg text-emerald-600 dark:text-emerald-400">donut_large</span>
                    Estatus Concluidos vs Revisión
                </h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Avance de dictaminación contra pendientes.</p>
            </div>

            <div class="relative w-full aspect-square max-h-64 mx-auto flex items-center justify-center">
                <canvas id="graficaEfectividad"></canvas>
            </div>

            <div class="pt-2 border-t border-slate-100 dark:border-slate-800 flex justify-between text-xs font-semibold px-2">
                <span class="text-slate-500 dark:text-slate-400">Dictaminados: <strong class="text-slate-800 dark:text-slate-200">{{ $totalAprobados + $totalLiberados + $totalRechazados }}</strong></span>
                <span class="text-slate-500 dark:text-slate-400">En Revisión: <strong class="text-amber-600 dark:text-amber-400">{{ $totalRevision }}</strong></span>
            </div>
        </div>

    </div>

    <!-- TABLA DE ACTIVIDAD RECIENTE -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/30">
            <div>
                <h3 class="font-black text-slate-900 dark:text-slate-100 text-base flex items-center gap-2">
                    <span class="material-icons-round text-lg text-custom-primary">history</span>
                    Últimos Proyectos Registrados
                </h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Protocolos incorporados recientemente a la plataforma.</p>
            </div>
            <a href="{{ route('coordinador.proyectos.index') }}" class="text-xs font-bold text-custom-primary hover:underline">
                Ver todos los registros →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs md:text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-100 dark:border-slate-800 text-slate-400 text-[11px] font-black uppercase tracking-wider">
                        <th class="p-4 pl-6">Folio / Título</th>
                        <th class="p-4">Especialidad</th>
                        <th class="p-4">Modalidad</th>
                        <th class="p-4 text-center">Estatus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($ultimosProyectos as $proy)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="p-4 pl-6 font-bold text-slate-900 dark:text-slate-100">
                                <span class="font-mono text-xs text-custom-primary mr-2">#{{ \Illuminate\Support\Str::padLeft($proy->id, 4, '0') }}</span>
                                {{ $proy->titulo }}
                            </td>
                            <td class="p-4">
                                <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $proy->especialidad }}</span>
                            </td>
                            <td class="p-4 text-slate-500 dark:text-slate-400">
                                {{ $proy->modalidad }}
                            </td>
                            <td class="p-4 text-center">
                                @switch($proy->estatus)
                                    @case('Aprobado')
                                        <span class="px-3 py-1 text-[11px] font-black rounded-full uppercase bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900">
                                            Aprobado
                                        </span>
                                        @break
                                    @case('Liberado_Exposicion')
                                    @case('Liberado')
                                        <span class="px-3 py-1 text-[11px] font-black rounded-full uppercase bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-900">
                                            Liberado
                                        </span>
                                        @break
                                    @case('Rechazado')
                                        <span class="px-3 py-1 text-[11px] font-black rounded-full uppercase bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-900">
                                            Rechazado
                                        </span>
                                        @break
                                    @default
                                        <span class="px-3 py-1 text-[11px] font-black rounded-full uppercase bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-900">
                                            En Revisión
                                        </span>
                                @endswitch
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400 dark:text-slate-500 italic">No hay proyectos dados de alta en este ciclo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</main>

<!-- SCRIPT DE CONFIGURACIÓN DE LAS 3 GRÁFICAS -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const esOscuro = document.documentElement.classList.contains('dark');
        const colorTexto = esOscuro ? '#94a3b8' : '#64748b';
        const colorBordeGrid = esOscuro ? '#1e293b' : '#f1f5f9';

        // 1. Gráfica de Distribución (Doughnut)
        const ctx1 = document.getElementById('graficaDistribucion').getContext('2d');
        new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['Aprobados', 'Liberados', 'Rechazados'],
                datasets: [{
                    data: [{{ $totalAprobados }}, {{ $totalLiberados }}, {{ $totalRechazados }}],
                    backgroundColor: ['#10b981', '#3b82f6', '#f43f5e'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': ' + context.raw + ' proyectos';
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });

        // 2. Gráfica de Barras por Carrera
        const ctx2 = document.getElementById('graficaCarreras').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Aprobados', 'Liberados', 'Rechazados'],
                datasets: [
                    {
                        label: 'Animación Digital',
                        data: [{{ $animacionAprobados }}, {{ $animacionLiberados }}, {{ $animacionRechazados }}],
                        backgroundColor: '#6366f1',
                        borderRadius: 8
                    },
                    {
                        label: 'Química Industrial',
                        data: [{{ $quimicaAprobados }}, {{ $quimicaLiberados }}, {{ $quimicaRechazados }}],
                        backgroundColor: '#10b981',
                        borderRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: colorTexto, font: { weight: 'bold' } }
                    },
                    y: {
                        grid: { color: colorBordeGrid },
                        ticks: { color: colorTexto, stepSize: 1 }
                    }
                }
            }
        });

        // 3. Gráfica Polar / Efectividad (Dictaminados vs Pendientes)
        const ctx3 = document.getElementById('graficaEfectividad').getContext('2d');
        new Chart(ctx3, {
            type: 'pie',
            data: {
                labels: ['Dictaminados (Aprob/Lib/Rech)', 'En Proceso / Pendientes'],
                datasets: [{
                    data: [{{ $totalAprobados + $totalLiberados + $totalRechazados }}, {{ $totalRevision }}],
                    backgroundColor: ['#0284c7', '#f59e0b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': ' + context.raw + ' proyectos';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection

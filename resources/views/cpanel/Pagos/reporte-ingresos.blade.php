@extends('cpanel.plantillafinazas')
@section('title', 'Reporte de Ingresos y Analítica')

@section('content')
<!-- Importación CDN de Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-6 space-y-6 text-xs transition-colors duration-200">

    <!-- CABECERA DEL REPORTE -->
    <div class="bg-white dark:bg-slate-900 p-4 md:p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-3xs flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-colors">
        <div>
            <div class="flex items-center gap-2 text-custom-primary text-[10px] font-black uppercase tracking-widest mb-0.5">
                <span class="material-icons-round text-base">analytics</span>
                <span>Finanzas y Caja • Estadísticas Financieras</span>
            </div>
            <h2 class="text-lg md:text-xl font-black text-slate-900 dark:text-slate-100">
                Tablero de Ingresos y Movimientos
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-xs">
                Auditoría periódica de ingresos recaudados, validaciones operativas y saldos en espera.
            </p>
        </div>

        <form action="{{ route('contador.reportes.index') }}" method="GET" class="flex items-center gap-2">
            <label class="text-[11px] font-bold text-slate-500 dark:text-slate-400">Ciclo:</label>
            <select name="anio" onchange="this.form.submit()"
                    class="bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-1.5 text-xs font-semibold focus:ring-1 focus:ring-custom-primary focus:outline-hidden transition-colors cursor-pointer">
                @for($y = Carbon\Carbon::now()->year; $y >= Carbon\Carbon::now()->year - 3; $y--)
                    <option value="{{ $y }}" {{ $anio == $y ? 'selected' : '' }}>Año Fiscal {{ $y }}</option>
                @endfor
            </select>
        </form>
    </div>

    <!-- TARJETAS DE MÉTRICAS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Recaudado -->
        <div class="bg-white dark:bg-slate-900 p-4.5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-3xs flex items-center gap-3.5 transition-colors">
            <div class="w-11 h-11 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/60 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round text-xl">payments</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">Recaudado (Efectivo)</p>
                <p class="text-base font-black text-slate-900 dark:text-slate-100 font-mono">${{ number_format($totalRecaudado, 2) }}</p>
            </div>
        </div>

        <!-- Fichas Registradas -->
        <div class="bg-white dark:bg-slate-900 p-4.5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-3xs flex items-center gap-3.5 transition-colors">
            <div class="w-11 h-11 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-900/60 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round text-xl">receipt_long</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">Total Registrados</p>
                <p class="text-base font-black text-slate-900 dark:text-slate-100">{{ $conteoRegistrados }} Pagos</p>
            </div>
        </div>

        <!-- En Proceso de Validación -->
        <div class="bg-white dark:bg-slate-900 p-4.5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-3xs flex items-center gap-3.5 transition-colors">
            <div class="w-11 h-11 bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-900/60 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round text-xl">hourglass_top</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">Por Conciliar (Monto)</p>
                <p class="text-base font-black text-slate-900 dark:text-slate-100 font-mono">${{ number_format($totalPendiente, 2) }}</p>
            </div>
        </div>

        <!-- Total Validados -->
        <div class="bg-white dark:bg-slate-900 p-4.5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-3xs flex items-center gap-3.5 transition-colors">
            <div class="w-11 h-11 bg-custom-light text-custom-primary border border-custom-primary/30 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round text-xl">verified</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">Folios Aprobados</p>
                <p class="text-base font-black text-slate-900 dark:text-slate-100">{{ $conteoValidados }} Folios</p>
            </div>
        </div>

    </div>

    <!-- SECCIÓN DE LAS 4 GRÁFICAS (GRID 2x2) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- GRÁFICA 1: INGRESOS TOTALES RECAUDADOS ($) -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-2">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-xs uppercase tracking-wider">Ingresos Totales Recaudados ($)</h3>
                </div>
                <span class="text-[10px] font-mono text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-md">Moneda Nacional</span>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="chartIngresosTotales"></canvas>
            </div>
        </div>

        <!-- GRÁFICA 2: PAGOS REGISTRADOS -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-2">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                    <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-xs uppercase tracking-wider">Pagos Registrados (Carga de Alumnos)</h3>
                </div>
                <span class="text-[10px] font-mono text-indigo-600 dark:text-indigo-400 font-bold bg-indigo-50 dark:bg-indigo-950/60 px-2 py-0.5 rounded-md">Comprobantes</span>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="chartPagosRegistrados"></canvas>
            </div>
        </div>

        <!-- GRÁFICA 3: PAGOS PENDIENTES DE VALIDAR -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-2">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-xs uppercase tracking-wider">Fichas Pendientes de Conciliación</h3>
                </div>
                <span class="text-[10px] font-mono text-amber-600 dark:text-amber-400 font-bold bg-amber-50 dark:bg-amber-950/60 px-2 py-0.5 rounded-md">En Revisión</span>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="chartPagosPendientes"></canvas>
            </div>
        </div>

        <!-- GRÁFICA 4: PAGOS VALIDADOS Y CONCILIADOS -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-2">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                    <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-xs uppercase tracking-wider">Pagos Validados y Sellados</h3>
                </div>
                <span class="text-[10px] font-mono text-rose-600 dark:text-rose-400 font-bold bg-rose-50 dark:bg-rose-950/60 px-2 py-0.5 rounded-md">Aprobados</span>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="chartPagosValidados"></canvas>
            </div>
        </div>

    </div>

</main>

<!-- CONFIGURACIÓN DE LOS GRÁFICOS CHART.JS -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(51, 65, 85, 0.4)' : 'rgba(226, 232, 240, 0.7)';
        const textColor = isDark ? '#94a3b8' : '#64748b';

        const labelsMeses = @json($meses);
        const primarioHex = localStorage.getItem('suie_primary_color') || '#841B44';

        const configuracionComun = (tituloY, esMoneda = false) => ({
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: isDark ? '#0f172a' : '#ffffff',
                    titleColor: isDark ? '#f8fafc' : '#0f172a',
                    bodyColor: isDark ? '#cbd5e1' : '#334155',
                    borderColor: isDark ? '#334155' : '#e2e8f0',
                    borderWidth: 1,
                    padding: 10,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            label += esMoneda ? '$' + context.parsed.y.toLocaleString('es-MX', { minimumFractionDigits: 2 }) : context.parsed.y;
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: gridColor },
                    ticks: { color: textColor, font: { size: 10, weight: '600' } }
                },
                y: {
                    grid: { color: gridColor },
                    ticks: {
                        color: textColor,
                        font: { size: 10, weight: '600' },
                        callback: function(value) {
                            return esMoneda ? '$' + value.toLocaleString() : value;
                        }
                    }
                }
            }
        });

        // 1. Gráfica: Ingresos Totales Recaudados (Área suave verde)
        new Chart(document.getElementById('chartIngresosTotales'), {
            type: 'line',
            data: {
                labels: labelsMeses,
                datasets: [{
                    label: 'Recaudación',
                    data: @json($datosIngresos),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.15)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#10b981',
                    pointRadius: 4,
                }]
            },
            options: configuracionComun('Monto ($)', true)
        });

        // 2. Gráfica: Pagos Registrados (Barras Índigo)
        new Chart(document.getElementById('chartPagosRegistrados'), {
            type: 'bar',
            data: {
                labels: labelsMeses,
                datasets: [{
                    label: 'Registrados',
                    data: @json($datosRegistrados),
                    backgroundColor: '#6366f1',
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: configuracionComun('Fichas')
        });

        // 3. Gráfica: Pagos Pendientes (Línea Ámbar)
        new Chart(document.getElementById('chartPagosPendientes'), {
            type: 'line',
            data: {
                labels: labelsMeses,
                datasets: [{
                    label: 'Pendientes',
                    data: @json($datosPendientes),
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.12)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#f59e0b',
                    pointRadius: 4,
                }]
            },
            options: configuracionComun('Pendientes')
        });

        // 4. Gráfica: Pagos Validados (Barras con color institucional SUIE)
        new Chart(document.getElementById('chartPagosValidados'), {
            type: 'bar',
            data: {
                labels: labelsMeses,
                datasets: [{
                    label: 'Validados',
                    data: @json($datosValidados),
                    backgroundColor: primarioHex,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: configuracionComun('Validados')
        });
    });
</script>
@endsection
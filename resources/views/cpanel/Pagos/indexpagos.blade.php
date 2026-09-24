@extends('cpanel/plantillafinazas')
@section('title', 'Fila de Validaciones')
@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-6 space-y-6 text-xs transition-colors duration-200">

    <!-- MENSAJES DE NOTIFICACIÓN DE CAJA -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 p-3.5 rounded-2xl flex items-center justify-between font-semibold shadow-3xs transition-colors">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-base text-emerald-600 dark:text-emerald-400">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-100 cursor-pointer">
                <span class="material-icons-round text-base">close</span>
            </button>
        </div>
    @endif

    <!-- MÉTRICAS DE CONTROL FINANCIERO -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        
        <!-- Por Verificar -->
        <div class="bg-white dark:bg-slate-900 p-4.5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-3xs flex items-center gap-4 transition-colors">
            <div class="w-11 h-11 bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-900/60 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round text-xl">hourglass_empty</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">Por Verificar</p>
                <p class="text-lg font-black text-slate-900 dark:text-slate-100">{{ $totales['Pendiente'] ?? 0 }} Fichas</p>
            </div>
        </div>

        <!-- Aprobados (Mes) -->
        <div class="bg-white dark:bg-slate-900 p-4.5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-3xs flex items-center gap-4 transition-colors">
            <div class="w-11 h-11 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/60 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round text-xl">task_alt</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">Aprobados (Mes)</p>
                <p class="text-lg font-black text-slate-900 dark:text-slate-100">{{ $totales['Pagado'] ?? 0 }} Depósitos</p>
            </div>
        </div>

        <!-- Reportes Vencidos -->
        <div class="bg-white dark:bg-slate-900 p-4.5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-3xs flex items-center gap-4 transition-colors">
            <div class="w-11 h-11 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-900/60 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round text-xl">error_outline</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">Reportes Vencidos</p>
                <p class="text-lg font-black text-slate-900 dark:text-slate-100">{{ $totales['Vencido'] ?? 0 }} Alumnos</p>
            </div>
        </div>

    </div>

    <!-- PANEL DE ACCIONES Y FILTRADO -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl shadow-xs border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4 transition-colors">
        
        <form action="{{ route('contador.pagos.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            
            <!-- Buscador -->
            <div class="relative w-full sm:w-80">
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl pl-9 pr-4 py-2 text-xs font-medium focus:ring-1 focus:ring-custom-primary focus:outline-hidden placeholder:text-slate-400 dark:placeholder:text-slate-500 transition-colors" 
                       placeholder="Buscar por matrícula o alumno...">
                <span class="material-icons-round text-slate-400 dark:text-slate-500 text-sm absolute left-3 top-2.5">search</span>
            </div>

            <!-- Filtro de Estatus -->
            <div>
                <select name="estatus" onchange="this.form.submit()" 
                        class="bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-700 rounded-xl p-2 text-xs font-semibold focus:ring-1 focus:ring-custom-primary focus:outline-hidden transition-colors cursor-pointer">
                    <option value="">Todos los Estatus</option>
                    <option value="Pendiente" {{ request('estatus') == 'Pendiente' ? 'selected' : '' }}>Pendiente de Validar</option>
                    <option value="Pagado" {{ request('estatus') == 'Pagado' ? 'selected' : '' }}>Liquidados / Pagados</option>
                    <option value="Vencido" {{ request('estatus') == 'Vencido' ? 'selected' : '' }}>Vencidos</option>
                </select>
            </div>

            @if(request('buscar') || request('estatus'))
                <a href="{{ route('contador.pagos.index') }}" class="text-custom-primary hover:underline font-bold text-xs flex items-center gap-0.5">
                    <span class="material-icons-round text-sm">clear</span> Limpiar filtros
                </a>
            @endif
        </form>

        <span class="text-xs font-mono text-slate-400 dark:text-slate-500">
            Fichas listadas: <strong class="text-slate-700 dark:text-slate-200">{{ $pagos->total() }}</strong>
        </span>
    </div>

    <!-- TABLA DE CONTROL DE PAGOS -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors duration-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-400 text-[10px] font-black uppercase tracking-wider">
                        <th class="p-4 w-16 text-center">Folio</th>
                        <th class="p-4">Estudiante (Matrícula)</th>
                        <th class="p-4">Concepto</th>
                        <th class="p-4 text-right">Monto</th>
                        <th class="p-4">Referencia Banco</th>
                        <th class="p-4 text-center">Estatus</th>
                        <th class="p-4 text-center w-28">Auditar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs">
                    @forelse($pagos as $pago)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                            
                            <!-- Folio -->
                            <td class="p-4 text-center font-mono font-bold text-slate-400 dark:text-slate-500">#{{ $pago->id }}</td>
                            
                            <!-- Estudiante -->
                            <td class="p-4">
                                <div class="font-bold text-slate-900 dark:text-slate-100">
                                    {{ str_replace(' (Plain)', '', $pago->alumno_nombre) }} 
                                    {{ $pago->alumno_paterno }}
                                </div>
                                <div class="text-[10px] text-slate-400 dark:text-slate-500 font-mono mt-0.5">
                                    Matrícula: {{ $pago->username }}
                                </div>
                            </td>
                            
                            <!-- Concepto -->
                            <td class="p-4 font-semibold text-slate-800 dark:text-slate-200">{{ $pago->concepto }}</td>
                            
                            <!-- Monto -->
                            <td class="p-4 text-right font-mono font-black text-slate-900 dark:text-slate-100">
                                @if(is_numeric($pago->monto))
                                    ${{ number_format($pago->monto, 2) }}
                                @else
                                    ${{ is_numeric(str_replace(' (Legacy)', '', $pago->monto)) ? number_format(str_replace(' (Legacy)', '', $pago->monto), 2) : '0.00' }}
                                @endif
                            </td>
                            
                            <!-- Referencia -->
                            <td class="p-4 font-mono uppercase">
                                @if(strlen($pago->referencia_bancaria) > 60)
                                    <span class="bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 px-2 py-0.5 rounded-md border border-amber-200 dark:border-amber-900/60 font-sans font-bold text-[10px]">
                                        Ref. Cifrada Rota
                                    </span>
                                @else
                                    <span class="bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md border border-slate-200/80 dark:border-slate-700 text-slate-800 dark:text-slate-200 text-xs">
                                        {{ str_replace(' (LEGACY)', '', str_replace(' (Legacy)', '', $pago->referencia_bancaria)) }}
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Estatus Insignia -->
                            <td class="p-4 text-center">
                                @if($pago->estatus == 'Pagado')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Liquidado
                                    </span>
                                @elseif($pago->estatus == 'Pendiente')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 animate-pulse">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Por Validar
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                        {{ $pago->estatus }}
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Acción -->
                            <td class="p-4 text-center">
                                <a href="{{ route('contador.pagos.revisar', $pago->id) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-custom-primary hover:bg-custom-primary-hover text-white font-bold rounded-xl shadow-3xs transition-colors cursor-pointer">
                                    <span class="material-icons-round text-xs">pageview</span>
                                    <span>Revisar</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-10 text-center text-slate-400 dark:text-slate-500 font-medium space-y-2">
                                <span class="material-icons-round text-3xl block text-slate-300 dark:text-slate-700">payments</span>
                                <p>No hay registros de pago en la fila de espera con los criterios seleccionados.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($pagos->hasPages())
            <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40">
                {{ $pagos->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</main>
@endsection
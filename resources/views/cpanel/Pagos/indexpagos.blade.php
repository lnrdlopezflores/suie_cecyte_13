@extends('cpanel/plantillafinazas')
@section('title', 'Fila de Validaciones')
@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-6 space-y-6 text-xs">

    <!-- MENSAJES DE NOTIFICACIÓN DE CAJA -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-3 rounded-xl flex items-center gap-2 font-semibold">
            <span class="material-icons-round text-sm">check_circle</span> {{ session('success') }}
        </div>
    @endif

    <!-- METRICAS DE CONTROL FINANCIERO -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-3xs flex items-center gap-4">
            <div class="w-10 h-10 bg-amber-50 text-amber-700 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round">hourglass_empty</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Por Verificar</p>
                <p class="text-base font-black text-slate-800">{{ $totales['Pendiente'] ?? 0 }} Fichas</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-3xs flex items-center gap-4">
            <div class="w-10 h-10 bg-emerald-50 text-emerald-700 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round">task_alt</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Aprobados (Mes)</p>
                <p class="text-base font-black text-slate-800">{{ $totales['Pagado'] ?? 0 }} Depósitos</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-3xs flex items-center gap-4">
            <div class="w-10 h-10 bg-rose-50 text-rose-700 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round">error_outline</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Reportes Vencidos</p>
                <p class="text-base font-black text-slate-800">{{ $totales['Vencido'] ?? 0 }} Alumnos</p>
            </div>
        </div>
    </div>

    <!-- PANEL DE ACCIONES Y FILTRADO -->
    <div class="bg-white p-4 rounded-xl shadow-xs border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
        
        <form action="{{ route('contador.pagos.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <!-- Buscador -->
            <div class="relative w-full sm:w-80">
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-9 pr-4 py-2 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden placeholder:text-slate-400" 
                       placeholder="Buscar por alumno o referencia...">
                <span class="material-icons-round text-slate-400 text-sm absolute left-3 top-2.5">search</span>
            </div>

            <!-- Filtro Estatus -->
            <div>
                <select name="estatus" onchange="this.form.submit()" 
                        class="bg-slate-50 border border-slate-300 rounded-xl p-2 font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                    <option value="">Todos los Estatus</option>
                    <option value="Pendiente" {{ request('estatus') == 'Pendiente' ? 'selected' : '' }}>Pendiente de Validar</option>
                    <option value="Pagado" {{ request('estatus') == 'Pagado' ? 'selected' : '' }}>Liquidados / Pagados</option>
                    <option value="Vencido" {{ request('estatus') == 'Vencido' ? 'selected' : '' }}>Vencidos</option>
                </select>
            </div>

            @if(request('buscar') || request('estatus'))
                <a href="{{ route('contador.pagos.index') }}" class="text-[#841B44] hover:underline font-semibold flex items-center gap-0.5">
                    <span class="material-icons-round text-sm">clear</span> Limpiar filtros
                </a>
            @endif
        </form>
    </div>

    <!-- TABLA DE CONTROL DE PAGOS -->
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                        <th class="p-4 w-16 text-center">Folio</th>
                        <th class="p-4">Estudiante (Matrícula)</th>
                        <th class="p-4">Concepto</th>
                        <th class="p-4 text-right">Monto</th>
                        <th class="p-4">Referencia Banco</th>
                        <th class="p-4 text-center">Estatus</th>
                        <th class="p-4 text-center w-28">Auditar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-700">
                    @forelse($pagos as $pago)
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            <td class="p-4 text-center font-mono font-bold text-slate-400">#{{ $pago->id }}</td>
                            
                            <td class="p-4">
                                <div class="font-bold text-slate-900">
                                    {{-- Si el nombre contiene la palabra (Plain) debido al catch, la removemos visualmente --}}
                                    {{ str_replace(' (Plain)', '', $pago->alumno_nombre) }} 
                                    {{ $pago->alumno_paterno }}
                                </div>
                                <div class="text-[10px] text-slate-400 font-mono mt-0.5">Matrícula: {{ $pago->username }}</div>
                            </td>
                            
                            <td class="p-4 font-medium text-slate-800">{{ $pago->concepto }}</td>
                            
                            <td class="p-4 text-right font-mono font-black text-slate-900">
                                @if(is_numeric($pago->monto))
                                    ${{ number_format($pago->monto, 2) }}
                                @else
                                    {{-- Si el monto falló y es un string de error, ponemos un guión o valor plano --}}
                                    ${{ is_numeric(str_replace(' (Legacy)', '', $pago->monto)) ? number_format(str_replace(' (Legacy)', '', $pago->monto), 2) : '0.00' }}
                                @endif
                            </td>
                            
                            <td class="p-4 font-mono uppercase">
                                {{-- Si la referencia es un hash gigante corrupto Base64 (mide más de 60 caracteres), ocultamos el hash --}}
                                @if(strlen($pago->referencia_bancaria) > 60)
                                    <span class="bg-amber-50 text-amber-700 px-2 py-0.5 rounded-sm border border-amber-200 font-sans font-bold text-[10px]">
                                        Ref. Cifrada Rota
                                    </span>
                                @else
                                    <span class="bg-slate-100 px-2 py-0.5 rounded-sm border border-slate-200/60 text-slate-800">
                                        {{ str_replace(' (LEGACY)', '', str_replace(' (Legacy)', '', $pago->referencia_bancaria)) }}
                                    </span>
                                @endif
                            </td>
                            
                            <td class="p-4 text-center">
                                @if($pago->estatus == 'Pagado')
                                    <span class="inline-flex items-center text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded-sm">
                                        Liquidado
                                    </span>
                                @elseif($pago->estatus == 'Pendiente')
                                    <span class="inline-flex items-center text-amber-600 font-bold bg-amber-50 px-2 py-0.5 rounded-sm animate-pulse">
                                        Por Validar
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded-sm">
                                        {{ $pago->estatus }}
                                    </span>
                                @endif
                            </td>
                            
                            <td class="p-4 text-center">
                                <a href="{{ route('contador.pagos.revisar', $pago->id) }}" 
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-[#841B44] hover:bg-[#6b1536] text-white font-bold rounded-lg shadow-3xs transition-colors">
                                    <span class="material-icons-round text-xs">pageview</span> Revisar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400 font-medium">
                                <span class="material-icons-round text-2xl block mb-1">payments</span>
                                No hay registros de pago en la fila de espera con los criterios seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginador -->
        @if($pagos->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                {{ $pagos->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</main>
@endsection
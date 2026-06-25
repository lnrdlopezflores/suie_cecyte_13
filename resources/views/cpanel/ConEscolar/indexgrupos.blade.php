@extends('cpanel/plantillaCE')
@section('title', 'Estructura Escolar - Grupos')
@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-6 space-y-6">

    <!-- METRICAS RÁPIDAS DEL CONTROL DE GRUPOS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-3xs flex items-center gap-4">
            <div class="w-10 h-10 bg-indigo-50 text-indigo-700 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round">grid_view</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Total de Grupos</p>
                <p class="text-lg font-black text-slate-800">{{ $grupos->total() }} Secciones</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-3xs flex items-center gap-4">
            <div class="w-10 h-10 bg-amber-50 text-amber-700 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round">wb_sunny</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider font-sans">Ciclo Activo</p>
                <p class="text-lg font-black text-slate-800">2026-A</p>
            </div>
        </div>
    </div>

    <!-- PANEL DE ACCIONES, BÚSQUEDA Y FILTROS -->
    <div class="bg-white p-4 rounded-xl shadow-xs border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
        
        <!-- Formulario Dinámico de Filtros (GET) -->
        <form action="{{ route('grupos.index') }}" method="GET" class="flex flex-wrap items-center gap-3 text-xs w-full md:w-auto">
            
            <!-- Selector de Semestre -->
            <div>
                <select name="semestre" onchange="this.form.submit()" 
                        class="bg-slate-50 border border-slate-300 rounded-xl p-2 font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                    <option value="">Todos los Semestres</option>
                    @foreach(['1', '2', '3', '4', '5', '6'] as $sem)
                        <option value="{{ $sem }}" {{ request('semestre') == $sem ? 'selected' : '' }}>{{ $sem }}° Semestre</option>
                    @endforeach
                </select>
            </div>

            <!-- Selector de Turno -->
            <div>
                <select name="turno" onchange="this.form.submit()" 
                        class="bg-slate-50 border border-slate-300 rounded-xl p-2 font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                    <option value="">Todos los Turnos</option>
                    <option value="Matutino" {{ request('turno') == 'Matutino' ? 'selected' : '' }}>Matutino</option>
                    <option value="Vespertino" {{ request('turno') == 'Vespertino' ? 'selected' : '' }}>Vespertino</option>
                </select>
            </div>

            @if(request('semestre') || request('turno'))
                <a href="{{ route('grupos.index') }}" class="text-[#841B44] hover:underline font-semibold flex items-center gap-0.5">
                    <span class="material-icons-round text-sm">clear</span> Limpiar filtros
                </a>
            @endif
        </form>

        <!-- Botón para Registrar Nuevo Grupo (Opcional a futuro) -->
        <div class="shrink-0 w-full md:w-auto text-right">
            <button class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#841B44] text-white text-xs font-bold rounded-xl shadow-2xs opacity-60 cursor-not-allowed" disabled>
                <span class="material-icons-round text-sm">add_box</span> Nuevo Grupo
            </button>
        </div>
    </div>

    <!-- TABLA DE CONTROL DE GRUPOS -->
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                        <th class="p-4 w-20 text-center">ID</th>
                        <th class="p-4 w-32 text-center">Grado y Grupo</th>
                        <th class="p-4">Especialidad Técnica</th>
                        <th class="p-4 text-center w-36">Turno asignado</th>
                        <th class="p-4 text-center w-36">Ciclo Activo</th>
                        <th class="p-4 text-center w-32">Estatus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs">
                    @forelse($grupos as $grupo)
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            <!-- ID del registro -->
                            <td class="p-4 text-center font-mono text-slate-400">#{{ \Illuminate\Support\Str::padLeft($grupo->id, 2, '0') }}</td>
                            
                            <!-- Semestre y sección (Ej: 6° "A") -->
                            <td class="p-4 text-center">
                                <span class="bg-indigo-50 text-indigo-700 border border-indigo-100 text-xs font-black px-3 py-1 rounded-xl uppercase tracking-wide">
                                    {{ $grupo->semestre }}° "{{ $grupo->grupo }}"
                                </span>
                            </td>
                            
                            <!-- Carrera o especialidad -->
                            <td class="p-4">
                                <div class="font-bold text-slate-900 text-sm">
                                    {{ $grupo->especialidad }}
                                </div>
                            </td>
                            
                            <!-- Turno -->
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border font-medium text-[11px]
                                    {{ $grupo->turno == 'Matutino' ? 'bg-amber-50 text-amber-800 border-amber-200' : 'bg-slate-100 text-slate-700 border-slate-300' }}
                                ">
                                    <span class="material-icons-round text-xs text-slate-400">
                                        {{ $grupo->turno == 'Matutino' ? 'wb_sunny' : 'nights_stay' }}
                                    </span>
                                    {{ $grupo->turno }}
                                </span>
                            </td>

                            <!-- Ciclo Escolar -->
                            <td class="p-4 text-center font-mono font-bold text-slate-600">
                                {{ $grupo->ciclo_escolar }}
                            </td>
                            
                            <!-- Estatus operativo -->
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Abierto
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400 font-medium">
                                <span class="material-icons-round text-2xl block mb-1">grid_view</span>
                                No hay grupos registrados que coincidan con los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginador Relacional de Laravel -->
        @if($grupos->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                {{ $grupos->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</main>
@endsection

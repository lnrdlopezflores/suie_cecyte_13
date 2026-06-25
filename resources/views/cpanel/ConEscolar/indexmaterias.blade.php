@extends('cpanel/plantillaCE')
@section('title', 'Plan de Estudios - Materias')
@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-6 space-y-6">

    <!-- RESUMEN / MÉTRICAS RÁPIDAS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-3xs flex items-center gap-4">
            <div class="w-10 h-10 bg-rose-50 text-[#841B44] rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round">auto_stories</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Materias en Plan</p>
                <p class="text-lg font-black text-slate-800">{{ $materias->total() }} Asignaturas</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-3xs flex items-center gap-4">
            <div class="w-10 h-10 bg-amber-50 text-amber-700 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round">schedule</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Promedio de Horas</p>
                <p class="text-lg font-black text-slate-800">4.2 hrs / semana</p>
            </div>
        </div>
    </div>

    <!-- ACCIONES Y FILTROS -->
    <div class="bg-white p-4 rounded-xl shadow-xs border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
        
        <!-- Buscador Dinámico (GET) -->
        <form action="{{ route('materias.index') }}" method="GET" class="flex flex-wrap items-center gap-3 text-xs w-full md:w-auto">
            <div class="relative w-full sm:w-80">
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-9 pr-4 py-2 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden placeholder:text-slate-400" 
                       placeholder="Buscar por clave o nombre de materia...">
                <span class="material-icons-round text-slate-400 text-sm absolute left-3 top-2.5">search</span>
            </div>

            @if(request('buscar'))
                <a href="{{ route('materias.index') }}" class="text-[#841B44] hover:underline font-semibold flex items-center gap-0.5">
                    <span class="material-icons-round text-sm">clear</span> Limpiar búsqueda
                </a>
            @endif
        </form>

        <!-- Botón para Registrar Nueva Materia (Opcional a futuro) -->
        <div class="shrink-0 w-full md:w-auto text-right">
            <a href="{{ route('materias.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#841B44] text-white text-xs font-bold rounded-xl shadow-2xs hover:opacity-100">
                <span class="material-icons-round text-sm">add_box</span> Nueva Materia
            </a>
        </div>
    </div>

    <!-- TABLA DE CONTROL DE MATERIAS -->
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                        <th class="p-4 w-24 text-center">ID Catálogo</th>
                        <th class="p-4 w-32">Clave Oficial</th>
                        <th class="p-4">Nombre de la Asignatura</th>
                        <th class="p-4 text-center w-48">Carga Horaria Semanal</th>
                        <th class="p-4 text-center w-32">Estatus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs">
                    @forelse($materias as $materia)
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            <!-- ID Auto-incremental -->
                            <td class="p-4 text-center font-mono text-slate-400">#{{ \Illuminate\Support\Str::padLeft($materia->id, 3, '0') }}</td>
                            
                            <!-- Clave Única (RED-6A, etc.) -->
                            <td class="p-4 font-mono font-bold text-[#841B44] tracking-wide">
                                {{ $materia->clave }}
                            </td>
                            
                            <!-- Nombre de la Materia -->
                            <td class="p-4">
                                <div class="font-bold text-slate-900 text-sm">
                                    {{ $materia->nombre }}
                                </div>
                            </td>
                            
                            <!-- Horas Semanales -->
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 font-semibold px-2.5 py-1 rounded-lg border border-slate-200 font-mono">
                                    <span class="material-icons-round text-xs text-slate-400">schedule</span>
                                    {{ $materia->horas_semanales }} hrs
                                </span>
                            </td>
                            
                            <!-- Estatus del mapa curricular -->
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Activa
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400 font-medium">
                                <span class="material-icons-round text-2xl block mb-1">auto_stories</span>
                                No se encontraron asignaturas cargadas en el plan de estudios o no coinciden con la búsqueda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación Estándar de Laravel -->
        @if($materias->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                {{ $materias->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</main>
@endsection

@extends('cpanel/plantillaCE')
@section('title', 'Planeación - Cargas Académicas')
@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-6 space-y-6">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-3xs flex items-center gap-4">
            <div class="w-10 h-10 bg-[#841B44]/10 text-[#841B44] rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round">assignment</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Clases Distribuidas</p>
                <p class="text-lg font-black text-slate-800">{{ $cargas->total() }} Asignaciones</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-xs border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
        
        <form action="{{ route('cargas.index') }}" method="GET" class="flex flex-wrap items-center gap-3 text-xs w-full md:w-auto">
            <div class="relative w-full sm:w-80">
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-9 pr-4 py-2 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden placeholder:text-slate-400" 
                       placeholder="Buscar por docente, materia o aula...">
                <span class="material-icons-round text-slate-400 text-sm absolute left-3 top-2.5">search</span>
            </div>

            @if(request('buscar'))
                <a href="{{ route('cargas.index') }}" class="text-[#841B44] hover:underline font-semibold flex items-center gap-0.5">
                    <span class="material-icons-round text-sm">clear</span> Limpiar filtros
                </a>
            @endif
        </form>

        <div class="shrink-0 w-full md:w-auto text-right">
            <a href="{{ route('cargas.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#841B44] text-white text-xs font-bold rounded-xl shadow-2xs hover:opacity-90 transition-opacity">
                <span class="material-icons-round text-sm">add_box</span> Asignar Carga
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xs border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                        <th class="p-4">Personal Docente</th>
                        <th class="p-4">Asignatura (Clave)</th>
                        <th class="p-4 text-center w-32">Grado y Grupo</th>
                        <th class="p-4">Horario y Aula</th>
                        <th class="p-4 text-center w-28">Estatus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs">
                    @forelse($cargas as $carga)
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            <td class="p-4">
                                <div class="font-bold text-slate-900">{{ $carga->docente_apellido }} {{ $carga->docente_nombre }}</div>
                                <div class="text-[10px] text-slate-400 font-mono mt-0.5">Nómina / ID: {{ $carga->username }}</div>
                            </td>
                            
                            <td class="p-4">
                                <div class="font-semibold text-slate-800 text-sm leading-tight">{{ $carga->materia_nombre }}</div>
                                <div class="text-[11px] font-mono font-bold text-[#841B44] mt-0.5">{{ $carga->clave }}</div>
                            </td>
                            
                            <td class="p-4 text-center">
                                <span class="bg-indigo-50 text-indigo-700 border border-indigo-100 text-xs font-black px-3 py-1 rounded-xl uppercase tracking-wide">
                                    {{ $carga->semestre }}° "{{ $carga->grupo }}"
                                </span>
                            </td>

                            <td class="p-4 space-y-1">
                                <div class="flex items-center text-slate-700 gap-1 font-medium">
                                    <span class="material-icons-round text-slate-400 text-xs">meeting_room</span>
                                    {{ $carga->aula ?? 'Aula por asignar' }}
                                </div>
                                <div class="flex items-center text-slate-500 gap-1 text-[11px] font-mono">
                                    <span class="material-icons-round text-slate-400 text-xs">schedule</span>
                                    {{ $carga->horario ?? 'Horario no definido' }}
                                </div>
                            </td>
                            
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Vigente
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400 font-medium">
                                <span class="material-icons-round text-2xl block mb-1">assignment_late</span>
                                No hay cargas académicas distribuidas o no coinciden con los filtros de búsqueda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($cargas->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                {{ $cargas->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</main>
@endsection

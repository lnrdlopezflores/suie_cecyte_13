@extends('cpanel/plantilladocente')
@section('title', 'dashboard')
@section('content')
        <main class="flex-1 max-w-6xl w-full mx-auto p-4 md:p-6 space-y-6">
    
    <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-200 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <div class="space-y-1">
            <h2 class="text-xl font-bold text-slate-900">
                ¡Bienvenido de vuelta, {{ auth()->user()->docente?->nombre ?? 'Profesor' }}!
            </h2>
            <p class="text-xs text-slate-500">
                Ciclo Escolar Activo: <span class="font-semibold text-indigo-700">2026-A (Febrero - Julio)</span> • Revisa tus asignaturas y grupos cargados.
            </p>
        </div>
        <div class="bg-slate-50 border border-slate-200 px-4 py-2 rounded-xl text-center shrink-0">
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Clases de Hoy</p>
            <p class="text-lg font-black text-slate-800">
                {{ $clasesHoy ?? '0' }} {{ Str::plural('Sesión', $clasesHoy ?? 0) }}
            </p>
        </div>
    </div>

    <div class="flex items-center justify-between">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Mis Cargas Académicas</h3>
        <span class="text-xs text-slate-500 font-medium">
            Total: {{ $materias->count() }} {{ Str::plural('Grupo Asignado', $materias->count() ?? 0) }}
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        @forelse($materias as $materia)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-3xs overflow-hidden flex flex-col justify-between group hover:border-indigo-500/50 transition-colors">
                <div class="p-5 space-y-4">
                    <div class="flex justify-between items-start">
                        <span class="bg-indigo-50 text-indigo-700 border border-indigo-100 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wide">
                            {{ $materia->semestre }}° Semestre
                        </span>
                        
                        @if($materia->alumnos_criticos_count > 0)
                            <span class="inline-flex items-center gap-1 bg-red-50 border border-red-100 text-red-700 text-[10px] font-semibold px-2 py-0.5 rounded-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-pulse"></span> 
                                {{ $materia->alumnos_criticos_count }} Alumno crítico
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-emerald-50 border border-emerald-100 text-emerald-700 text-[10px] font-semibold px-2 py-0.5 rounded-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Grupo Estable
                            </span>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-extrabold text-base text-slate-900 group-hover:text-indigo-900 transition-colors">
                            {{ $materia->nombre }}
                        </h3>
                        <p class="text-xs font-semibold text-slate-500 mt-0.5 flex items-center">
                            <span class="material-icons-round text-sm text-slate-400 mr-1">groups</span> 
                            Grupo {{ $materia->grupo }} • {{ $materia->especialidad }}
                        </p>
                    </div>
                    <div class="text-[11px] text-slate-400 flex flex-wrap gap-x-4 gap-y-1 pt-2 border-t border-slate-100">
                        <span class="flex items-center">
                            <span class="material-icons-round text-xs mr-1 text-slate-400">schedule</span> 
                            {{ $materia->horario }}
                        </span>
                        <span class="flex items-center">
                            <span class="material-icons-round text-xs mr-1 text-slate-400">meeting_room</span> 
                            {{ $materia->aula }}
                        </span>
                    </div>
                </div>
                
                <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex justify-between items-center text-xs">
                    <a href="{{ route('asistencia.tomar', $materia->id) }}" class="font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                        <span class="material-icons-round text-sm">how_to_reg</span> Tomar Asistencia
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 bg-amber-50 border border-amber-200 text-amber-800 p-4 rounded-xl text-center text-sm font-medium">
                No tienes asignaturas asignadas para este ciclo escolar.
            </div>
        @endforelse

    </div>
</main>
@endsection

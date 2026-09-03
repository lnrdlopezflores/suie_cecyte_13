@extends('cpanel/plantillacoordinacion')
@section('title', 'Planeación - Cargas Académicas')

@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-8 space-y-8 text-sm md:text-base transition-colors duration-200">

    <!-- ENCABEZADO Y CONTADORES RÁPIDOS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-custom-primary text-xs font-black uppercase tracking-widest mb-1">
                <span class="material-icons-round text-base">menu_book</span>
                <span>Organización Curricular</span>
            </div>
            <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-slate-100 flex items-center gap-2.5">
                Cargas Académicas por Docente
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm mt-1">
                Distribución semanal de asignaturas, grupos y aulas asignadas exclusivamente al personal docente activo.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <div class="bg-white dark:bg-slate-900 px-5 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-3xs flex items-center gap-3">
                <div class="w-10 h-10 bg-custom-light text-custom-primary rounded-xl flex items-center justify-center shrink-0 border border-custom-primary/30 shadow-2xs">
                    <span class="material-icons-round text-xl">assignment</span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-black tracking-wider block">Asignaciones</span>
                    <span class="text-lg font-black text-slate-900 dark:text-slate-100">{{ $totalAsignaciones ?? 0 }}</span>
                </div>
            </div>

            <div class="bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-900/60 px-5 py-3.5 rounded-2xl shadow-3xs text-center">
                <span class="text-[10px] text-emerald-700 dark:text-emerald-400 font-black uppercase tracking-wider block">Docentes Activos</span>
                <span class="text-lg font-black text-emerald-900 dark:text-emerald-100">{{ $docentesConCarga->count() }}</span>
            </div>
        </div>
    </div>

    <!-- BARRA DE BÚSQUEDA Y ACCIÓN RÁPIDA -->
    <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl shadow-xs border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4 transition-colors">
        <form action="{{ route('cargas.index') }}" method="GET" class="flex flex-wrap items-center gap-3 text-xs w-full md:w-auto">
            <div class="relative w-full sm:w-96">
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-2xl pl-10 pr-4 py-3 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden placeholder:text-slate-400 dark:placeholder:text-slate-500 transition-all" 
                       placeholder="Buscar por materia, clave, aula o ID docente...">
                <span class="material-icons-round text-slate-400 dark:text-slate-500 text-lg absolute left-3.5 top-3">search</span>
            </div>

            @if(request('buscar'))
                <a href="{{ route('coordinador.cargas.index') }}" class="text-custom-primary hover:underline font-extrabold flex items-center gap-1 px-2 py-1">
                    <span class="material-icons-round text-base">clear</span> Limpiar filtros
                </a>
            @endif
        </form>

        <a href="{{ route('cargas.create') }}" 
           class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-5 py-3.5 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold text-xs rounded-2xl shadow-xs hover:shadow-md transition-all cursor-pointer">
            <span class="material-icons-round text-base">add_box</span>
            <span>Asignar Carga Académica</span>
        </a>
    </div>

    <!-- LISTADO AGRUPADO POR CADA DOCENTE ACTIVO -->
    <div class="space-y-6">
        @forelse($docentesConCarga as $docente)
            @php
                $iniciales = strtoupper(substr($docente->docente_nombre ?: 'P', 0, 1) . substr($docente->docente_apellido ?: 'R', 0, 1));
            @endphp

            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden transition-colors">
                
                <!-- Encabezado del Docente -->
                <div class="p-5 md:p-6 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-custom-light text-custom-primary font-black flex items-center justify-center text-sm shrink-0 border border-custom-primary/30 shadow-2xs">
                            {{ $iniciales }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-black text-slate-900 dark:text-slate-100 text-base md:text-lg">
                                    Prof. {{ $docente->nombre_completo }}
                                </h3>
                                <span class="inline-flex items-center text-emerald-700 dark:text-emerald-300 font-extrabold bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-900/60 px-2.5 py-0.5 rounded-full text-[10px] uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>Activo
                                </span>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-slate-400 dark:text-slate-500 font-mono mt-0.5">
                                <span>ID: {{ $docente->username }}</span>
                                @if($docente->correo)
                                    <span>•</span>
                                    <span class="truncate">{{ $docente->correo }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Métricas del Docente -->
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-900/60 text-xs font-bold px-3 py-1.5 rounded-xl">
                            {{ $docente->total_materias }} {{ $docente->total_materias === 1 ? 'materia' : 'materias' }}
                        </span>
                        <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold px-3 py-1.5 rounded-xl text-xs">
                            {{ $docente->total_horas }} hrs/sem
                        </span>
                    </div>
                </div>

                <!-- Tabla de Materias Asignadas -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-slate-800/20 border-b border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-400 text-[10px] font-black uppercase tracking-wider">
                                <th class="p-4 pl-6">Asignatura</th>
                                <th class="p-4">Clave</th>
                                <th class="p-4 text-center">Grado / Grupo</th>
                                <th class="p-4">Especialidad</th>
                                <th class="p-4">Aula y Horario</th>
                                <th class="p-4 text-center pr-6">Horas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                            @foreach($docente->materias as $carga)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="p-4 pl-6 font-bold text-slate-900 dark:text-slate-100 text-xs md:text-sm">
                                        {{ $carga->materia_nombre }}
                                    </td>
                                    
                                    <td class="p-4 font-mono font-black text-custom-primary">
                                        {{ $carga->clave }}
                                    </td>

                                    <td class="p-4 text-center">
                                        <span class="bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-900/60 text-xs font-black px-2.5 py-1 rounded-xl">
                                            {{ $carga->semestre }}° "{{ $carga->grupo }}"
                                        </span>
                                    </td>

                                    <td class="p-4 font-semibold text-slate-600 dark:text-slate-300">
                                        {{ $carga->especialidad }}
                                    </td>

                                    <td class="p-4 space-y-1">
                                        <div class="flex items-center gap-1.5 font-bold text-slate-800 dark:text-slate-200">
                                            <span class="material-icons-round text-slate-400 text-xs">meeting_room</span>
                                            <span>{{ $carga->aula ?? 'Por asignar' }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 font-mono text-[11px] text-slate-500 dark:text-slate-400">
                                            <span class="material-icons-round text-slate-400 text-xs">schedule</span>
                                            <span>{{ $carga->horario ?? 'No definido' }}</span>
                                        </div>
                                    </td>

                                    <td class="p-4 text-center pr-6 font-mono font-bold text-slate-900 dark:text-slate-100">
                                        {{ $carga->horas_semanales }} h
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 p-12 rounded-3xl border border-slate-200 dark:border-slate-800 text-center text-slate-400 dark:text-slate-500 font-medium space-y-3">
                <span class="material-icons-round text-5xl block text-slate-300 dark:text-slate-700">assignment_late</span>
                <p class="text-base">No hay cargas académicas asignadas a docentes activos.</p>
                <p class="text-xs text-slate-400">Verifica que los profesores cuenten con usuario activo y asignaturas registradas.</p>
            </div>
        @endforelse
    </div>

</main>
@endsection

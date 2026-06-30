@extends('cpanel/plantillaestudiante')

@section('title', 'Mis Materias')

@section('grupo_badge')
    @if(isset($grupoInfo))
        {{ $grupoInfo->semestre }}° Semestre — Grupo "{{ $grupoInfo->grupo }}"
    @else
        Sin Grupo Asignado
    @endif
@endsection

@section('content')
<main class="p-4 md:p-6 space-y-6 max-w-7xl w-full mx-auto text-xs">

    <!-- ENCABEZADO DE LA CARGA ACADÉMICA -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-3xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <span class="material-icons-round text-[#841B44]">auto_stories</span> Mi Carga Académica
            </h2>
            <p class="text-slate-500 text-[11px] mt-0.5">Consulta las asignaturas asignadas a tu grupo, horarios, aulas y tu porcentaje de asistencia actual.</p>
        </div>
        
        @if(isset($grupoInfo))
            <div class="shrink-0 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200">
                <span class="text-[10px] text-slate-400 block font-bold uppercase tracking-wider">Especialidad Técnica</span>
                <span class="font-bold text-slate-700">{{ $grupoInfo->especialidad }} (Turno {{ $grupoInfo->turno }})</span>
            </div>
        @endif
    </div>

    <!-- TARJETAS/CONTENEDORES DE MATERIAS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($materias as $materia)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-3xs p-5 flex flex-col justify-between space-y-4 hover:border-slate-300 transition-all">
                
                <!-- Identificación de la Asignatura -->
                <div class="flex justify-between items-start gap-3">
                    <div class="space-y-1">
                        <span class="font-mono font-bold text-[#841B44] bg-rose-50 px-2 py-0.5 rounded-sm uppercase text-[10px] tracking-wider">
                            {{ $materia->clave }}
                        </span>
                        <h3 class="text-sm font-black text-slate-900 leading-tight pt-1">
                            {{ $materia->materia_nombre }}
                        </h3>
                        <p class="text-slate-500 font-medium text-[11px] flex items-center gap-1 pt-0.5">
                            <span class="material-icons-round text-sm text-slate-400">person</span>
                            Prof. {{ $materia->docente_apellido }} {{ $materia->docente_nombre }}
                        </p>
                    </div>
                    
                    <!-- Horas Semanales -->
                    <div class="text-right shrink-0">
                        <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-xl font-bold text-[10px]">
                            {{ $materia->horas_semanales }} hrs/semana
                        </span>
                    </div>
                </div>

                <!-- Logística de Aula y Horarios -->
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/60 grid grid-cols-2 gap-2 text-[11px]">
                    <div class="space-y-0.5">
                        <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block">Aula</span>
                        <div class="flex items-center gap-1 text-slate-700 font-semibold">
                            <span class="material-icons-round text-slate-400 text-xs">meeting_room</span>
                            {{ $materia->aula ?? 'Por asignar' }}
                        </div>
                    </div>
                    <div class="space-y-0.5">
                        <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block">Horario Asignado</span>
                        <div class="flex items-center gap-1 text-slate-600 font-mono text-[10px] leading-tight truncate" title="{{ $materia->horario }}">
                            <span class="material-icons-round text-slate-400 text-xs">schedule</span>
                            {{ $materia->horario ?? 'No definido' }}
                        </div>
                    </div>
                </div>

                <!-- Barra de Seguimiento de Asistencias (Métricas reales en base a tus tablas) -->
                <div class="pt-1 space-y-1.5">
                    <div class="flex justify-between items-center text-[10px] font-bold">
                        <span class="text-slate-400 uppercase tracking-wider">Rendimiento de Asistencias</span>
                        <span class="{{ $materia->porcentaje_asistencia >= 80 ? 'text-emerald-600' : 'text-rose-600' }} font-mono">
                            {{ $materia->porcentaje_asistencia }}% Asistencia
                        </span>
                    </div>
                    
                    <!-- Barra de progreso visual -->
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden border border-slate-200/60">
                        <div class="h-full rounded-full transition-all duration-500 {{ $materia->porcentaje_asistencia >= 80 ? 'bg-emerald-500' : 'bg-rose-500' }}" 
                             style="width: {{ $materia->porcentaje_asistencia }}%">
                        </div>
                    </div>
                    
                    <div class="flex justify-between text-[9px] text-slate-400 font-medium">
                        <span>Faltas acumuladas: {{ $materia->total_faltas }}</span>
                        <span>Mínimo requerido: 80%</span>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-full bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-400 font-medium">
                <span class="material-icons-round text-3xl block mb-2 text-slate-300">auto_stories</span>
                Aún no tienes materias vinculadas. Esto puede deberse a que no tienes un grupo asignado para el ciclo escolar vigente.
            </div>
        @endforelse

    </div>
</main>
@endsection
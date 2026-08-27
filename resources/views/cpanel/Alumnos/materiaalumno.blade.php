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
<main class="p-6 md:p-8 space-y-8 max-w-7xl w-full mx-auto text-sm md:text-base">

    <!-- ENCABEZADO DE LA CARGA ACADÉMICA -->
    <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6 transition-colors duration-200">
        <div class="space-y-1">
            <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-slate-100 flex items-center gap-3">
                <span class="material-icons-round text-2xl md:text-3xl text-[#841B44] dark:text-rose-400">auto_stories</span> 
                Mi Carga Académica
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm">Consulta las asignaturas de tu grupo, horarios, aulas y tu porcentaje de asistencia en tiempo real.</p>
        </div>
        
        @if(isset($grupoInfo))
            <div class="shrink-0 bg-slate-50 dark:bg-slate-800/80 px-5 py-3 rounded-2xl border border-slate-200 dark:border-slate-700/80">
                <span class="text-xs text-slate-400 dark:text-slate-400 block font-extrabold uppercase tracking-wider">Especialidad Técnica</span>
                <span class="font-black text-slate-800 dark:text-slate-100 text-sm md:text-base">{{ $grupoInfo->especialidad }} (Turno {{ $grupoInfo->turno }})</span>
            </div>
        @endif
    </div>

    <!-- TARJETAS DE MATERIAS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
        @forelse($materias as $materia)
            @php
                // Descifrado dinámico y seguro de la identidad del docente
                $docenteNombre = $materia->docente_nombre ?? '';
                $docenteApellido = $materia->docente_apellido ?? '';

                try {
                    if (is_string($docenteNombre) && (str_starts_with($docenteNombre, 'ey') || strlen($docenteNombre) > 50)) {
                        $docenteNombre = decrypt($docenteNombre);
                    }
                    if (is_string($docenteApellido) && (str_starts_with($docenteApellido, 'ey') || strlen($docenteApellido) > 50)) {
                        $docenteApellido = decrypt($docenteApellido);
                    }
                } catch (\Throwable $e) {
                    $docenteNombre = str_replace(' (Plain)', '', $docenteNombre);
                    $docenteApellido = str_replace(' (Plain)', '', $docenteApellido);
                }
            @endphp

            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 md:p-7 flex flex-col justify-between space-y-6 hover:border-slate-300 dark:hover:border-slate-700 hover:shadow-md transition-all duration-200">
                
                <!-- Identificación de la Asignatura -->
                <div class="flex justify-between items-start gap-4">
                    <div class="space-y-1.5">
                        <span class="font-mono font-black text-[#841B44] dark:text-rose-300 bg-rose-50 dark:bg-rose-950/60 border border-rose-100 dark:border-rose-900/50 px-3 py-1 rounded-lg uppercase text-xs tracking-wider inline-block">
                            {{ $materia->clave }}
                        </span>
                        <h3 class="text-base md:text-lg font-black text-slate-900 dark:text-slate-100 leading-snug pt-1">
                            {{ $materia->materia_nombre }}
                        </h3>
                        <p class="text-slate-600 dark:text-slate-300 font-medium text-xs md:text-sm flex items-center gap-1.5 pt-0.5">
                            <span class="material-icons-round text-base text-slate-400 dark:text-slate-500">person</span>
                            Prof. {{ $docenteApellido }} {{ $docenteNombre }}
                        </p>
                    </div>
                    
                    <!-- Horas Semanales -->
                    <div class="text-right shrink-0">
                        <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-3.5 py-1.5 rounded-xl font-bold text-xs">
                            {{ $materia->horas_semanales }} hrs/sem
                        </span>
                    </div>
                </div>

                <!-- Logística de Aula y Horarios -->
                <div class="bg-slate-50 dark:bg-slate-800/60 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 grid grid-cols-2 gap-4 text-xs md:text-sm">
                    <div class="space-y-1">
                        <span class="text-[11px] text-slate-400 dark:text-slate-400 uppercase font-extrabold tracking-wider block">Aula Asignada</span>
                        <div class="flex items-center gap-1.5 text-slate-800 dark:text-slate-200 font-bold">
                            <span class="material-icons-round text-slate-400 text-base">meeting_room</span>
                            {{ $materia->aula ?? 'Por asignar' }}
                        </div>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[11px] text-slate-400 dark:text-slate-400 uppercase font-extrabold tracking-wider block">Horario Semanal</span>
                        <div class="flex items-center gap-1.5 text-slate-700 dark:text-slate-300 font-mono text-xs leading-tight truncate" title="{{ $materia->horario }}">
                            <span class="material-icons-round text-slate-400 text-base">schedule</span>
                            {{ $materia->horario ?? 'No definido' }}
                        </div>
                    </div>
                </div>

                <!-- Barra de Seguimiento de Asistencias -->
                <div class="pt-2 space-y-2.5">
                    <div class="flex justify-between items-center text-xs md:text-sm font-black">
                        <span class="text-slate-500 dark:text-slate-400 uppercase tracking-wider text-[11px]">Porcentaje de Asistencias</span>
                        <span class="{{ $materia->porcentaje_asistencia >= 80 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }} font-mono text-sm md:text-base">
                            {{ $materia->porcentaje_asistencia }}% Asistencia
                        </span>
                    </div>
                    
                    <!-- Barra de progreso visual -->
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-3 rounded-full overflow-hidden border border-slate-200 dark:border-slate-700">
                        <div class="h-full rounded-full transition-all duration-500 {{ $materia->porcentaje_asistencia >= 80 ? 'bg-emerald-500' : 'bg-rose-500' }}" 
                             style="width: {{ $materia->porcentaje_asistencia }}%">
                        </div>
                    </div>
                    
                    <div class="flex justify-between text-xs text-slate-500 dark:text-slate-400 font-medium pt-0.5">
                        <span>Inasistencias acumuladas: <strong class="text-slate-700 dark:text-slate-200">{{ $materia->total_faltas }}</strong></span>
                        <span>Mínimo institucional: <strong>80%</strong></span>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-slate-900 p-12 rounded-3xl border border-slate-200 dark:border-slate-800 text-center text-slate-400 dark:text-slate-500 font-medium space-y-3">
                <span class="material-icons-round text-5xl block text-slate-300 dark:text-slate-700">auto_stories</span>
                <p class="text-base">Aún no tienes asignaturas registradas para este ciclo escolar.</p>
                <p class="text-xs text-slate-400">Esto suele suceder cuando tu matrícula aún no ha sido asignada a un grupo activo.</p>
            </div>
        @endforelse

    </div>
</main>
@endsection
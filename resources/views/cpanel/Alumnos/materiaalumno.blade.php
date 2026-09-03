@extends('cpanel/plantillaestudiante')
@section('title', 'Horario y Asignaturas')

@section('grupo_badge')
    @if(isset($grupoInfo))
        {{ $grupoInfo->semestre }}° Semestre — Grupo "{{ $grupoInfo->grupo }}"
    @else
        Sin Grupo Asignado
    @endif
@endsection

@section('content')
<main class="p-4 md:p-8 space-y-8 max-w-7xl w-full mx-auto text-sm md:text-base transition-colors duration-200">

    <!-- CABECERA PRINCIPAL CON CONTROLES -->
    <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6 transition-colors">
        <div class="space-y-1">
            <div class="flex items-center gap-2 text-custom-primary text-xs font-black uppercase tracking-widest">
                <span class="material-icons-round text-base">calendar_today</span>
                <span>Distribución Semanal de Asignaturas</span>
            </div>
            <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-slate-100 flex items-center gap-2.5">
                Mi Horario y Carga Escolar
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm">
                Consulta tus clases por día de la semana, salones asignados y estado de asistencia acumulada.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @if(isset($grupoInfo))
                <div class="bg-slate-50 dark:bg-slate-800/80 px-4 py-2.5 rounded-2xl border border-slate-200 dark:border-slate-700 text-left">
                    <span class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider block">Especialidad</span>
                    <span class="font-black text-slate-800 dark:text-slate-100 text-xs md:text-sm">{{ $grupoInfo->especialidad }} ({{ $grupoInfo->turno }})</span>
                </div>
            @endif

           
            
        </div>
    </div>

    @php
        // Días hábiles de la semana
        $diasSemana = [
            'Lunes'     => ['tag' => 'LUN', 'bg' => 'bg-indigo-50 dark:bg-indigo-950/40 border-indigo-100 dark:border-indigo-900/50 text-indigo-700 dark:text-indigo-400'],
            'Martes'    => ['tag' => 'MAR', 'bg' => 'bg-blue-50 dark:bg-blue-950/40 border-blue-100 dark:border-blue-900/50 text-blue-700 dark:text-blue-400'],
            'Miércoles' => ['tag' => 'MIÉ', 'bg' => 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-100 dark:border-emerald-900/50 text-emerald-700 dark:text-emerald-400'],
            'Jueves'    => ['tag' => 'JUE', 'bg' => 'bg-amber-50 dark:bg-amber-950/40 border-amber-100 dark:border-amber-900/50 text-amber-700 dark:text-amber-400'],
            'Viernes'   => ['tag' => 'VIE', 'bg' => 'bg-rose-50 dark:bg-rose-950/40 border-rose-100 dark:border-rose-900/50 text-rose-700 dark:text-rose-400'],
        ];

        // Procesamiento y desencriptado previo de materias
        $materiasProcesadas = collect($materias)->map(function($m) {
            $nom = $m->docente_nombre ?? '';
            $pat = $m->docente_apellido ?? '';
            try {
                if (is_string($nom) && (str_starts_with($nom, 'ey') || strlen($nom) > 50)) $nom = decrypt($nom);
                if (is_string($pat) && (str_starts_with($pat, 'ey') || strlen($pat) > 50)) $pat = decrypt($pat);
            } catch (\Throwable $e) {}

            $m->profesor_limpio = trim("$pat $nom") ?: 'Profesor Asignado';
            return $m;
        });
    @endphp

    <!-- VISTA 1: CALENDARIO SEMANAL POR COLUMNAS DE DÍAS -->
    <div id="contenedorCalendario" class="space-y-6 block">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-5 items-start">
            
            @foreach($diasSemana as $nombreDia => $estilo)
                @php
                    // Filtra las materias que tengan asignado este día en su horario o string
                    // Admite campos tipo "Lunes 07:00-09:00" o coincidencia en string
                    $materiasDelDia = $materiasProcesadas->filter(function($m) use ($nombreDia) {
                        $horarioStr = $m->horario ?? '';
                        // Si no tiene horario especificado, se lista con aviso
                        return stripos($horarioStr, $nombreDia) !== false || 
                               stripos($horarioStr, substr($nombreDia, 0, 3)) !== false;
                    });
                @endphp

                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden flex flex-col transition-colors">
                    
                    <!-- Encabezado del Día -->
                    <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between {{ $estilo['bg'] }}">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-current animate-pulse"></span>
                            <h3 class="font-black text-xs md:text-sm uppercase tracking-wider">{{ $nombreDia }}</h3>
                        </div>
                        <span class="text-[10px] font-mono font-black opacity-80 px-2 py-0.5 rounded-lg bg-white/60 dark:bg-slate-900/60">
                            {{ $materiasDelDia->count() }} {{ $materiasDelDia->count() === 1 ? 'clase' : 'clases' }}
                        </span>
                    </div>

                    <!-- Lista de Materias en el Día -->
                    <div class="p-3.5 space-y-3.5 flex-1 min-h-[220px]">
                        @forelse($materiasDelDia as $materiaDia)
                            <div class="p-4 rounded-2xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700/80 space-y-3 hover:border-custom-primary dark:hover:border-custom-primary transition-all group">
                                
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between gap-1">
                                        <span class="font-mono text-[10px] font-black text-custom-primary bg-custom-light px-2 py-0.5 rounded-md uppercase">
                                            {{ $materiaDia->clave }}
                                        </span>
                                        <span class="text-[10px] font-mono text-slate-400 dark:text-slate-500 font-bold">
                                            {{ $materiaDia->horas_semanales }}h/sem
                                        </span>
                                    </div>
                                    <h4 class="font-black text-xs md:text-sm text-slate-900 dark:text-slate-100 leading-snug group-hover:text-custom-primary transition-colors">
                                        {{ $materiaDia->materia_nombre }}
                                    </h4>
                                </div>

                                <!-- Datos Logísticos (Hora y Aula) -->
                                <div class="space-y-1 text-[11px] pt-1 border-t border-slate-200/60 dark:border-slate-700/60 text-slate-600 dark:text-slate-300">
                                    <div class="flex items-center gap-1.5 font-mono text-slate-700 dark:text-slate-200 font-bold">
                                        <span class="material-icons-round text-xs text-custom-primary">schedule</span>
                                        <span class="truncate">{{ $materiaDia->horario ?? 'Horario por confirmar' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 font-semibold text-slate-500 dark:text-slate-400">
                                        <span class="material-icons-round text-xs text-slate-400">meeting_room</span>
                                        <span>Aula: {{ $materiaDia->aula ?? 'Por asignar' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-slate-500 dark:text-slate-400 truncate">
                                        <span class="material-icons-round text-xs text-slate-400">person</span>
                                        <span class="truncate">{{ $materiaDia->profesor_limpio }}</span>
                                    </div>
                                </div>

                                <!-- Barra Mini Asistencia -->
                                <div class="pt-1">
                                    <div class="flex justify-between text-[10px] font-mono font-bold mb-1">
                                        <span class="text-slate-400">Asistencia</span>
                                        <span class="{{ $materiaDia->porcentaje_asistencia >= 80 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                            {{ $materiaDia->porcentaje_asistencia }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-slate-200 dark:bg-slate-700 h-1.5 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full {{ $materiaDia->porcentaje_asistencia >= 80 ? 'bg-emerald-500' : 'bg-rose-500' }}" 
                                             style="width: {{ $materiaDia->porcentaje_asistencia }}%">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @empty
                            <!-- Día sin clases asignadas directamente -->
                            <div class="h-full min-h-[160px] flex flex-col items-center justify-center text-center p-4 border border-dashed border-slate-200 dark:border-slate-800 rounded-2xl text-slate-400 dark:text-slate-500 space-y-1">
                                <span class="material-icons-round text-2xl text-slate-300 dark:text-slate-700">event_busy</span>
                                <span class="text-[11px] font-medium">Sin materias agendadas</span>
                            </div>
                        @endforelse
                    </div>

                </div>
            @endforeach

        </div>
    </div>
</main>

<script>
    function cambiarVista(tipo) {
        const cal = document.getElementById('contenedorCalendario');
        const tar = document.getElementById('contenedorTarjetas');
        const btnCal = document.getElementById('btnVistaCalendario');
        const btnTar = document.getElementById('btnVistaTarjetas');

        if (tipo === 'calendario') {
            cal.classList.remove('hidden');
            tar.classList.add('hidden');

            btnCal.classList.add('bg-white', 'dark:bg-slate-900', 'text-custom-primary', 'shadow-xs', 'font-black');
            btnCal.classList.remove('text-slate-500', 'dark:text-slate-400', 'font-bold');

            btnTar.classList.remove('bg-white', 'dark:bg-slate-900', 'text-custom-primary', 'shadow-xs', 'font-black');
            btnTar.classList.add('text-slate-500', 'dark:text-slate-400', 'font-bold');
        } else {
            tar.classList.remove('hidden');
            cal.classList.add('hidden');

            btnTar.classList.add('bg-white', 'dark:bg-slate-900', 'text-custom-primary', 'shadow-xs', 'font-black');
            btnTar.classList.remove('text-slate-500', 'dark:text-slate-400', 'font-bold');

            btnCal.classList.remove('bg-white', 'dark:bg-slate-900', 'text-custom-primary', 'shadow-xs', 'font-black');
            btnCal.classList.add('text-slate-500', 'dark:text-slate-400', 'font-bold');
        }
    }
</script>
@endsection
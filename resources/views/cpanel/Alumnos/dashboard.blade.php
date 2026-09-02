@extends('cpanel/plantillaestudiante')
@section('title', 'Panel Estudiantil')

{{-- Inyección dinámica del Grado y Grupo en el Topbar --}}
@section('grupo_badge')
    @if(isset($infoAlumno->semestre))
        {{ $infoAlumno->semestre }}° Semestre — Grupo "{{ $infoAlumno->grupo }}"
    @else
        Aspirante / Sin Grupo
    @endif
@endsection

@section('content')
<main class="p-5 md:p-8 space-y-8 max-w-7xl w-full mx-auto text-sm md:text-base transition-colors duration-200">
    
    <!-- BANNER HERO DINÁMICO DE BIENVENIDA -->
    <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-slate-900 to-slate-800 dark:from-slate-950 dark:to-slate-900 p-7 md:p-10 rounded-3xl border border-slate-800/80 shadow-xl text-white flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <!-- Luz ambiental en base al color institucional -->
        <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-custom-primary opacity-20 blur-3xl pointer-events-none"></div>

        <div class="space-y-2 relative z-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/10 dark:bg-white/5 border border-white/15 text-white text-xs font-black rounded-lg uppercase tracking-wider">
                <span class="material-icons-round text-sm text-amber-400">verified</span> Matrícula Activa
            </span>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight leading-tight">
                ¡Hola de nuevo, {{ $infoAlumno->nombre }}!
            </h2>
            <p class="text-slate-300 max-w-2xl text-xs sm:text-sm md:text-base leading-relaxed font-normal">
                Bienvenido al portal estudiantil del <strong class="text-white">SUIE</strong>. Revisa tus materias inscritas, verifica la validación de tus pagos y supervisa el dictamen de tu proyecto de titulación.
            </p>
        </div>

        <div class="shrink-0 bg-white/10 dark:bg-white/5 px-6 py-4 rounded-2xl backdrop-blur-md border border-white/15 text-center md:text-right relative z-10">
            <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-300">Ciclo Escolar Activo</p>
            <p class="text-lg md:text-xl font-black text-white font-mono mt-0.5">2026 - 2027</p>
            <span class="text-[11px] text-slate-400 font-semibold block mt-0.5">CECyTE 13 Tepetitla</span>
        </div>
    </div>

    <!-- TARJETAS DE INDICADORES RÁPIDOS (KPIs) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        
        <!-- Estatus Académico -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-5">
            <div class="w-13 h-13 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 rounded-2xl flex items-center justify-center shrink-0 border border-indigo-100 dark:border-indigo-900/60 shadow-3xs">
                <span class="material-icons-round text-2xl">auto_stories</span>
            </div>
            <div class="space-y-0.5">
                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-extrabold tracking-wider">Estatus Académico</p>
                <p class="text-base font-black text-slate-900 dark:text-slate-100">Carga Regular</p>
                <span class="text-[11px] text-indigo-600 dark:text-indigo-400 font-bold block">Sin adeudos técnicos</span>
            </div>
        </div>

        <!-- Finanzas / Pagos -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-5">
            <div class="w-13 h-13 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 rounded-2xl flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/60 shadow-3xs">
                <span class="material-icons-round text-2xl">payments</span>
            </div>
            <div class="space-y-1">
                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-extrabold tracking-wider">Control de Pagos</p>
                <span class="inline-flex items-center text-emerald-700 dark:text-emerald-300 font-bold bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-xs px-2.5 py-0.5 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Al corriente
                </span>
            </div>
        </div>

        <!-- Semestre Activo -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-5">
            <div class="w-13 h-13 bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 rounded-2xl flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/60 shadow-3xs">
                <span class="material-icons-round text-2xl">grid_view</span>
            </div>
            <div class="space-y-0.5">
                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-extrabold tracking-wider">Semestre Activo</p>
                <p class="text-base font-black text-slate-900 dark:text-slate-100">
                    {{ $infoAlumno->semestre ?? 'N/A' }}° Semestre
                </p>
                <span class="text-[11px] text-slate-400 dark:text-slate-500 font-mono">Grupo "{{ $infoAlumno->grupo ?? 'U' }}"</span>
            </div>
        </div>

        <!-- Estatus Matricular -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-5">
            <div class="w-13 h-13 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-2xl flex items-center justify-center shrink-0 border border-slate-200 dark:border-slate-700 shadow-3xs">
                <span class="material-icons-round text-2xl">badge</span>
            </div>
            <div class="space-y-0.5">
                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-extrabold tracking-wider">Estatus Matricular</p>
                <p class="text-base font-black text-slate-900 dark:text-slate-100">
                    {{ $infoAlumno->estatus_egreso === 'Egresado' ? 'Egresado' : 'Alumno Regular' }}
                </p>
                <span class="text-[11px] text-slate-400 dark:text-slate-500 font-mono">{{ auth()->user()->username }}</span>
            </div>
        </div>

    </div>

    <!-- SECCIÓN DE CONTENIDO: TIMELINE DE TRÁMITES + ACCESOS RÁPIDOS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Monitoreo del Trámite de Egreso (Timeline de 2 Columnas) -->
        <div class="lg:col-span-8 bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-7">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-5 border-b border-slate-100 dark:border-slate-800 gap-2">
                <div class="space-y-1">
                    <h3 class="text-base md:text-lg font-black text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        <span class="material-icons-round text-xl text-custom-primary">assignment_ind</span> 
                        Ruta de Egreso y Titulación
                    </h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm">Requisitos para la habilitación de los módulos de titulación y examen profesional.</p>
                </div>
            </div>

            <div class="space-y-6">
                @php
                    $faseFinalHabilitada = isset($infoAlumno->semestre) && ($infoAlumno->semestre >= 6 || $infoAlumno->estatus_egreso === 'Egresado');
                    $proyectoAprobado = isset($infoAlumno->proyecto_aprobado) && $infoAlumno->proyecto_aprobado == true;
                @endphp

                <!-- Paso 1: Habilitación por Semestre -->
                <div class="flex items-start gap-4">
                    <div class="flex flex-col items-center">
                        <div class="w-9 h-9 rounded-2xl flex items-center justify-center font-black text-sm shrink-0 shadow-3xs {{ $faseFinalHabilitada ? 'bg-emerald-500 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 border border-slate-200 dark:border-slate-700' }}">
                            @if($faseFinalHabilitada)
                                <span class="material-icons-round text-lg">check</span>
                            @else
                                1
                            @endif
                        </div>
                        <div class="w-0.5 h-12 bg-slate-200 dark:bg-slate-800 my-1"></div>
                    </div>

                    <div class="space-y-1 pt-1">
                        <div class="flex items-center gap-2">
                            <h4 class="font-extrabold text-slate-900 dark:text-slate-100 text-sm md:text-base">1. Fase Terminal (6° Semestre)</h4>
                            @if($faseFinalHabilitada)
                                <span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-bold text-[10px] uppercase border border-emerald-200 dark:border-emerald-800">Cumplido</span>
                            @else
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-500 font-bold text-[10px] uppercase">Pendiente</span>
                            @endif
                        </div>
                        <p class="text-xs md:text-sm leading-relaxed {{ $faseFinalHabilitada ? 'text-slate-600 dark:text-slate-300' : 'text-slate-400 dark:text-slate-500' }}">
                            {{ $faseFinalHabilitada ? 'Inscrito en 6° semestre o egresado. El módulo para registro de proyecto se encuentra activo en tu menú lateral.' : 'Requiere estar formalmente inscrito en el 6° Semestre para cargar protocolos de titulación.' }}
                        </p>
                    </div>
                </div>

                <!-- Paso 2: Dictamen del Jurado -->
                <div class="flex items-start gap-4">
                    <div class="flex flex-col items-center">
                        <div class="w-9 h-9 rounded-2xl flex items-center justify-center font-black text-sm shrink-0 shadow-3xs {{ $proyectoAprobado ? 'bg-emerald-500 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 border border-slate-200 dark:border-slate-700' }}">
                            @if($proyectoAprobado)
                                <span class="material-icons-round text-lg">check</span>
                            @else
                                2
                            @endif
                        </div>
                    </div>

                    <div class="space-y-1 pt-1">
                        <div class="flex items-center gap-2">
                            <h4 class="font-extrabold text-slate-900 dark:text-slate-100 text-sm md:text-base">2. Dictamen Favorable del Jurado</h4>
                            @if($proyectoAprobado)
                                <span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-bold text-[10px] uppercase border border-emerald-200 dark:border-emerald-800">Aprobado</span>
                            @else
                                <span class="px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 font-bold text-[10px] uppercase border border-amber-200 dark:border-amber-800">En Evaluación</span>
                            @endif
                        </div>
                        <p class="text-xs md:text-sm leading-relaxed {{ $proyectoAprobado ? 'text-emerald-700 dark:text-emerald-400 font-semibold' : 'text-slate-400 dark:text-slate-500' }}">
                            {{ $proyectoAprobado ? 'Dictamen emitido por el tribunal examinador. Tu trámite oficial de recepción profesional está disponible para entrega de expedientes.' : 'Tu proyecto se encuentra en revisión técnica o pendiente del voto aprobatorio de los 3 sínodos examinadores.' }}
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Columna Lateral: Accesos Directos del Alumno -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white dark:bg-slate-900 p-6 md:p-7 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-5">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="font-black text-slate-900 dark:text-slate-100 text-sm md:text-base flex items-center gap-2">
                        <span class="material-icons-round text-base text-custom-primary">bolt</span>
                        Acciones Rápidas
                    </h3>
                    <p class="text-slate-400 dark:text-slate-500 text-xs">Módulos más consultados de tu cuenta.</p>
                </div>

                <div class="space-y-2.5">
                    <a href="{{ route('indexmaterias.index') }}" 
                       class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 transition-all group">
                        <div class="flex items-center gap-3">
                            <span class="material-icons-round text-xl text-indigo-600 dark:text-indigo-400">auto_stories</span>
                            <span class="font-bold text-xs md:text-sm text-slate-800 dark:text-slate-200">Horario y Materias</span>
                        </div>
                        <span class="material-icons-round text-slate-400 group-hover:translate-x-1 transition-transform text-sm">chevron_right</span>
                    </a>

                    <a href="{{ route('alumnoPagos.index') }}" 
                       class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 transition-all group">
                        <div class="flex items-center gap-3">
                            <span class="material-icons-round text-xl text-emerald-600 dark:text-emerald-400">payments</span>
                            <span class="font-bold text-xs md:text-sm text-slate-800 dark:text-slate-200">Comprobantes de Pago</span>
                        </div>
                        <span class="material-icons-round text-slate-400 group-hover:translate-x-1 transition-transform text-sm">chevron_right</span>
                    </a>

                    @if($faseFinalHabilitada)
                        <a href="{{ route('titulacion.index') }}" 
                           class="flex items-center justify-between p-3.5 rounded-2xl bg-rose-50/60 dark:bg-rose-950/30 hover:bg-rose-100/60 dark:hover:bg-rose-950/50 border border-rose-200/60 dark:border-rose-900/40 transition-all group">
                            <div class="flex items-center gap-3">
                                <span class="material-icons-round text-xl text-custom-primary">history_edu</span>
                                <span class="font-bold text-xs md:text-sm text-slate-900 dark:text-slate-100">Proyecto de Titulación</span>
                            </div>
                            <span class="material-icons-round text-custom-primary group-hover:translate-x-1 transition-transform text-sm">chevron_right</span>
                        </a>
                    @endif
                </div>

                <!-- Aviso de Asistencia Institucional -->
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-700/80 space-y-1">
                    <p class="text-[11px] font-black text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                        <span class="material-icons-round text-xs text-custom-primary">support_agent</span>
                        Orientación y Soporte
                    </p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                        Para aclaraciones con tu matrícula o asignación de grupo, acude al departamento de Control Escolar de tu plantel.
                    </p>
                </div>
            </div>
        </div>

    </div>

</main>
@endsection
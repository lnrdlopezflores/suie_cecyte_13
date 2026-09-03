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
<main class="p-4 md:p-8 space-y-8 max-w-7xl w-full mx-auto text-sm md:text-base transition-colors duration-200">
    
    <!-- BANNER HERO DINÁMICO DE BIENVENIDA -->
    <div class="relative overflow-hidden bg-slate-900 dark:bg-slate-950 p-7 md:p-10 rounded-3xl border border-slate-800/90 shadow-xl text-white flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <!-- Luz ambiental reactiva con la variable primaria -->
        <div class="absolute -right-20 -bottom-20 w-96 h-96 rounded-full bg-custom-primary opacity-20 blur-3xl pointer-events-none"></div>
        <div class="absolute top-0 right-1/3 w-64 h-64 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>

        <div class="space-y-3 relative z-10">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/10 dark:bg-white/5 backdrop-blur-md border border-white/15 text-emerald-400 text-xs font-black rounded-xl uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Alumno Regular
                </span>
                <span class="text-xs text-slate-400 font-mono">ID: {{ auth()->user()->username }}</span>
            </div>
            
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight leading-tight">
                ¡Hola de nuevo, <span class="text-custom-primary">{{ $infoAlumno->nombre ?? 'Estudiante' }}</span>!
            </h2>
            
            <p class="text-slate-300 max-w-2xl text-xs sm:text-sm md:text-base leading-relaxed font-normal">
                Bienvenido al portal estudiantil del plantel <strong class="text-white">CECyTE 13</strong>. Consulta tu carga académica, gestiona tus comprobantes bancarios y monitorea la liberación de tu proceso de titulación.
            </p>
        </div>

        <!-- Indicador Ciclo Escolar -->
        <div class="shrink-0 bg-white/10 dark:bg-white/5 p-5 md:p-6 rounded-3xl backdrop-blur-md border border-white/15 text-center md:text-right relative z-10 space-y-1">
            <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block">Ciclo Escolar</span>
            <p class="text-xl md:text-2xl font-black text-white font-mono tracking-tight">2026 - 2027</p>
            <div class="flex items-center justify-center md:justify-end gap-1.5 text-xs text-slate-300 font-medium pt-1">
                <span class="material-icons-round text-sm text-custom-primary">location_on</span>
                <span>Tepetitla, Tlax.</span>
            </div>
        </div>
    </div>

    <!-- TARJETAS DE INDICADORES RÁPIDOS (KPIs) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        
        <!-- 1. Semestre y Grupo -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs hover:shadow-md transition-all flex items-center gap-4">
            <div class="w-12 h-12 bg-custom-light text-custom-primary rounded-2xl flex items-center justify-center shrink-0 border border-custom-primary/30 shadow-3xs">
                <span class="material-icons-round text-2xl">school</span>
            </div>
            <div class="space-y-0.5">
                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-black tracking-wider">Carga Escolar</p>
                <p class="text-base font-black text-slate-900 dark:text-slate-100">
                    {{ $infoAlumno->semestre ?? 'N/A' }}° Semestre
                </p>
                <span class="text-[11px] text-custom-primary font-bold block">Grupo "{{ $infoAlumno->grupo ?? 'U' }}"</span>
            </div>
        </div>

        <!-- 2. Pagos y Finanzas -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs hover:shadow-md transition-all flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center shrink-0 border border-emerald-200 dark:border-emerald-900/60 shadow-3xs">
                <span class="material-icons-round text-2xl">payments</span>
            </div>
            <div class="space-y-1">
                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-black tracking-wider">Control Financiero</p>
                <span class="inline-flex items-center text-emerald-700 dark:text-emerald-300 font-extrabold bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-[10px] px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> Al corriente
                </span>
            </div>
        </div>

        <!-- 3. Estatus Académico -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs hover:shadow-md transition-all flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center shrink-0 border border-indigo-200 dark:border-indigo-900/60 shadow-3xs">
                <span class="material-icons-round text-2xl">verified</span>
            </div>
            <div class="space-y-0.5">
                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-black tracking-wider">Situación Alumno</p>
                <p class="text-base font-black text-slate-900 dark:text-slate-100">
                    {{ ($infoAlumno->estatus_egreso ?? '') === 'Egresado' ? 'Egresado' : 'Regular' }}
                </p>
                <span class="text-[11px] text-indigo-600 dark:text-indigo-400 font-bold block">Sin adeudos</span>
            </div>
        </div>

        <!-- 4. Seguridad en Dos Pasos (2FA) -->
        @php
            $has2FA = auth()->user()->google2fa_enabled ?? false;
        @endphp
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs hover:shadow-md transition-all flex items-center gap-4">
            <div class="w-12 h-12 {{ $has2FA ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-900/60' : 'bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-900/60' }} rounded-2xl flex items-center justify-center shrink-0 border shadow-3xs">
                <span class="material-icons-round text-2xl">{{ $has2FA ? 'phonelink_lock' : 'phonelink_setup' }}</span>
            </div>
            <div class="space-y-1">
                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-black tracking-wider">Google 2FA</p>
                @if($has2FA)
                    <span class="inline-flex items-center text-emerald-700 dark:text-emerald-300 font-extrabold bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-[10px] px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                        Protegido
                    </span>
                @else
                    <a href="{{ route('2fa.setup') }}" class="inline-flex items-center text-amber-700 dark:text-amber-300 hover:underline font-extrabold text-[11px]">
                        Activar ahora →
                    </a>
                @endif
            </div>
        </div>

    </div>

    <!-- SECCIÓN DE CONTENIDO: TIMELINE DE TITULACIÓN + ACCESOS RÁPIDOS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- RUTA DE EGRESO Y TITULACIÓN (TIMELINE) -->
        <div class="lg:col-span-8 bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs space-y-7 transition-colors">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-5 border-b border-slate-100 dark:border-slate-800 gap-2">
                <div>
                    <div class="flex items-center gap-2 text-custom-primary text-xs font-black uppercase tracking-widest mb-1">
                        <span class="material-icons-round text-base">timeline</span>
                        <span>Progreso Académico</span>
                    </div>
                    <h3 class="text-base md:text-lg font-black text-slate-900 dark:text-slate-100">
                        Ruta de Titulación y Egreso
                    </h3>
                </div>
                <span class="text-xs text-slate-400 dark:text-slate-500 font-medium">Requisitos para recepción profesional</span>
            </div>

            <div class="space-y-8">
                @php
                    $faseFinalHabilitada = isset($infoAlumno->semestre) && ($infoAlumno->semestre >= 6 || ($infoAlumno->estatus_egreso ?? '') === 'Egresado');
                    $proyectoAprobado = isset($infoAlumno->proyecto_aprobado) && $infoAlumno->proyecto_aprobado == true;
                @endphp

                <!-- Paso 1: Fase Terminal (6° Semestre) -->
                <div class="flex items-start gap-4 sm:gap-6">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-black text-xs shrink-0 shadow-2xs {{ $faseFinalHabilitada ? 'bg-emerald-500 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 border border-slate-200 dark:border-slate-700' }}">
                            @if($faseFinalHabilitada)
                                <span class="material-icons-round text-lg">check</span>
                            @else
                                01
                            @endif
                        </div>
                        <div class="w-0.5 h-14 bg-slate-200 dark:bg-slate-800 my-1"></div>
                    </div>

                    <div class="space-y-1.5 pt-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h4 class="font-extrabold text-slate-900 dark:text-slate-100 text-sm md:text-base">
                                Inscripción a Fase Terminal (6° Semestre)
                            </h4>
                            @if($faseFinalHabilitada)
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-black text-[10px] uppercase border border-emerald-200 dark:border-emerald-800">Habilitado</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 font-black text-[10px] uppercase">Bloqueado</span>
                            @endif
                        </div>
                        <p class="text-xs md:text-sm leading-relaxed {{ $faseFinalHabilitada ? 'text-slate-600 dark:text-slate-300' : 'text-slate-400 dark:text-slate-500' }}">
                            {{ $faseFinalHabilitada ? 'Cumples con el semestre mínimo requerido. El módulo de Proyectos de Titulación está activo en tu menú lateral.' : 'Requiere encontrarse formalmente inscrito en el 6° semestre para dar de alta el protocolo técnico.' }}
                        </p>
                    </div>
                </div>

                <!-- Paso 2: Dictamen del Tribunal Examinador -->
                <div class="flex items-start gap-4 sm:gap-6">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-black text-xs shrink-0 shadow-2xs {{ $proyectoAprobado ? 'bg-emerald-500 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 border border-slate-200 dark:border-slate-700' }}">
                            @if($proyectoAprobado)
                                <span class="material-icons-round text-lg">check</span>
                            @else
                                02
                            @endif
                        </div>
                    </div>

                    <div class="space-y-1.5 pt-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h4 class="font-extrabold text-slate-900 dark:text-slate-100 text-sm md:text-base">
                                Liberación por Jurado Revisor
                            </h4>
                            @if($proyectoAprobado)
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-black text-[10px] uppercase border border-emerald-200 dark:border-emerald-800">Aprobado</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 font-black text-[10px] uppercase border border-amber-200 dark:border-amber-800">En Revisión</span>
                            @endif
                        </div>
                        <p class="text-xs md:text-sm leading-relaxed {{ $proyectoAprobado ? 'text-emerald-700 dark:text-emerald-400 font-semibold' : 'text-slate-400 dark:text-slate-500' }}">
                            {{ $proyectoAprobado ? 'Dictamen favorable registrado por la mayoría de los sinodales asignados. Puedes continuar con la carga de documentos de titulación.' : 'Tu reporte final se encuentra en fase de evaluación colegiada por los 3 docentes asignados a tu carrera.' }}
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- ACCESOS RÁPIDOS Y SEGURIDAD -->
        <div class="lg:col-span-4 space-y-6">
            
            <div class="bg-white dark:bg-slate-900 p-6 md:p-7 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs space-y-5 transition-colors">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="font-black text-slate-900 dark:text-slate-100 text-sm md:text-base flex items-center gap-2">
                        <span class="material-icons-round text-base text-custom-primary">touch_app</span>
                        Acciones Rápidas
                    </h3>
                    <p class="text-slate-400 dark:text-slate-500 text-xs">Atajos a tus trámites principales.</p>
                </div>

                <div class="space-y-3">
                    <!-- Horario y Asignaturas -->
                    <a href="{{ route('indexmaterias.index') }}" 
                       class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                <span class="material-icons-round text-lg">view_week</span>
                            </div>
                            <span class="font-bold text-xs md:text-sm text-slate-800 dark:text-slate-200">Horario Semanal</span>
                        </div>
                        <span class="material-icons-round text-slate-400 group-hover:translate-x-1 transition-transform text-base">chevron_right</span>
                    </a>

                    <!-- Subir Comprobante de Pago -->
                    <a href="{{ route('alumnoPagos.index') }}" 
                       class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                <span class="material-icons-round text-lg">receipt_long</span>
                            </div>
                            <span class="font-bold text-xs md:text-sm text-slate-800 dark:text-slate-200">Reportar Depósito</span>
                        </div>
                        <span class="material-icons-round text-slate-400 group-hover:translate-x-1 transition-transform text-base">chevron_right</span>
                    </a>

                    <!-- Configurar 2FA -->
                    <a href="{{ route('2fa.setup') }}" 
                       class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-custom-light text-custom-primary flex items-center justify-center">
                                <span class="material-icons-round text-lg">security</span>
                            </div>
                            <span class="font-bold text-xs md:text-sm text-slate-800 dark:text-slate-200">Google Authenticator</span>
                        </div>
                        <span class="material-icons-round text-custom-primary group-hover:translate-x-1 transition-transform text-base">chevron_right</span>
                    </a>
                </div>

                <!-- Soporte Institucional -->
                <div class="p-4 rounded-2xl bg-slate-50/70 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-700/80 space-y-1.5">
                    <p class="text-xs font-black text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                        <span class="material-icons-round text-sm text-custom-primary">contact_support</span>
                        ¿Dudas o Aclaraciones?
                    </p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                        Acude a ventanilla de Control Escolar en horario hábil para validación de cargas o cambio de turno.
                    </p>
                </div>
            </div>

        </div>

    </div>

</main>
@endsection
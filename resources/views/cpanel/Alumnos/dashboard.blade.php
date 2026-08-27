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
<main class="p-6 md:p-8 space-y-8 max-w-7xl w-full mx-auto text-sm md:text-base">
    
    <!-- BANNER HERO DE BIENVENIDA -->
    <div class="bg-gradient-to-r from-slate-900 to-[#841B44] p-8 md:p-10 rounded-3xl border border-slate-800 shadow-xl text-white flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-2">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight">
                ¡Hola, {{ $infoAlumno->nombre }}!
            </h2>
            <p class="text-slate-200 max-w-2xl text-sm md:text-base leading-relaxed font-normal">
                Bienvenido al Sistema Unificado de Integración Educativa (SUIE). Desde aquí puedes consultar tus asignaturas, dar seguimiento a tus pagos escolares y gestionar tu protocolo de titulación.
            </p>
        </div>
        <div class="shrink-0 bg-white/10 dark:bg-white/5 px-6 py-4 rounded-2xl backdrop-blur-md border border-white/20 text-center sm:text-right">
            <p class="text-xs font-bold uppercase tracking-widest text-rose-200">Ciclo Escolar Activo</p>
            <p class="text-lg md:text-xl font-black text-white font-mono mt-0.5">2026 - 2027</p>
        </div>
    </div>

    <!-- TARJETAS DE INDICADORES RÁPIDOS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Estatus Académico -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-all flex items-center gap-5">
            <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-400 rounded-2xl flex items-center justify-center shrink-0 border border-indigo-100 dark:border-indigo-900/60 shadow-xs">
                <span class="material-icons-round text-3xl">auto_stories</span>
            </div>
            <div class="space-y-0.5">
                <p class="text-xs text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">Estatus Académico</p>
                <p class="text-base md:text-lg font-black text-slate-800 dark:text-slate-100">Carga Regular</p>
            </div>
        </div>

        <!-- Finanzas / Pagos -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-all flex items-center gap-5">
            <div class="w-14 h-14 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 rounded-2xl flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/60 shadow-xs">
                <span class="material-icons-round text-3xl">payments</span>
            </div>
            <div class="space-y-1">
                <p class="text-xs text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">Finanzas / Pagos</p>
                <span class="inline-flex items-center text-emerald-700 dark:text-emerald-300 font-bold bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-900 text-xs px-3 py-1 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> Al corriente
                </span>
            </div>
        </div>

        <!-- Semestre Cursando -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-all flex items-center gap-5">
            <div class="w-14 h-14 bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 rounded-2xl flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/60 shadow-xs">
                <span class="material-icons-round text-3xl">grid_view</span>
            </div>
            <div class="space-y-0.5">
                <p class="text-xs text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">Semestre Activo</p>
                <p class="text-base md:text-lg font-black text-slate-800 dark:text-slate-100">
                    {{ $infoAlumno->semestre ?? 'N/A' }}° Semestre
                </p>
            </div>
        </div>

        <!-- Estatus Matricular -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-all flex items-center gap-5">
            <div class="w-14 h-14 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-2xl flex items-center justify-center shrink-0 border border-slate-200 dark:border-slate-700 shadow-xs">
                <span class="material-icons-round text-3xl">gavel</span>
            </div>
            <div class="space-y-0.5">
                <p class="text-xs text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">Estatus Matricular</p>
                <p class="text-base md:text-lg font-black text-slate-800 dark:text-slate-100">
                    {{ $infoAlumno->estatus_egreso === 'Egresado' ? 'Egresado' : 'Alumno Regular' }}
                </p>
            </div>
        </div>

    </div>

    <!-- SECCIÓN INFERIOR: MONITOREO Y AVISOS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Monitoreo del Trámite de Egreso -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 p-7 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-4">
                    <h3 class="text-lg md:text-xl font-black text-slate-900 dark:text-slate-100 flex items-center gap-2.5">
                        <span class="material-icons-round text-2xl text-[#841B44] dark:text-rose-400">assignment_ind</span> 
                        Monitoreo del Trámite de Egreso
                    </h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm mt-1">Requisitos para la habilitación de los módulos terminales y recepción profesional.</p>
                </div>
                
                <div class="space-y-6">
                    
                    <!-- Paso 1 -->
                    <div class="flex items-start gap-4">
                        @if(isset($infoAlumno->semestre) && ($infoAlumno->semestre >= 6 || $infoAlumno->estatus_egreso == 'Egresado'))
                            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-sm shrink-0 font-black shadow-xs">✓</div>
                            <div class="space-y-1">
                                <h4 class="font-extrabold text-slate-900 dark:text-slate-100 text-sm md:text-base">1. Habilitación de Fase Final</h4>
                                <p class="text-slate-600 dark:text-slate-300 text-xs md:text-sm leading-relaxed">Cursando o egresado de sexto semestre. Módulo de Proyectos de Titulación habilitado.</p>
                            </div>
                        @else
                            <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center text-sm shrink-0 font-bold">1</div>
                            <div class="space-y-1 opacity-60">
                                <h4 class="font-extrabold text-slate-600 dark:text-slate-400 text-sm md:text-base">1. Habilitación de Fase Final</h4>
                                <p class="text-slate-500 dark:text-slate-500 text-xs md:text-sm leading-relaxed">Requiere estar inscrito en el 6° Semestre del plan de estudios.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Paso 2 -->
                    <div class="flex items-start gap-4">
                        @if(isset($infoAlumno->proyecto_aprobado) && $infoAlumno->proyecto_aprobado == true)
                            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-sm shrink-0 font-black shadow-xs">✓</div>
                            <div class="space-y-1">
                                <h4 class="font-extrabold text-slate-900 dark:text-slate-100 text-sm md:text-base">2. Dictamen del Proyecto de Titulación</h4>
                                <p class="text-emerald-700 dark:text-emerald-400 font-bold text-xs md:text-sm leading-relaxed">Aprobado por el Jurado — Tu trámite de titulación oficial se encuentra disponible para la carga de documentos.</p>
                            </div>
                        @else
                            <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center text-sm shrink-0 font-bold">2</div>
                            <div class="space-y-1 opacity-60">
                                <h4 class="font-extrabold text-slate-600 dark:text-slate-400 text-sm md:text-base">2. Dictamen del Proyecto de Titulación</h4>
                                <p class="text-slate-500 dark:text-slate-500 text-xs md:text-sm leading-relaxed">En proceso de revisión técnica o pendiente de evaluación por los 3 jurados examinadores.</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>
@endsection
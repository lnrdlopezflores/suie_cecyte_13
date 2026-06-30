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
<main class="p-4 md:p-6 space-y-6 max-w-7xl w-full mx-auto text-xs">
    
    <div class="bg-gradient-to-r from-slate-900 to-[#841B44] p-6 rounded-2xl border border-slate-800 shadow-md text-white flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="space-y-1">
            <h2 class="text-lg md:text-xl font-black">¡Hola, {{ $infoAlumno->nombre }}!</h2>
            <p class="text-slate-300 max-w-xl text-[11px] leading-relaxed">
                Bienvenido al Sistema Unificado de Integración Educativa (SUIE). Desde aquí puedes dar seguimiento a tus materias, consultar tus órdenes de pago y tramitar tu titulación al cumplir los créditos obligatorios.
            </p>
        </div>
        <div class="shrink-0 bg-white/10 px-4 py-2 rounded-xl backdrop-blur-xs border border-white/10">
            <p class="text-[10px] text-slate-300 font-bold uppercase tracking-wider">Ciclo Escolar Activo</p>
            <p class="text-sm font-black text-white font-mono">2026-2027</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-3xs flex items-center gap-4">
            <div class="w-10 h-10 bg-indigo-50 text-indigo-700 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round">auto_stories</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Estatus Académico</p>
                <p class="text-sm font-black text-slate-800">Carga Regular</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-3xs flex items-center gap-4">
            <div class="w-10 h-10 bg-emerald-50 text-emerald-700 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round">payments</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Finanzas / Colegiatura</p>
                <span class="inline-flex items-center text-emerald-700 font-bold bg-emerald-50 text-[10px] px-2 py-0.5 rounded-sm mt-0.5">
                    Al corriente
                </span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-3xs flex items-center gap-4">
            <div class="w-10 h-10 bg-amber-50 text-amber-700 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round">grid_view</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Semestre Cursando</p>
                <p class="text-sm font-black text-slate-800">
                    {{ $infoAlumno->semestre ?? 'N/A' }}° Grado
                </p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-3xs flex items-center gap-4">
            <div class="w-10 h-10 bg-slate-100 text-slate-700 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round">gavel</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Estatus Matricular</p>
                <p class="text-sm font-black text-slate-800">
                    {{ $infoAlumno->estatus_egreso === 'Egresado' ? 'Egresado' : 'Alumno Regular' }}
                </p>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-3xs space-y-4">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-icons-round text-[#841B44]">assignment_ind</span> Monitoreo del Trámite de Egreso
                </h3>
                
                <div class="space-y-4 pt-2">
                    
                    <div class="flex items-start gap-3">
                        @if(isset($infoAlumno->semestre) && ($infoAlumno->semestre == '6' || $infoAlumno->estatus_egreso == 'Egresado'))
                            <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs shrink-0 font-bold">✓</div>
                            <div class="space-y-0.5">
                                <h4 class="font-bold text-slate-800">Habilitación de Fase Final</h4>
                                <p class="text-slate-500 text-[11px]">Cursando o egresado de sexto semestre. Módulo de proyectos liberado.</p>
                            </div>
                        @else
                            <div class="w-6 h-6 rounded-full bg-slate-200 text-slate-400 flex items-center justify-center text-xs shrink-0 font-bold">1</div>
                            <div class="space-y-0.5 opacity-60">
                                <h4 class="font-bold text-slate-500">Habilitación de Fase Final</h4>
                                <p class="text-slate-400 text-[11px]">Requiere estar inscrito en el 6° Semestre del plan de estudios.</p>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-start gap-3">
                        @if(isset($infoAlumno->proyecto_aprobado) && $infoAlumno->proyecto_aprobado == true)
                            <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs shrink-0 font-bold">✓</div>
                            <div class="space-y-0.5">
                                <h4 class="font-bold text-slate-800">Dictamen de Proyecto de Residencia</h4>
                                <p class="text-emerald-700 font-semibold text-[11px]">Aprobado — Tu proceso de titulación se encuentra activo para entrega de papeles.</p>
                            </div>
                        @else
                            <div class="w-6 h-6 rounded-full bg-slate-200 text-slate-400 flex items-center justify-center text-xs shrink-0 font-bold">2</div>
                            <div class="space-y-0.5 opacity-60">
                                <h4 class="font-bold text-slate-500">Dictamen de Proyecto de Residencia</h4>
                                <p class="text-slate-400 text-[11px]">Pendiente de revisión o aún no has subido tu anteproyecto de titulación.</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-3xs space-y-4">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-icons-round text-amber-600">campaign</span> Avisos del Plantel
                </h3>
                
                <div class="divide-y divide-slate-100 text-[11px] space-y-3">
                    <div class="pt-1 space-y-1">
                        <span class="text-[9px] font-bold text-[#841B44] bg-rose-50 px-1.5 py-0.5 rounded-sm uppercase tracking-wide">Control Escolar</span>
                        <p class="font-bold text-slate-800">Evaluaciones Parciales</p>
                        <p class="text-slate-500 leading-normal">Recuerda revisar tus calificaciones ordinarias con tus docentes antes del cierre del sistema.</p>
                    </div>
                    <div class="pt-3 space-y-1">
                        <span class="text-[9px] font-bold text-indigo-700 bg-indigo-50 px-1.5 py-0.5 rounded-sm uppercase tracking-wide">Vinculación</span>
                        <p class="font-bold text-slate-800">Revisión de Expedientes</p>
                        <p class="text-slate-500 leading-normal">Los alumnos de 6° semestre que tengan aprobada la residencia deben presentarse en la Oficina de Registro.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection
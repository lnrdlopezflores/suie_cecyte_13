@extends('cpanel/plantillaestudiante')
@section('title', 'Trámite de Titulación')

@section('content')
<main class="p-4 md:p-8 space-y-8 max-w-7xl w-full mx-auto text-xs md:text-sm transition-colors duration-200">

    {{-- ALERTAS DE NOTIFICACIÓN CON ANIMACIÓN --}}
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 p-4 rounded-2xl font-bold flex items-center justify-between shadow-xs transition-all animate-fade-in">
            <div class="flex items-center gap-3">
                <span class="material-icons-round text-xl text-emerald-600 dark:text-emerald-400">check_circle</span>
                <p>{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-100 cursor-pointer p-1 rounded-lg hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition-colors">
                <span class="material-icons-round text-base">close</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 p-4 rounded-2xl space-y-2 shadow-xs animate-fade-in">
            <div class="flex items-center gap-2 font-black text-sm">
                <span class="material-icons-round text-lg text-rose-600 dark:text-rose-400">error</span>
                <span>Atención: Se encontraron observaciones</span>
            </div>
            <ul class="list-disc pl-7 space-y-1 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- BANNER DE PROYECTO APROBADO DEFINITIVAMENTE --}}
    @if(isset($proyecto) && $proyecto->estatus == 'Aprobado')
        <div class="bg-gradient-to-r from-emerald-500 via-teal-600 to-emerald-700 text-white p-6 md:p-8 rounded-3xl shadow-xl space-y-3 relative overflow-hidden border border-emerald-400/30 animate-fade-in">
            <div class="absolute -right-6 -bottom-6 text-white/10 select-none pointer-events-none">
                <span class="material-icons-round text-9xl">task_alt</span>
            </div>
            
            <div class="flex items-center gap-3 relative z-10">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shrink-0 border border-white/30 shadow-inner">
                    <span class="material-icons-round text-3xl text-white">verified</span>
                </div>
                <div>
                    <span class="px-2.5 py-0.5 bg-white/20 text-white text-[10px] font-black rounded-md uppercase tracking-widest border border-white/30"> Dictamen Final </span>
                    <h3 class="text-lg md:text-2xl font-black tracking-tight mt-0.5">¡Felicidades! Tu Proyecto ha sido Aprobado</h3>
                </div>
            </div>

            <p class="text-xs md:text-sm text-emerald-50 leading-relaxed max-w-3xl font-medium relative z-10">
                El protocolo y repositorio de tu proyecto de titulación han sido validados satisfactoriamente por el sínodo examinador. <strong>Ya no es necesario realizar más cambios ni modificaciones.</strong> Puedes dar seguimiento a la expedición de tu acta final en Servicios Escolares.
            </p>
        </div>
    @endif

    {{-- ENCABEZADO PRINCIPAL --}}
    <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-6 transition-colors duration-200">
        <div class="space-y-1">
            <div class="flex items-center gap-2 text-slate-400 dark:text-slate-500 font-bold text-[11px] uppercase tracking-widest">
                <span>Gestión Académica</span>
                <span>•</span>
                <span class="text-[#841B44] dark:text-rose-400">Titulación Profesional</span>
            </div>
            <h2 class="text-lg md:text-2xl font-black text-slate-900 dark:text-white flex items-center gap-3 tracking-tight">
                <div class="w-10 h-10 rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-[#841B44] dark:text-rose-400 flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-900/40 shadow-2xs">
                    <span class="material-icons-round text-2xl">history_edu</span>
                </div>
                <span>Registro de Proyecto de Titulación</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm font-medium">Configura el protocolo de tu proyecto, integra a tus colaboradores y asigna a tu docente asesor.</p>
        </div>
        
        <div class="shrink-0 bg-gradient-to-r from-rose-50 to-pink-50 dark:from-rose-950/50 dark:to-slate-900 border border-rose-200/80 dark:border-rose-900/50 text-[#841B44] dark:text-rose-300 px-5 py-3 rounded-2xl font-black text-xs text-center shadow-2xs">
            <span class="text-[10px] text-slate-400 dark:text-slate-500 block uppercase tracking-wider font-bold">Fase Actual</span>
            {{ isset($proyecto) && $proyecto->estatus == 'Aprobado' ? 'Proyecto Concluido / Aprobado' : 'Etapa 1: Protocolo de Titulación' }}
        </div>
    </div>

    @if(!$proyecto)
        {{-- PASO 1: FORMULARIO DE CREACIÓN DE PROYECTO --}}
        <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xs space-y-6 transition-colors duration-200">
            <div class="border-b border-slate-100 dark:border-slate-800 pb-4">
                <h3 class="text-base md:text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-[#841B44] text-white flex items-center justify-center text-xs font-black">1</span>
                    Registrar Protocolo del Proyecto
                </h3>
                <p class="text-slate-500 dark:text-slate-400 text-xs mt-0.5">Ingresa la denominación oficial y detalles aprobados para tu memoria o proyecto.</p>
            </div>

            <form action="{{ route('titulacion.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label class="block font-bold text-slate-700 dark:text-slate-300 text-xs md:text-sm">
                        Nombre Completo del Proyecto <span class="text-rose-600 dark:text-rose-400">*</span>
                    </label>
                    <input type="text" name="titulo" required value="{{ old('titulo') }}"
                           class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-bold text-slate-900 dark:text-slate-100 text-xs md:text-sm focus:ring-2 focus:ring-[#841B44] dark:focus:ring-rose-500 focus:outline-hidden transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500"
                           placeholder="Ej: Implementación del Sistema Unificado de Integración Educativa (SUIE)...">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block font-bold text-slate-700 dark:text-slate-300 text-xs md:text-sm">
                            Modalidad de Titulación <span class="text-rose-600 dark:text-rose-400">*</span>
                        </label>
                        <select name="modalidad" required class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-bold text-slate-800 dark:text-slate-100 text-xs md:text-sm focus:ring-2 focus:ring-[#841B44] dark:focus:ring-rose-500 focus:outline-hidden transition-all">
                            <option value="Proyecto de Titulación">Proyecto de Titulación</option>
                            <option value="Memoria de Experiencia Profesional">Memoria de Experiencia Profesional</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block font-bold text-slate-700 dark:text-slate-300 text-xs md:text-sm">Resumen o Alcance (Opcional)</label>
                        <input type="text" name="resumen" value="{{ old('resumen') }}"
                               class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-medium text-slate-800 dark:text-slate-100 text-xs md:text-sm focus:ring-2 focus:ring-[#841B44] dark:focus:ring-rose-500 focus:outline-hidden transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500"
                               placeholder="Breve explicación del objetivo general del proyecto...">
                    </div>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-[#841B44] hover:bg-[#681535] dark:bg-rose-700 dark:hover:bg-rose-800 text-white font-extrabold text-xs md:text-sm rounded-2xl shadow-md active:scale-98 transition-all cursor-pointer flex items-center justify-center gap-2">
                        <span class="material-icons-round text-lg">save</span>
                        <span>Registrar Proyecto</span>
                    </button>
                </div>
            </form>
        </div>
    @else
        {{-- PASO 2: GESTIÓN DEL PROYECTO CREADO --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- DETALLES PRINCIPALES Y EQUIPO --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xs space-y-6 transition-colors duration-200 relative overflow-hidden">
                    
                    {{-- PALOMITA DE FONDO SI ESTÁ APROBADO --}}
                    @if($proyecto->estatus == 'Aprobado')
                        <div class="absolute top-4 right-4 text-emerald-500/10 dark:text-emerald-400/10 pointer-events-none select-none">
                            <span class="material-icons-round text-9xl">check_circle</span>
                        </div>
                    @endif

                    {{-- CABECERA Y BADGE DE ESTATUS --}}
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 border-b border-slate-100 dark:border-slate-800 pb-6 relative z-10">
                        <div class="space-y-2">
                            <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-900/60 text-indigo-700 dark:text-indigo-300 text-[10px] font-black rounded-lg uppercase tracking-wider inline-block">
                                {{ $proyecto->modalidad }}
                            </span>
                            <h3 class="text-base md:text-xl font-black text-slate-900 dark:text-white tracking-tight leading-snug flex items-center gap-2">
                                <span>{{ $proyecto->titulo }}</span>
                                @if($proyecto->estatus == 'Aprobado')
                                    <span class="material-icons-round text-emerald-500 text-xl" title="Proyecto Aprobado Definitivamente">check_circle</span>
                                @endif
                            </h3>
                        </div>

                        {{-- BADGE DINÁMICO DE ESTATUS --}}
                        <div class="shrink-0">
                            @php
                                $statusClasses = [
                                    'Aprobado' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800 shadow-xs',
                                    'Pendiente' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                    'En_Revision' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                    'En Revision' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                    'Liberado_Exposicion' => 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border-indigo-200 dark:border-indigo-800',
                                    'Rechazado' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800',
                                ];
                                $currentStatusClass = $statusClasses[$proyecto->estatus] ?? 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700';
                            @endphp

                            <span class="px-4 py-1.5 text-xs font-black rounded-full uppercase border flex items-center gap-1.5 {{ $currentStatusClass }}">
                                @if($proyecto->estatus == 'Aprobado')
                                    <span class="material-icons-round text-base text-emerald-600 dark:text-emerald-400">check_circle</span> Proyecto Aprobado
                                @elseif($proyecto->estatus == 'Liberado_Exposicion')
                                    <span class="material-icons-round text-sm">record_voice_over</span> Liberado para Exposición
                                @else
                                    <span class="material-icons-round text-sm">info</span> Estatus: {{ str_replace('_', ' ', $proyecto->estatus) }}
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- BANNER DE ESTADO LIBERADO --}}
                    @if($proyecto->estatus == 'Liberado_Exposicion')
                        <div class="p-5 bg-indigo-50/80 dark:bg-indigo-950/40 border border-indigo-200/80 dark:border-indigo-900/60 rounded-2xl text-indigo-950 dark:text-indigo-200 space-y-1.5">
                            <div class="flex items-center gap-2 font-black text-xs md:text-sm">
                                <span class="material-icons-round text-base text-indigo-600 dark:text-indigo-400">how_to_vote</span>
                                <span>Protocolo Liberado para Defensa</span>
                            </div>
                            <p class="text-xs text-indigo-800 dark:text-indigo-300 leading-relaxed font-medium">
                                Tu docente asesor ha autorizado este proyecto. Se encuentra en dictaminación por parte del sínodo examinador (se requiere la aprobación de al menos 2 de 3 jurados para iniciar el trámite oficial).
                            </p>
                        </div>
                    @endif

                    @if($proyecto->resumen)
                        <div class="text-slate-600 dark:text-slate-300 leading-relaxed bg-slate-50 dark:bg-slate-800/60 p-4 md:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 text-xs md:text-sm">
                            <strong class="text-slate-800 dark:text-slate-200 block mb-1 font-bold">Resumen del Protocolo:</strong>
                            <p class="font-normal">{{ $proyecto->resumen }}</p>
                        </div>
                    @endif

                    {{-- DOCENTE ASESOR --}}
                    <div class="bg-slate-50 dark:bg-slate-800/70 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 bg-rose-50 dark:bg-rose-950/60 text-[#841B44] dark:text-rose-400 rounded-2xl flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-900/40 shadow-2xs">
                                <span class="material-icons-round text-2xl">supervisor_account</span>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 dark:text-slate-400 font-extrabold uppercase tracking-wider">Docente Asesor Asignado</p>
                                @if(isset($asesor) && $asesor)
                                    @php
                                        $nomA = $asesor->nombre;
                                        $patA = $asesor->apellido_paterno;
                                        try {
                                            if(is_string($nomA) && (str_starts_with($nomA, 'ey') || strlen($nomA) > 50)) $nomA = decrypt($nomA);
                                            if(is_string($patA) && (str_starts_with($patA, 'ey') || strlen($patA) > 50)) $patA = decrypt($patA);
                                        } catch(\Throwable $e) {}
                                    @endphp
                                    <p class="font-black text-slate-900 dark:text-white text-xs md:text-sm mt-0.5">Prof. {{ $patA }} {{ $nomA }}</p>
                                @else
                                    <p class="font-bold text-amber-600 dark:text-amber-400 text-xs italic mt-0.5 flex items-center gap-1">
                                        <span class="material-icons-round text-sm">warning</span> Aún no se ha asignado un docente asesor
                                    </p>
                                @endif
                            </div>
                        </div>

                        @if(!in_array($proyecto->estatus, ['Liberado_Exposicion', 'Aprobado']))
                            <button onclick="document.getElementById('modalAsesor').classList.remove('hidden')" 
                                    class="px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-600 font-bold rounded-xl transition-all cursor-pointer shrink-0 text-xs flex items-center justify-center gap-1.5 shadow-2xs active:scale-98">
                                <span class="material-icons-round text-base text-[#841B44] dark:text-rose-400">person_add_alt</span>
                                <span>{{ isset($asesor) && $asesor ? 'Cambiar Asesor' : 'Asignar Asesor' }}</span>
                            </button>
                        @endif
                    </div>

                    {{-- INTEGRANTES DEL EQUIPO --}}
                    <div class="pt-2 space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="font-black text-slate-900 dark:text-white flex items-center gap-2 text-xs md:text-sm">
                                <span class="material-icons-round text-slate-400 text-lg">groups</span> Integrantes del Equipo
                            </h4>
                            <span class="text-[10px] font-black px-3 py-1 rounded-full border {{ $integrantes->count() >= 3 ? 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700' }}">
                                {{ $integrantes->count() }} / 3 Integrantes
                            </span>
                        </div>

                        <div class="divide-y divide-slate-100 dark:divide-slate-800 border border-slate-200/80 dark:border-slate-800 rounded-2xl px-4 bg-slate-50/50 dark:bg-slate-800/40">
                            @foreach($integrantes as $integrante)
                                @php
                                    $nom = $integrante->nombre;
                                    $pat = $integrante->apellido_paterno;
                                    try {
                                        if(is_string($nom) && (str_starts_with($nom, 'ey') || strlen($nom) > 50)) $nom = decrypt($nom);
                                        if(is_string($pat) && (str_starts_with($pat, 'ey') || strlen($pat) > 50)) $pat = decrypt($pat);
                                    } catch(\Throwable $e) {}
                                @endphp
                                <div class="py-3.5 flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-xs">
                                            {{ substr($nom, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-slate-100 text-xs md:text-sm">{{ $pat }} {{ $nom }}</p>
                                            <p class="text-[10px] text-slate-400 font-mono">Matrícula: {{ $integrante->username }}</p>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-black px-2.5 py-0.5 rounded-md border uppercase tracking-wider {{ $integrante->alumno_id == auth()->user()->alumno?->id ? 'bg-rose-50 dark:bg-rose-950/60 text-[#841B44] dark:text-rose-300 border-rose-200 dark:border-rose-900/60' : 'bg-slate-100 dark:bg-slate-700/80 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-600' }}">
                                        {{ $integrante->alumno_id == auth()->user()->alumno?->id ? 'Líder' : 'Colaborador' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- BOTÓN REPOSITORIO --}}
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                        <a href="{{ route('titulacion.repositorio', $proyecto->id) }}" 
                           class="w-full sm:w-auto px-7 py-3.5 bg-[#841B44] hover:bg-[#681535] dark:bg-rose-700 dark:hover:bg-rose-800 text-white font-extrabold rounded-2xl shadow-md active:scale-98 transition-all cursor-pointer flex items-center justify-center gap-2.5 text-xs md:text-sm">
                            <span class="material-icons-round text-xl">folder_open</span>
                            <span>Repositorio Digital de Entregables</span>
                            <span class="material-icons-round text-lg">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- PANEL LATERAL: AGREGAR INTEGRANTE --}}
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xs space-y-5 sticky top-6 transition-colors duration-200">
                    <h3 class="font-black text-slate-900 dark:text-white flex items-center gap-2 text-sm md:text-base">
                        <div class="w-8 h-8 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-[#841B44] dark:text-rose-400 flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-900/40">
                            <span class="material-icons-round text-base">person_add</span>
                        </div>
                        <span>Vincular Compañero</span>
                    </h3>
                    
                    @if(in_array($proyecto->estatus, ['Liberado_Exposicion', 'Aprobado']))
                        <div class="p-4 bg-emerald-50/60 dark:bg-emerald-950/30 border border-emerald-200/80 dark:border-emerald-800 rounded-2xl text-emerald-800 dark:text-emerald-300 text-xs space-y-2">
                            <div class="flex items-center gap-2 font-bold">
                                <span class="material-icons-round text-base text-emerald-600 dark:text-emerald-400">check_circle</span>
                                <span>Estructura de Equipo Finalizada</span>
                            </div>
                            <p class="text-[11px] leading-relaxed text-emerald-700 dark:text-emerald-300 font-medium">
                                El proyecto fue aprobado o se encuentra en dictamen final. Ya no es posible modificar la estructura de integrantes.
                            </p>
                        </div>
                    @elseif($integrantes->count() < 3)
                        <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed font-medium">
                            Ingresa la matrícula oficial del estudiante para integrarlo a tu equipo (Máximo 3 alumnos).
                        </p>

                        <form action="{{ route('titulacion.agregar-companero') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">

                            <div class="space-y-1.5">
                                <label class="block font-bold text-slate-700 dark:text-slate-300 text-xs">Matrícula del Estudiante *</label>
                                <input type="text" name="username" required
                                       class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3 font-mono font-bold text-slate-900 dark:text-slate-100 text-xs focus:ring-2 focus:ring-[#841B44] dark:focus:ring-rose-500 focus:outline-hidden transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                       placeholder="Ej: 22240105">
                            </div>

                            <button type="submit" class="w-full py-3 bg-[#841B44] hover:bg-[#681535] dark:bg-rose-700 dark:hover:bg-rose-800 text-white font-extrabold rounded-2xl transition-all active:scale-98 cursor-pointer flex items-center justify-center gap-2 text-xs shadow-md">
                                <span class="material-icons-round text-base">group_add</span>
                                <span>Agregar al Equipo</span>
                            </button>
                        </form>
                    @else
                        <div class="p-4 bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 rounded-2xl text-amber-800 dark:text-amber-200 text-xs space-y-1.5">
                            <div class="flex items-center gap-1.5 font-bold">
                                <span class="material-icons-round text-base text-amber-600 dark:text-amber-400">info</span>
                                <span>Límite Alcanzado</span>
                            </div>
                            <p class="text-[11px] leading-relaxed text-amber-700 dark:text-amber-300">
                                Este equipo ha registrado el cupo máximo permitido (3 alumnos).
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    @endif

</main>

{{-- MODAL ASIGNAR ASESOR --}}
<div id="modalAsesor" class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden animate-fade-in">
    <div class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden p-6 md:p-8 space-y-6 border border-slate-100 dark:border-slate-800 transition-colors">
        
        <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-4">
            <h3 class="font-black text-slate-900 dark:text-white text-base md:text-lg flex items-center gap-2">
                <span class="material-icons-round text-xl text-[#841B44] dark:text-rose-400">school</span>
                <span>Asignar Docente Asesor</span>
            </h3>
            <button onclick="document.getElementById('modalAsesor').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 rounded-lg transition-colors">
                <span class="material-icons-round text-2xl">close</span>
            </button>
        </div>

        <form action="{{ route('titulacion.asignar-asesor') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="proyecto_id" value="{{ $proyecto->id ?? '' }}">

            <div class="space-y-2">
                <label class="block font-bold text-slate-700 dark:text-slate-300 text-xs md:text-sm">Selecciona al Docente *</label>
                <select name="docente_id" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-semibold text-slate-800 dark:text-slate-100 text-xs md:text-sm focus:ring-2 focus:ring-[#841B44] dark:focus:ring-rose-500 focus:outline-hidden transition-all">
                    <option value="" disabled selected>Seleccionar docente...</option>
                    @foreach($docentes as $docente)
                        @php
                            $nomD = $docente->nombre;
                            $patD = $docente->apellido_paterno;
                            try {
                                if(is_string($nomD) && (str_starts_with($nomD, 'ey') || strlen($nomD) > 50)) $nomD = decrypt($nomD);
                                if(is_string($patD) && (str_starts_with($patD, 'ey') || strlen($patD) > 50)) $patD = decrypt($patD);
                            } catch(\Throwable $e) {}
                        @endphp
                        <option value="{{ $docente->id }}" {{ isset($proyecto) && ($proyecto->docente_asesor_id ?? $proyecto->asesor_id ?? null) == $docente->id ? 'selected' : '' }}>
                            Prof. {{ $patD }} {{ $nomD }} ({{ $docente->username }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modalAsesor').classList.add('hidden')" 
                        class="px-5 py-3 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800 text-xs md:text-sm transition-colors cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" class="px-6 py-3 bg-[#841B44] hover:bg-[#681535] dark:bg-rose-700 dark:hover:bg-rose-800 text-white font-extrabold rounded-2xl text-xs md:text-sm shadow-md active:scale-98 transition-all cursor-pointer">
                    Guardar Asesor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@extends('cpanel/plantillaestudiante')
@section('title', 'Trámite de Titulación')

@section('content')
<main class="p-6 md:p-8 space-y-8 max-w-7xl w-full mx-auto text-sm md:text-base">

    <!-- MENSAJES DE NOTIFICACIÓN -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 p-4 md:p-5 rounded-2xl flex items-center justify-between shadow-xs font-semibold">
            <div class="flex items-center gap-3">
                <span class="material-icons-round text-2xl text-emerald-600 dark:text-emerald-400">check_circle</span>
                <p class="text-sm md:text-base">{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-100 cursor-pointer">
                <span class="material-icons-round text-lg">close</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 p-4 md:p-5 rounded-2xl space-y-2 shadow-xs">
            <div class="flex items-center gap-2 font-bold text-sm md:text-base">
                <span class="material-icons-round text-xl text-rose-600 dark:text-rose-400">error</span>
                <span>Ocurrieron observaciones en la solicitud:</span>
            </div>
            <ul class="list-disc pl-8 text-xs md:text-sm space-y-1 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ENCABEZADO PRINCIPAL -->
    <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6 transition-colors duration-200">
        <div>
            <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-slate-100 flex items-center gap-3">
                <span class="material-icons-round text-2xl md:text-3xl text-[#841B44] dark:text-rose-400">history_edu</span> 
                Registro de Proyecto de Titulación
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm mt-1">Establece el protocolo de tu proyecto, agrega a los integrantes del equipo y asigna tu asesor.</p>
        </div>
        <div class="shrink-0 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900 text-[#841B44] dark:text-rose-300 px-4 py-2.5 rounded-2xl font-extrabold text-xs md:text-sm text-center">
            Etapa 1: Protocolo de Titulación
        </div>
    </div>

    @if(!$proyecto)
        <!-- PASO 1: FORMULARIO DE CREACIÓN DEL PROYECTO -->
        <div class="bg-white dark:bg-slate-900 p-7 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6 transition-colors duration-200">
            <div class="border-b border-slate-100 dark:border-slate-800 pb-4">
                <h3 class="text-base md:text-lg font-black text-slate-900 dark:text-slate-100">1. Registrar Titularidad del Proyecto</h3>
                <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm mt-0.5">Ingresa la denominación oficial aprobada para tu proyecto de titulación.</p>
            </div>

            <form action="{{ route('titulacion.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-2 text-xs md:text-sm">Nombre Completo del Proyecto *</label>
                    <input type="text" name="titulo" required value="{{ old('titulo') }}"
                           class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-4 font-semibold text-slate-900 dark:text-slate-100 text-xs md:text-sm focus:ring-2 focus:ring-[#841B44] focus:outline-hidden transition-all"
                           placeholder="Ej: Implementación del Sistema Unificado de Integración Educativa (SUIE)...">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-2 text-xs md:text-sm">Modalidad de Titulación *</label>
                        <select name="modalidad" required class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-4 font-semibold text-slate-800 dark:text-slate-100 text-xs md:text-sm focus:ring-2 focus:ring-[#841B44] focus:outline-hidden transition-all">
                            <option value="Proyecto de Titulación">Proyecto de Titulación</option>
                            <option value="Memoria de Experiencia Profesional">Memoria de Experiencia Profesional</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-2 text-xs md:text-sm">Resumen del Alcance (Opcional)</label>
                        <input type="text" name="resumen" value="{{ old('resumen') }}"
                               class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-4 font-medium text-slate-800 dark:text-slate-100 text-xs md:text-sm focus:ring-2 focus:ring-[#841B44] focus:outline-hidden transition-all"
                               placeholder="Breve descripción del problema a resolver...">
                    </div>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="px-7 py-3.5 bg-[#841B44] hover:bg-[#681535] dark:bg-rose-800 dark:hover:bg-rose-700 text-white font-extrabold text-sm rounded-2xl shadow-md transition-all cursor-pointer flex items-center gap-2">
                        <span class="material-icons-round text-lg">save</span> Registrar Proyecto
                    </button>
                </div>
            </form>
        </div>
    @else
        <!-- PASO 2: GESTIÓN DE EQUIPO, ASESOR Y ENLACE AL REPOSITORIO -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- DETALLES DEL PROYECTO -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-slate-900 p-7 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6 transition-colors duration-200">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 border-b border-slate-100 dark:border-slate-800 pb-5">
                        <div class="space-y-2">
                            <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-900 text-indigo-700 dark:text-indigo-300 text-xs font-bold rounded-lg uppercase tracking-wider inline-block">
                                {{ $proyecto->modalidad }}
                            </span>
                            <h3 class="text-lg md:text-xl font-black text-slate-900 dark:text-slate-100 leading-snug">
                                {{ $proyecto->titulo }}
                            </h3>
                        </div>

                        <!-- BADGE DE ESTATUS -->
                        <span class="px-4 py-1.5 text-xs font-black rounded-full uppercase border shrink-0 flex items-center gap-1.5
                            {{ $proyecto->estatus == 'Aprobado' ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-900' : '' }}
                            {{ $proyecto->estatus == 'Pendiente' ? 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-900' : '' }}
                            {{ $proyecto->estatus == 'En_Revision' || $proyecto->estatus == 'En Revision' ? 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-900' : '' }}
                            {{ $proyecto->estatus == 'Liberado_Exposicion' ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border-indigo-200 dark:border-indigo-900' : '' }}
                            {{ $proyecto->estatus == 'Rechazado' ? 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-900' : '' }}
                        ">
                            @if($proyecto->estatus == 'Liberado_Exposicion')
                                <span class="material-icons-round text-sm">record_voice_over</span> Liberado para Exposición
                            @elseif($proyecto->estatus == 'Aprobado')
                                <span class="material-icons-round text-sm">verified</span> Aprobado
                            @else
                                Estatus: {{ str_replace('_', ' ', $proyecto->estatus) }}
                            @endif
                        </span>
                    </div>

                    <!-- BANNER INFORMATIVO SI ESTÁ LIBERADO -->
                    @if($proyecto->estatus == 'Liberado_Exposicion')
                        <div class="p-5 bg-indigo-50/80 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-900/60 rounded-2xl text-indigo-950 dark:text-indigo-200 space-y-2">
                            <div class="flex items-center gap-2 font-extrabold text-sm md:text-base">
                                <span class="material-icons-round text-lg text-indigo-600 dark:text-indigo-400">how_to_vote</span>
                                Protocolo y Entregables Liberados para Defensa
                            </div>
                            <p class="text-xs md:text-sm text-indigo-800 dark:text-indigo-300 leading-relaxed font-normal">
                                Tu docente asesor ha autorizado este proyecto. Actualmente se encuentra en proceso de evaluación por parte del sínodo examinador (se requiere la aprobación de mínimo 2 de los 3 jurados para habilitar tu Proceso de Titulación oficial).
                            </p>
                        </div>
                    @endif

                    @if($proyecto->resumen)
                        <div class="text-slate-600 dark:text-slate-300 leading-relaxed bg-slate-50 dark:bg-slate-800/60 p-4 md:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 text-xs md:text-sm">
                            <strong class="text-slate-800 dark:text-slate-200 block mb-1">Resumen del Protocolo:</strong>
                            {{ $proyecto->resumen }}
                        </div>
                    @endif

                    <!-- ASESOR ASIGNADO -->
                    <div class="bg-slate-50/90 dark:bg-slate-800/60 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-rose-50 dark:bg-rose-950/60 text-[#841B44] dark:text-rose-300 rounded-2xl flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-900/60 shadow-3xs">
                                <span class="material-icons-round text-2xl">supervisor_account</span>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 dark:text-slate-400 font-extrabold uppercase tracking-wider">Asesor Metodológico / Técnico</p>
                                @if(isset($asesor) && $asesor)
                                    @php
                                        $nomA = $asesor->nombre;
                                        $patA = $asesor->apellido_paterno;
                                        try {
                                            if(is_string($nomA) && (str_starts_with($nomA, 'ey') || strlen($nomA) > 50)) $nomA = decrypt($nomA);
                                            if(is_string($patA) && (str_starts_with($patA, 'ey') || strlen($patA) > 50)) $patA = decrypt($patA);
                                        } catch(\Throwable $e) {}
                                    @endphp
                                    <p class="font-black text-slate-900 dark:text-slate-100 text-sm md:text-base mt-0.5">Prof. {{ $patA }} {{ $nomA }}</p>
                                @else
                                    <p class="font-bold text-amber-600 dark:text-amber-400 text-xs md:text-sm italic mt-0.5">Aún no se ha asignado un docente asesor</p>
                                @endif
                            </div>
                        </div>

                        @if(!in_array($proyecto->estatus, ['Liberado_Exposicion', 'Aprobado']))
                            <button onclick="document.getElementById('modalAsesor').classList.remove('hidden')" 
                                    class="px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 font-bold rounded-xl transition-colors cursor-pointer shrink-0 text-xs md:text-sm flex items-center justify-center gap-1.5 shadow-3xs">
                                <span class="material-icons-round text-base">person_add_alt</span> {{ isset($asesor) && $asesor ? 'Cambiar Asesor' : 'Asignar Asesor' }}
                            </button>
                        @endif
                    </div>

                    <!-- INTEGRANTES DEL EQUIPO -->
                    <div class="pt-2 space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="font-black text-slate-900 dark:text-slate-100 flex items-center gap-2 text-sm md:text-base">
                                <span class="material-icons-round text-slate-400 text-lg">groups</span> Integrantes Registrados
                            </h4>
                            <span class="text-xs font-bold px-3 py-1 rounded-full border {{ $integrantes->count() >= 3 ? 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-900' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700' }}">
                                {{ $integrantes->count() }} / 3 Integrantes
                            </span>
                        </div>

                        <div class="divide-y divide-slate-100 dark:divide-slate-800 border border-slate-100 dark:border-slate-800 rounded-2xl px-4 bg-slate-50/50 dark:bg-slate-800/30">
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
                                        <span class="material-icons-round text-slate-400 text-2xl">account_circle</span>
                                        <div>
                                            <p class="font-bold text-slate-800 dark:text-slate-200 text-xs md:text-sm">{{ $pat }} {{ $nom }}</p>
                                            <p class="text-xs text-slate-400 font-mono">Matrícula: {{ $integrante->username }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">
                                        {{ $integrante->alumno_id == auth()->user()->alumno?->id ? 'Líder / Creador' : 'Colaborador' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- BOTÓN IR AL REPOSITORIO -->
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                        <a href="{{ route('titulacion.repositorio', $proyecto->id) }}" 
                           class="w-full sm:w-auto px-7 py-3.5 bg-[#841B44] hover:bg-[#681535] dark:bg-rose-800 dark:hover:bg-rose-700 text-white font-extrabold rounded-2xl shadow-md transition-all cursor-pointer flex items-center justify-center gap-2.5 text-sm">
                            <span class="material-icons-round text-xl">folder_open</span> Repositorio de Entregables
                            <span class="material-icons-round text-lg">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- PANEL LATERAL: AGREGAR COMPAÑERO -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-5 transition-colors duration-200">
                    <h3 class="font-black text-slate-900 dark:text-slate-100 flex items-center gap-2 text-base md:text-lg">
                        <span class="material-icons-round text-[#841B44] dark:text-rose-400 text-xl">person_add</span> Vincular Compañero
                    </h3>
                    
                    @if(in_array($proyecto->estatus, ['Liberado_Exposicion', 'Aprobado']))
                        <div class="p-5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-600 dark:text-slate-300 text-xs md:text-sm space-y-2">
                            <div class="flex items-center gap-2 font-bold text-slate-800 dark:text-slate-100">
                                <span class="material-icons-round text-lg text-indigo-600 dark:text-indigo-400">lock</span>
                                Registro de Equipo Cerrado
                            </div>
                            <p class="text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                                El proyecto se encuentra en etapa de dictaminación o aprobación final. La estructura de integrantes ya no puede modificarse.
                            </p>
                        </div>
                    @elseif($integrantes->count() < 3)
                        <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm leading-relaxed">
                            Ingresa la matrícula oficial del estudiante para integrarlo a tu equipo de titulación (Máximo 3 alumnos por equipo).
                        </p>

                        <form action="{{ route('titulacion.agregar-companero') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">

                            <div>
                                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5 text-xs md:text-sm">Matrícula del Estudiante *</label>
                                <input type="text" name="username" required
                                       class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-mono font-bold text-slate-900 dark:text-slate-100 text-xs md:text-sm focus:ring-2 focus:ring-[#841B44] focus:outline-hidden transition-all"
                                       placeholder="Ej: 22240105">
                            </div>

                            <button type="submit" class="w-full py-3.5 bg-[#841B44] hover:bg-[#681535] dark:bg-rose-800 dark:hover:bg-rose-700 text-white font-extrabold rounded-2xl transition-all cursor-pointer flex items-center justify-center gap-2 text-xs md:text-sm shadow-md">
                                <span class="material-icons-round text-lg">group_add</span> Agregar al Equipo
                            </button>
                        </form>
                    @else
                        <div class="p-5 bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-900 rounded-2xl text-amber-800 dark:text-amber-200 text-xs md:text-sm space-y-2">
                            <div class="flex items-center gap-2 font-bold">
                                <span class="material-icons-round text-lg">info</span>
                                Límite de Equipo Alcanzado
                            </div>
                            <p class="text-xs leading-relaxed text-amber-700 dark:text-amber-300">
                                Este equipo ha alcanzado el cupo máximo permitido (3 integrantes).
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    @endif

</main>

<!-- MODAL ASIGNAR ASESOR -->
<div id="modalAsesor" class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden p-6 md:p-8 space-y-6 border border-slate-100 dark:border-slate-800">
        <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-4">
            <h3 class="font-black text-slate-900 dark:text-slate-100 text-base md:text-lg flex items-center gap-2">
                <span class="material-icons-round text-xl text-[#841B44] dark:text-rose-400">school</span> Asignar Docente Asesor
            </h3>
            <button onclick="document.getElementById('modalAsesor').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <span class="material-icons-round text-2xl">close</span>
            </button>
        </div>

        <form action="{{ route('titulacion.asignar-asesor') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="proyecto_id" value="{{ $proyecto->id ?? '' }}">

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-2 text-xs md:text-sm">Selecciona al Docente *</label>
                <select name="docente_id" required class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-semibold text-slate-800 dark:text-slate-100 text-xs md:text-sm focus:ring-2 focus:ring-[#841B44] focus:outline-hidden">
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

            <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modalAsesor').classList.add('hidden')" 
                        class="px-5 py-3 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800 text-xs md:text-sm transition-colors">
                    Cancelar
                </button>
                <button type="submit" class="px-6 py-3 bg-[#841B44] hover:bg-[#681535] dark:bg-rose-800 dark:hover:bg-rose-700 text-white font-extrabold rounded-2xl text-xs md:text-sm shadow-md transition-colors">
                    Guardar Asesor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
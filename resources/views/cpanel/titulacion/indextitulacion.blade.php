@extends('cpanel/plantillaestudiante')
@section('title', 'Trámite de Titulación')
@section('content')
<main class="p-4 md:p-6 space-y-6 max-w-7xl w-full mx-auto text-xs">
    <!-- Mensajes de Notificación -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl text-xs font-bold flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-base">check_circle</span>
                <p>{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 cursor-pointer">
                <span class="material-icons-round text-sm">close</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl text-xs font-bold space-y-1">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-sm">error</span>
                <p>Ocurrieron observaciones en la solicitud:</p>
            </div>
            <ul class="list-disc pl-8 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ENCABEZADO PRINCIPAL -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                <span class="material-icons-round text-[#841B44]">history_edu</span> Registro de Proyecto de Titulación
            </h2>
            <p class="text-slate-500 text-[11px] mt-0.5">Establece el protocolo de tu proyecto, agrega a los integrantes del equipo y asigna tu asesor.</p>
        </div>
        <div class="shrink-0 bg-rose-50 border border-rose-100 text-[#841B44] px-3 py-1.5 rounded-xl font-bold text-center">
            Etapa 1: Proyecto de Titulación
        </div>
    </div>

    @if(!$proyecto)
        <!-- PASO 1: FORMULARIO DE CREACIÓN DEL PROYECTO -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-sm font-bold text-slate-900">1. Registrar Titularidad del Proyecto</h3>
                <p class="text-slate-500 text-[11px]">Ingresa la denominación oficial aprobada para tu proyecto de titulación.</p>
            </div>

            <form action="{{ route('titulacion.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nombre Completo del Proyecto *</label>
                    <input type="text" name="titulo" required value="{{ old('titulo') }}"
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden"
                           placeholder="Ej: Implementación del Sistema Unificado de Integración Educativa (SUIE)...">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Modalidad de Titulación *</label>
                        <select name="modalidad" required class="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44]">
                            <option value="Proyecto de Titulación">Proyecto de Titulación</option>
                            <option value="Memoria de Experiencia Profesional">Memoria de Experiencia Profesional</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Resumen del Alcance (Opcional)</label>
                        <input type="text" name="resumen" value="{{ old('resumen') }}"
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 font-medium focus:ring-1 focus:ring-[#841B44]"
                               placeholder="Breve descripción del problema a resolver...">
                    </div>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-[#841B44] hover:bg-[#681535] text-white font-bold rounded-xl shadow-2xs transition-colors cursor-pointer flex items-center gap-1.5">
                        <span class="material-icons-round text-sm">save</span> Registrar Proyecto
                    </button>
                </div>
            </form>
        </div>
    @else
        <!-- PASO 2: GESTIÓN DE EQUIPO, ASESOR Y ENLACE AL REPOSITORIO -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Detalles del Proyecto -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs space-y-5">
                    <div class="flex justify-between items-start gap-4">
                        <div>
                            <span class="px-2.5 py-1 bg-indigo-50 border border-indigo-100 text-indigo-700 text-[10px] font-bold rounded-md uppercase">
                                {{ $proyecto->modalidad }}
                            </span>
                            <h3 class="text-base font-extrabold text-slate-900 mt-2">{{ $proyecto->titulo }}</h3>
                        </div>
                        <span class="px-2.5 py-1 text-[10px] font-bold rounded-full uppercase border shrink-0
                            {{ $proyecto->estatus == 'Aprobado' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                            {{ $proyecto->estatus == 'Pendiente' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                            {{ $proyecto->estatus == 'En Revision' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                        ">
                            Estatus: {{ $proyecto->estatus }}
                        </span>
                    </div>

                    @if($proyecto->resumen)
                        <p class="text-slate-600 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-200/60">
                            {{ $proyecto->resumen }}
                        </p>
                    @endif

                    <!-- Información del Asesor Asignado -->
                    <div class="bg-slate-50/80 p-4 rounded-xl border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-rose-50 text-[#841B44] rounded-xl flex items-center justify-center shrink-0 border border-rose-100">
                                <span class="material-icons-round text-lg">supervisor_account</span>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Asesor Metodológico / Técnico</p>
                                @if(isset($asesor) && $asesor)
                                    @php
                                        $nomA = $asesor->nombre;
                                        $patA = $asesor->apellido_paterno;
                                        try {
                                            if(is_string($nomA) && (str_starts_with($nomA, 'ey') || strlen($nomA) > 50)) $nomA = decrypt($nomA);
                                            if(is_string($patA) && (str_starts_with($patA, 'ey') || strlen($patA) > 50)) $patA = decrypt($patA);
                                        } catch(\Throwable $e) {}
                                    @endphp
                                    <p class="font-extrabold text-slate-900 text-xs">Prof. {{ $patA }} {{ $nomA }}</p>
                                @else
                                    <p class="font-bold text-amber-600 text-xs italic">Aún no se ha asignado un docente asesor</p>
                                @endif
                            </div>
                        </div>

                        <!-- Botón para Vincular/Cambiar Asesor -->
                        <button onclick="document.getElementById('modalAsesor').classList.remove('hidden')" 
                                class="px-3 py-1.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-100 font-bold rounded-lg transition-colors cursor-pointer shrink-0 text-[11px] flex items-center justify-center gap-1">
                            <span class="material-icons-round text-sm">person_add_alt</span> {{ isset($asesor) && $asesor ? 'Cambiar Asesor' : 'Asignar Asesor' }}
                        </button>
                    </div>

                    <!-- Integrantes del Equipo -->
                    <div class="pt-2 space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-slate-900 flex items-center gap-1.5">
                                <span class="material-icons-round text-slate-400 text-sm">groups</span> Integrantes Registrados
                            </h4>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $integrantes->count() >= 3 ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                {{ $integrantes->count() }} / 3 Integrantes
                            </span>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @foreach($integrantes as $integrante)
                                @php
                                    $nom = $integrante->nombre;
                                    $pat = $integrante->apellido_paterno;
                                    try {
                                        if(is_string($nom) && (str_starts_with($nom, 'ey') || strlen($nom) > 50)) $nom = decrypt($nom);
                                        if(is_string($pat) && (str_starts_with($pat, 'ey') || strlen($pat) > 50)) $pat = decrypt($pat);
                                    } catch(\Throwable $e) {}
                                @endphp
                                <div class="py-2.5 flex justify-between items-center">
                                    <div class="flex items-center gap-2">
                                        <span class="material-icons-round text-slate-400">account_circle</span>
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $pat }} {{ $nom }}</p>
                                            <p class="text-[10px] text-slate-400 font-mono">Matrícula: {{ $integrante->username }}</p>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">
                                        {{ $integrante->alumno_id == auth()->user()->alumno?->id ? 'Líder / Creador' : 'Colaborador' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- BOTÓN PRINCIPAL PARA IR AL REPOSITORIO -->
                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <a href="{{ route('titulacion.repositorio', $proyecto->id) }}" 
                           class="w-full sm:w-auto px-6 py-3 bg-[#841B44] hover:bg-[#681535] text-white font-extrabold rounded-xl shadow-xs transition-colors cursor-pointer flex items-center justify-center gap-2 text-xs">
                            <span class="material-icons-round text-base">folder_open</span> Ir al Repositorio del Proyecto
                            <span class="material-icons-round text-sm">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral: Agregar Compañero de Equipo -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs space-y-4">
                    <h3 class="font-bold text-slate-900 flex items-center gap-1.5">
                        <span class="material-icons-round text-[#841B44] text-sm">person_add</span> Vincular Compañero
                    </h3>
                    
                    @if($integrantes->count() < 3)
                        <p class="text-slate-500 leading-relaxed">
                            Ingresa la matrícula oficial del estudiante para integrarlo a tu equipo de titulación (Máximo 3 alumnos por equipo).
                        </p>

                        <form action="{{ route('titulacion.agregar-companero') }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Matrícula del Estudiante *</label>
                                <input type="text" name="username" required
                                       class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-mono font-bold text-slate-800 focus:ring-1 focus:ring-[#841B44]"
                                       placeholder="Ej: 22240105">
                            </div>

                            <button type="submit" class="w-full py-2.5 bg-[#841B44] hover:bg-[#681535] text-white font-bold rounded-xl transition-colors cursor-pointer flex items-center justify-center gap-1">
                                <span class="material-icons-round text-sm">group_add</span> Agregar al Equipo
                            </button>
                        </form>
                    @else
                        <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-xs space-y-1.5">
                            <div class="flex items-center gap-1.5 font-bold">
                                <span class="material-icons-round text-base">info</span>
                                Límite de Equipo Alcanzado
                            </div>
                            <p class="text-[11px] leading-relaxed">
                                Este equipo ha registrado el cupo máximo permitido (3 integrantes). No es posible vincular a más estudiantes.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    @endif

</main>

<!-- MODAL PARA ASIGNAR ASESOR DOCENTE -->
<div id="modalAsesor" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden p-6 space-y-4">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <h3 class="font-extrabold text-slate-900 text-sm flex items-center gap-1.5">
                <span class="material-icons-round text-[#841B44]">school</span> Asignar Docente Asesor
            </h3>
            <button onclick="document.getElementById('modalAsesor').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <span class="material-icons-round">close</span>
            </button>
        </div>

        <form action="{{ route('titulacion.asignar-asesor') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="proyecto_id" value="{{ $proyecto->id ?? '' }}">

            <div>
                <label class="block font-bold text-slate-700 mb-1">Selecciona al Docente *</label>
                <select name="docente_id" required class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44]">
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

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('modalAsesor').classList.add('hidden')" 
                        class="px-4 py-2 border border-slate-300 text-slate-600 font-bold rounded-xl hover:bg-slate-50">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-[#841B44] text-white font-bold rounded-xl hover:bg-[#681535]">
                    Guardar Asesor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
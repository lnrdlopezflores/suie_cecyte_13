@extends('cpanel/plantillaestudiante')
@section('title', 'Repositorio del Proyecto')

@section('content')
<main class="p-4 md:p-6 space-y-6 max-w-7xl w-full mx-auto text-xs transition-colors duration-200">

    {{-- ALERTA DE ÉXITO --}}
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 p-4 rounded-2xl text-xs font-bold flex items-center justify-between shadow-2xs transition-all animate-fade-in">
            <div class="flex items-center gap-2.5">
                <span class="material-icons-round text-lg text-emerald-600 dark:text-emerald-400">check_circle</span>
                <p>{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-200 cursor-pointer p-1 rounded-lg hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition-colors">
                <span class="material-icons-round text-sm">close</span>
            </button>
        </div>
    @endif

    {{-- ENCABEZADO Y MIGAS DE PAN --}}
    <div class="bg-white dark:bg-slate-900 p-5 md:p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-colors duration-200">
        <div>
            <nav class="flex items-center gap-2 text-slate-400 dark:text-slate-500 font-bold mb-1.5 text-[11px]">
                <a href="{{ route('titulacion.index') }}" class="hover:text-rose-700 dark:hover:text-rose-400 transition-colors flex items-center gap-0.5">
                    <span class="material-icons-round text-sm">arrow_back</span> Proyecto de Titulación
                </a>
                <span>/</span>
                <span class="text-slate-600 dark:text-slate-300">Repositorio Digital</span>
            </nav>
            <h2 class="text-base md:text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-[#841B44] dark:text-rose-400 flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-900/40">
                    <span class="material-icons-round text-lg">folder_special</span>
                </div>
                <span>Repositorio de Entregables</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-[11px] mt-1 font-medium">Gestiona y actualiza los archivos base requeridos para la evaluación de tu proyecto final.</p>
        </div>
    </div>

    {{-- CARD RESUMEN Y PROGRESO GENERAL --}}
    @php
        $entregados = ($proyecto->documento_url ? 1 : 0) + ($proyecto->presentacion_url ? 1 : 0) + ($proyecto->video_url ? 1 : 0);
        $porcentajeProgreso = round(($entregados / 3) * 100);
    @endphp

    <div class="bg-white dark:bg-slate-900 p-5 md:p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xs space-y-4 transition-colors duration-200">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900/50 text-[#841B44] dark:text-rose-300 text-[10px] font-black rounded-lg uppercase tracking-wider">
                        {{ $proyecto->modalidad ?? 'Proyecto de Titulación' }}
                    </span>
                    <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500">•</span>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold">
                        Especialidad: <strong class="text-slate-700 dark:text-slate-200">{{ $proyecto->especialidad_historica ?? 'General' }}</strong>
                    </span>
                </div>
                <h3 class="text-sm md:text-base font-black text-slate-900 dark:text-white tracking-tight">{{ $proyecto->titulo }}</h3>
            </div>

            <div class="bg-slate-50 dark:bg-slate-800/80 px-4 py-3 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shrink-0 text-left md:text-right w-full md:w-auto flex md:block justify-between items-center">
                <span class="text-[10px] text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider block">Docente Asesor</span>
                <span class="font-extrabold text-slate-800 dark:text-slate-100 text-xs">
                    @if(isset($asesor) && $asesor)
                        @php
                            $nomA = $asesor->nombre;
                            $patA = $asesor->apellido_paterno;
                            try {
                                if (is_string($nomA) && (str_starts_with($nomA, 'ey') || strlen($nomA) > 50)) $nomA = decrypt($nomA);
                                if (is_string($patA) && (str_starts_with($patA, 'ey') || strlen($patA) > 50)) $patA = decrypt($patA);
                            } catch (\Throwable $e) {}
                        @endphp
                        Prof. {{ $patA }} {{ $nomA }}
                    @else
                        <span class="text-amber-600 dark:text-amber-400 italic font-semibold flex items-center gap-1">
                            <span class="material-icons-round text-xs">warning</span> Sin asesor asignado
                        </span>
                    @endif
                </span>
            </div>
        </div>

        {{-- Barra de Progreso Dinámica --}}
        <div class="pt-3 border-t border-slate-100 dark:border-slate-800 space-y-1.5">
            <div class="flex justify-between items-center text-[11px]">
                <span class="font-bold text-slate-600 dark:text-slate-400">Progreso del Repositorio</span>
                <span class="font-black text-[#841B44] dark:text-rose-400">{{ $entregados }} de 3 completados ({{ $porcentajeProgreso }}%)</span>
            </div>
            <div class="w-full h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-[#841B44] dark:bg-rose-500 rounded-full transition-all duration-500 ease-out" style="width: {{ $porcentajeProgreso }}%"></div>
            </div>
        </div>
    </div>

    {{-- TARJETAS DE ENTREGA DINÁMICAS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- 1. REPORTE ESCRITO -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xs hover:shadow-md dark:hover:border-slate-700 transition-all duration-300 flex flex-col justify-between space-y-5">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="w-11 h-11 bg-rose-50 dark:bg-rose-950/60 text-[#841B44] dark:text-rose-400 rounded-2xl flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-900/40 shadow-2xs">
                        <span class="material-icons-round text-2xl">picture_as_pdf</span>
                    </div>
                    <span class="px-3 py-1 text-[10px] font-black rounded-full border transition-colors {{ $proyecto->documento_url ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-700' }}">
                        {{ $proyecto->documento_url ? '✓ Cargado' : 'Pendiente' }}
                    </span>
                </div>

                <div>
                    <h4 class="font-extrabold text-slate-900 dark:text-white text-xs sm:text-sm">1. Reporte Escrito Final</h4>
                    <p class="text-slate-500 dark:text-slate-400 text-[11px] mt-0.5 font-medium leading-relaxed">Memoria de estadía o proyecto en formato PDF (Máx. 30MB).</p>
                </div>

                @if($proyecto->documento_url)
                    <div class="bg-slate-50 dark:bg-slate-800/80 p-3 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 flex items-center justify-between">
                        <div class="flex items-center gap-2 truncate max-w-[150px]">
                            <span class="material-icons-round text-base text-rose-600 dark:text-rose-400">description</span>
                            <span class="font-mono text-[10px] text-slate-700 dark:text-slate-300 truncate">
                                {{ basename($proyecto->documento_url) }}
                            </span>
                        </div>
                        <a href="{{ asset('storage/' . $proyecto->documento_url) }}" target="_blank" class="px-2.5 py-1 bg-white dark:bg-slate-700 text-[#841B44] dark:text-rose-300 border border-slate-200 dark:border-slate-600 hover:bg-rose-50 dark:hover:bg-slate-600 rounded-xl font-bold text-[10px] flex items-center gap-1 transition-colors shadow-2xs">
                            <span class="material-icons-round text-xs">visibility</span> Ver
                        </a>
                    </div>
                @endif
            </div>

            <form action="{{ route('titulacion.guardar-entregable') }}" method="POST" enctype="multipart/form-data" class="pt-4 border-t border-slate-100 dark:border-slate-800/80 space-y-3">
                @csrf
                <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">
                <input type="hidden" name="tipo" value="Reporte">

                <div class="relative">
                    <input type="file" name="archivo" accept=".pdf" required class="block w-full text-[10px] text-slate-500 dark:text-slate-400 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-rose-50 dark:file:bg-rose-950/80 file:text-[#841B44] dark:file:text-rose-300 hover:file:bg-rose-100 dark:hover:file:bg-rose-900/60 cursor-pointer transition-colors">
                </div>

                <button type="submit" class="w-full py-2.5 bg-[#841B44] hover:bg-[#681535] dark:bg-rose-700 dark:hover:bg-rose-800 text-white font-extrabold rounded-2xl transition-all shadow-md active:scale-98 cursor-pointer flex items-center justify-center gap-1.5">
                    <span class="material-icons-round text-base">cloud_upload</span> 
                    <span>{{ $proyecto->documento_url ? 'Actualizar Reporte' : 'Subir Reporte' }}</span>
                </button>
            </form>
        </div>

        <!-- 2. PRESENTACIÓN EJECUTIVA -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xs hover:shadow-md dark:hover:border-slate-700 transition-all duration-300 flex flex-col justify-between space-y-5">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="w-11 h-11 bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 rounded-2xl flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/40 shadow-2xs">
                        <span class="material-icons-round text-2xl">slideshow</span>
                    </div>
                    <span class="px-3 py-1 text-[10px] font-black rounded-full border transition-colors {{ $proyecto->presentacion_url ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-700' }}">
                        {{ $proyecto->presentacion_url ? '✓ Cargado' : 'Pendiente' }}
                    </span>
                </div>

                <div>
                    <h4 class="font-extrabold text-slate-900 dark:text-white text-xs sm:text-sm">2. Presentación Técnica</h4>
                    <p class="text-slate-500 dark:text-slate-400 text-[11px] mt-0.5 font-medium leading-relaxed">Diapositivas para la defensa (.pdf, .pptx - Máx. 30MB).</p>
                </div>

                @if($proyecto->presentacion_url)
                    <div class="bg-slate-50 dark:bg-slate-800/80 p-3 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 flex items-center justify-between">
                        <div class="flex items-center gap-2 truncate max-w-[150px]">
                            <span class="material-icons-round text-base text-amber-600 dark:text-amber-400">co_present</span>
                            <span class="font-mono text-[10px] text-slate-700 dark:text-slate-300 truncate">
                                {{ basename($proyecto->presentacion_url) }}
                            </span>
                        </div>
                        <a href="{{ asset('storage/' . $proyecto->presentacion_url) }}" target="_blank" class="px-2.5 py-1 bg-white dark:bg-slate-700 text-[#841B44] dark:text-rose-300 border border-slate-200 dark:border-slate-600 hover:bg-rose-50 dark:hover:bg-slate-600 rounded-xl font-bold text-[10px] flex items-center gap-1 transition-colors shadow-2xs">
                            <span class="material-icons-round text-xs">download</span> Descargar
                        </a>
                    </div>
                @endif
            </div>

            <form action="{{ route('titulacion.guardar-entregable') }}" method="POST" enctype="multipart/form-data" class="pt-4 border-t border-slate-100 dark:border-slate-800/80 space-y-3">
                @csrf
                <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">
                <input type="hidden" name="tipo" value="Presentacion">

                <div class="relative">
                    <input type="file" name="archivo" accept=".pdf,.pptx,.ppt" required class="block w-full text-[10px] text-slate-500 dark:text-slate-400 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-amber-50 dark:file:bg-amber-950/80 file:text-amber-700 dark:file:text-amber-300 hover:file:bg-amber-100 dark:hover:file:bg-amber-900/60 cursor-pointer transition-colors">
                </div>

                <button type="submit" class="w-full py-2.5 bg-[#841B44] hover:bg-[#681535] dark:bg-rose-700 dark:hover:bg-rose-800 text-white font-extrabold rounded-2xl transition-all shadow-md active:scale-98 cursor-pointer flex items-center justify-center gap-1.5">
                    <span class="material-icons-round text-base">cloud_upload</span> 
                    <span>{{ $proyecto->presentacion_url ? 'Actualizar Presentación' : 'Subir Presentación' }}</span>
                </button>
            </form>
        </div>

        <!-- 3. VIDEO DEMOSTRATIVO -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xs hover:shadow-md dark:hover:border-slate-700 transition-all duration-300 flex flex-col justify-between space-y-5">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="w-11 h-11 bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center shrink-0 border border-blue-100 dark:border-blue-900/40 shadow-2xs">
                        <span class="material-icons-round text-2xl">video_library</span>
                    </div>
                    <span class="px-3 py-1 text-[10px] font-black rounded-full border transition-colors {{ $proyecto->video_url ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-700' }}">
                        {{ $proyecto->video_url ? '✓ Registrado' : 'Opcional' }}
                    </span>
                </div>

                <div>
                    <h4 class="font-extrabold text-slate-900 dark:text-white text-xs sm:text-sm">3. Video Demostrativo</h4>
                    <p class="text-slate-500 dark:text-slate-400 text-[11px] mt-0.5 font-medium leading-relaxed">Enlace a YouTube, Drive o OneDrive con la demostración.</p>
                </div>

                @if($proyecto->video_url)
                    <div class="bg-slate-50 dark:bg-slate-800/80 p-3 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 truncate max-w-[150px]">
                            <span class="material-icons-round text-base text-blue-600 dark:text-blue-400">link</span>
                            <span class="font-mono text-[10px] text-blue-600 dark:text-blue-400 truncate">
                                {{ $proyecto->video_url }}
                            </span>
                        </div>
                        <a href="{{ $proyecto->video_url }}" target="_blank" class="px-2.5 py-1 bg-white dark:bg-slate-700 text-[#841B44] dark:text-rose-300 border border-slate-200 dark:border-slate-600 hover:bg-rose-50 dark:hover:bg-slate-600 rounded-xl font-bold text-[10px] flex items-center gap-1 transition-colors shadow-2xs shrink-0">
                            <span class="material-icons-round text-xs">open_in_new</span> Abrir
                        </a>
                    </div>
                @endif
            </div>

            <form action="{{ route('titulacion.guardar-video') }}" method="POST" class="pt-4 border-t border-slate-100 dark:border-slate-800/80 space-y-3">
                @csrf
                <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">

                <div class="relative">
                    <input type="url" name="video_url" required value="{{ old('video_url', $proyecto->video_url ?? '') }}" 
                           class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-2xl p-2.5 font-medium text-[11px] focus:ring-2 focus:ring-[#841B44] dark:focus:ring-rose-500 focus:outline-hidden transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500" 
                           placeholder="https://youtu.be/...">
                </div>

                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white font-extrabold rounded-2xl transition-all shadow-md active:scale-98 cursor-pointer flex items-center justify-center gap-1.5">
                    <span class="material-icons-round text-base">link</span>
                    <span>{{ $proyecto->video_url ? 'Actualizar Enlace' : 'Guardar Enlace' }}</span>
                </button>
            </form>
        </div>

    </div>

</main>
@endsection
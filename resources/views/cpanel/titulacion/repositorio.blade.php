@extends('cpanel/plantillaestudiante')
@section('title', 'Repositorio del Proyecto')
@section('content')
<main class="p-4 md:p-6 space-y-6 max-w-7xl w-full mx-auto text-xs">

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

    <!-- ENCABEZADO -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-slate-400 font-bold mb-1">
                <a href="{{ route('titulacion.index') }}" class="hover:text-[#841B44] transition-colors flex items-center gap-0.5">
                    <span class="material-icons-round text-sm">arrow_back</span> Proyecto de Titulación
                </a>
                <span>/</span>
                <span class="text-slate-600">Repositorio Digital</span>
            </div>
            <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                <span class="material-icons-round text-[#841B44]">folder_special</span> Repositorio de Entregables
            </h2>
            <p class="text-slate-500 text-[11px] mt-0.5">Carga los tres archivos base de tu proyecto para revisión y evaluación.</p>
        </div>
    </div>

    <!-- RESUMEN -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="px-2 py-0.5 bg-rose-50 border border-rose-100 text-[#841B44] text-[10px] font-bold rounded-md uppercase">
                {{ $proyecto->modalidad ?? 'Proyecto de Titulación' }}
            </span>
            <h3 class="text-sm font-extrabold text-slate-900 mt-1">{{ $proyecto->titulo }}</h3>
            <p class="text-[11px] text-slate-500 mt-0.5">
                Especialidad: <span class="font-bold text-slate-700">{{ $proyecto->especialidad_historica ?? 'General' }}</span>
            </p>
        </div>

        <div class="bg-slate-50 px-4 py-2 rounded-xl border border-slate-200/80 shrink-0 text-right">
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Docente Asesor</span>
            <span class="font-extrabold text-slate-800 text-xs">
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
                    <span class="text-amber-600 italic font-semibold">Sin asesor asignado</span>
                @endif
            </span>
        </div>
    </div>

    <!-- TARJETAS DE ENTREGA -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- 1. REPORTE ESCRITO (documento_url) -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs flex flex-col justify-between space-y-4">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center shrink-0 border border-red-100">
                        <span class="material-icons-round text-xl">picture_as_pdf</span>
                    </div>
                    <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full border {{ $proyecto->documento_url ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                        {{ $proyecto->documento_url ? 'Cargado' : 'Pendiente' }}
                    </span>
                </div>

                <div>
                    <h4 class="font-extrabold text-slate-900 text-xs">1. Reporte Escrito Final</h4>
                    <p class="text-slate-500 text-[11px] mt-0.5">Memoria de estadía o proyecto en formato PDF (Máx. 30MB).</p>
                </div>

                @if($proyecto->documento_url)
                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200/80 flex items-center justify-between">
                        <span class="font-mono text-[10px] text-slate-600 truncate max-w-[150px]">
                            {{ basename($proyecto->documento_url) }}
                        </span>
                        <a href="{{ asset('storage/' . $proyecto->documento_url) }}" target="_blank" class="text-[#841B44] hover:underline font-bold text-[10px] flex items-center gap-0.5">
                            <span class="material-icons-round text-xs">visibility</span> Ver PDF
                        </a>
                    </div>
                @endif
            </div>

            <form action="{{ route('titulacion.guardar-entregable') }}" method="POST" enctype="multipart/form-data" class="pt-2 border-t border-slate-100 space-y-3">
                @csrf
                <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">
                <input type="hidden" name="tipo" value="Reporte">

                <input type="file" name="archivo" accept=".pdf" required class="block w-full text-[10px] text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-rose-50 file:text-[#841B44] hover:file:bg-rose-100 cursor-pointer">

                <button type="submit" class="w-full py-2 bg-[#841B44] hover:bg-[#681535] text-white font-bold rounded-xl transition-colors cursor-pointer flex items-center justify-center gap-1">
                    <span class="material-icons-round text-sm">cloud_upload</span> Subir Reporte
                </button>
            </form>
        </div>

        <!-- 2. PRESENTACIÓN EJECUTIVA (presentacion_url) -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs flex flex-col justify-between space-y-4">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center shrink-0 border border-amber-100">
                        <span class="material-icons-round text-xl">slideshow</span>
                    </div>
                    <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full border {{ $proyecto->presentacion_url ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                        {{ $proyecto->presentacion_url ? 'Cargado' : 'Pendiente' }}
                    </span>
                </div>

                <div>
                    <h4 class="font-extrabold text-slate-900 text-xs">2. Presentación Técnica</h4>
                    <p class="text-slate-500 text-[11px] mt-0.5">Diapositivas para la defensa (.pdf, .pptx - Máx. 30MB).</p>
                </div>

                @if($proyecto->presentacion_url)
                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200/80 flex items-center justify-between">
                        <span class="font-mono text-[10px] text-slate-600 truncate max-w-[150px]">
                            {{ basename($proyecto->presentacion_url) }}
                        </span>
                        <a href="{{ asset('storage/' . $proyecto->presentacion_url) }}" target="_blank" class="text-[#841B44] hover:underline font-bold text-[10px] flex items-center gap-0.5">
                            <span class="material-icons-round text-xs">download</span> Descargar
                        </a>
                    </div>
                @endif
            </div>

            <form action="{{ route('titulacion.guardar-entregable') }}" method="POST" enctype="multipart/form-data" class="pt-2 border-t border-slate-100 space-y-3">
                @csrf
                <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">
                <input type="hidden" name="tipo" value="Presentacion">

                <input type="file" name="archivo" accept=".pdf,.pptx,.ppt" required class="block w-full text-[10px] text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-rose-50 file:text-[#841B44] hover:file:bg-rose-100 cursor-pointer">

                <button type="submit" class="w-full py-2 bg-[#841B44] hover:bg-[#681535] text-white font-bold rounded-xl transition-colors cursor-pointer flex items-center justify-center gap-1">
                    <span class="material-icons-round text-sm">cloud_upload</span> Subir Presentación
                </button>
            </form>
        </div>

        <!-- 3. VIDEO DEMOSTRATIVO (video_url) -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs flex flex-col justify-between space-y-4">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0 border border-blue-100">
                        <span class="material-icons-round text-xl">video_library</span>
                    </div>
                    <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full border {{ $proyecto->video_url ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                        {{ $proyecto->video_url ? 'Registrado' : 'Opcional' }}
                    </span>
                </div>

                <div>
                    <h4 class="font-extrabold text-slate-900 text-xs">3. Video Demostrativo</h4>
                    <p class="text-slate-500 text-[11px] mt-0.5">Enlace a YouTube, Drive o OneDrive con la demostración.</p>
                </div>

                @if($proyecto->video_url)
                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200/80 flex items-center justify-between">
                        <span class="font-mono text-[10px] text-blue-600 truncate max-w-[150px]">
                            {{ $proyecto->video_url }}
                        </span>
                        <a href="{{ $proyecto->video_url }}" target="_blank" class="text-[#841B44] hover:underline font-bold text-[10px] flex items-center gap-0.5">
                            <span class="material-icons-round text-xs">open_in_new</span> Abrir
                        </a>
                    </div>
                @endif
            </div>

            <form action="{{ route('titulacion.guardar-video') }}" method="POST" class="pt-2 border-t border-slate-100 space-y-3">
                @csrf
                <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">

                <input type="url" name="video_url" required value="{{ old('video_url', $proyecto->video_url ?? '') }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2 font-medium text-[11px] focus:ring-1 focus:ring-[#841B44]" placeholder="https://youtu.be/...">

                <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-colors cursor-pointer flex items-center justify-center gap-1">
                    <span class="material-icons-round text-sm">link</span> Guardar Enlace
                </button>
            </form>
        </div>

    </div>

</main>
@endsection
@extends('cpanel/plantillaestudiante')
@section('title', 'Repositorio del Proyecto')
@section('content')
<main class="p-4 md:p-6 space-y-6 max-w-7xl w-full mx-auto text-xs">

    <!-- MENSAJES DE ALERTA DE SESIÓN -->
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
                <p>Ocurrieron observaciones en la carga de archivos:</p>
            </div>
            <ul class="list-disc pl-8 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ENCABEZADO Y NAVEGACIÓN -->
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
            <p class="text-slate-500 text-[11px] mt-0.5">Carga el reporte escrito, la presentación técnica y el video demostrativo para la evaluación del comité.</p>
        </div>
    </div>

    <!-- TARJETA RESUMEN DEL PROYECTO -->
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
                        // Descifrado dinámico y defensivo para el nombre del asesor
                        $nombreAsesor = $asesor->nombre ?? '';
                        $paternoAsesor = $asesor->apellido_paterno ?? '';

                        try {
                            if (is_string($nombreAsesor) && (str_starts_with($nombreAsesor, 'ey') || strlen($nombreAsesor) > 50)) {
                                $nombreAsesor = decrypt($nombreAsesor);
                            }
                            if (is_string($paternoAsesor) && (str_starts_with($paternoAsesor, 'ey') || strlen($paternoAsesor) > 50)) {
                                $paternoAsesor = decrypt($paternoAsesor);
                            }
                        } catch (\Throwable $e) {
                            $nombreAsesor = str_replace(' (Plain)', '', $nombreAsesor);
                            $paternoAsesor = str_replace(' (Plain)', '', $paternoAsesor);
                        }
                    @endphp
                    Prof. {{ $paternoAsesor }} {{ $nombreAsesor }}
                @else
                    <span class="text-amber-600 italic font-semibold">Sin asesor asignado</span>
                @endif
            </span>
        </div>
    </div>

    <!-- SECCIÓN DE ENTREGABLES DEL REPOSITORIO -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- ENTREGABLE 1: REPORTE FINAL (PDF) -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs flex flex-col justify-between space-y-4">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center shrink-0 border border-red-100">
                        <span class="material-icons-round text-xl">picture_as_pdf</span>
                    </div>
                    <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full border
                        {{ ($documentos['Reporte']->estatus ?? 'Pendiente') == 'Aprobado' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                        {{ ($documentos['Reporte']->estatus ?? 'Pendiente') == 'Pendiente' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                        {{ ($documentos['Reporte']->estatus ?? 'Pendiente') == 'Rechazado' ? 'bg-rose-50 text-rose-700 border-rose-200' : '' }}
                    ">
                        {{ $documentos['Reporte']->estatus ?? 'Sin Cargar' }}
                    </span>
                </div>

                <div>
                    <h4 class="font-extrabold text-slate-900 text-xs">1. Reporte Escrito Final</h4>
                    <p class="text-slate-500 text-[11px] mt-0.5">Documento completo en formato PDF con la memoria de estadía o residencia (Máx. 20MB).</p>
                </div>

                @if(isset($documentos['Reporte']) && $documentos['Reporte']->ruta_archivo)
                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200/80 flex items-center justify-between">
                        <span class="font-mono text-[10px] text-slate-600 truncate max-w-[150px]">
                            {{ $documentos['Reporte']->nombre_archivo }}
                        </span>
                        <a href="{{ asset('storage/' . $documentos['Reporte']->ruta_archivo) }}" target="_blank" 
                           class="text-[#841B44] hover:underline font-bold text-[10px] flex items-center gap-0.5">
                            <span class="material-icons-round text-xs">visibility</span> Ver PDF
                        </a>
                    </div>
                @endif
            </div>

            <!-- Formulario de Carga -->
            <form action="{{ route('titulacion.guardar-entregable') }}" method="POST" enctype="multipart/form-data" class="pt-2 border-t border-slate-100 space-y-3">
                @csrf
                <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">
                <input type="hidden" name="tipo" value="Reporte">

                <div>
                    <input type="file" name="archivo" accept=".pdf" required 
                           class="block w-full text-[10px] text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-rose-50 file:text-[#841B44] hover:file:bg-rose-100 cursor-pointer">
                </div>

                <button type="submit" class="w-full py-2 bg-[#841B44] hover:bg-[#681535] text-white font-bold rounded-xl transition-colors cursor-pointer flex items-center justify-center gap-1">
                    <span class="material-icons-round text-sm">cloud_upload</span> Subir Reporte
                </button>
            </form>
        </div>

        <!-- ENTREGABLE 2: PRESENTACIÓN EJECUTIVA (PPTX/PDF) -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs flex flex-col justify-between space-y-4">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center shrink-0 border border-amber-100">
                        <span class="material-icons-round text-xl">slideshow</span>
                    </div>
                    <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full border
                        {{ ($documentos['Presentacion']->estatus ?? 'Pendiente') == 'Aprobado' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                        {{ ($documentos['Presentacion']->estatus ?? 'Pendiente') == 'Pendiente' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                        {{ ($documentos['Presentacion']->estatus ?? 'Pendiente') == 'Rechazado' ? 'bg-rose-50 text-rose-700 border-rose-200' : '' }}
                    ">
                        {{ $documentos['Presentacion']->estatus ?? 'Sin Cargar' }}
                    </span>
                </div>

                <div>
                    <h4 class="font-extrabold text-slate-900 text-xs">2. Presentación Ejecutiva</h4>
                    <p class="text-slate-500 text-[11px] mt-0.5">Diapositivas para la defensa institucional del proyecto (.pdf, .pptx - Máx. 30MB).</p>
                </div>

                @if(isset($documentos['Presentacion']) && $documentos['Presentacion']->ruta_archivo)
                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200/80 flex items-center justify-between">
                        <span class="font-mono text-[10px] text-slate-600 truncate max-w-[150px]">
                            {{ $documentos['Presentacion']->nombre_archivo }}
                        </span>
                        <a href="{{ asset('storage/' . $documentos['Presentacion']->ruta_archivo) }}" target="_blank" 
                           class="text-[#841B44] hover:underline font-bold text-[10px] flex items-center gap-0.5">
                            <span class="material-icons-round text-xs">file_download</span> Descargar
                        </a>
                    </div>
                @endif
            </div>

            <!-- Formulario de Carga -->
            <form action="{{ route('titulacion.guardar-entregable') }}" method="POST" enctype="multipart/form-data" class="pt-2 border-t border-slate-100 space-y-3">
                @csrf
                <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">
                <input type="hidden" name="tipo" value="Presentacion">

                <div>
                    <input type="file" name="archivo" accept=".pdf,.pptx,.ppt" required 
                           class="block w-full text-[10px] text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-rose-50 file:text-[#841B44] hover:file:bg-rose-100 cursor-pointer">
                </div>

                <button type="submit" class="w-full py-2 bg-[#841B44] hover:bg-[#681535] text-white font-bold rounded-xl transition-colors cursor-pointer flex items-center justify-center gap-1">
                    <span class="material-icons-round text-sm">cloud_upload</span> Subir Presentación
                </button>
            </form>
        </div>

        <!-- ENTREGABLE 3: ENLACE AL VIDEO DEMOSTRATIVO -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs flex flex-col justify-between space-y-4">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0 border border-blue-100">
                        <span class="material-icons-round text-xl">video_library</span>
                    </div>
                    <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full border
                        {{ ($documentos['Video']->estatus ?? 'Pendiente') == 'Aprobado' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                        {{ ($documentos['Video']->estatus ?? 'Pendiente') == 'Pendiente' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                    ">
                        {{ isset($proyecto->video_url) && $proyecto->video_url ? 'Registrado' : 'Opcional' }}
                    </span>
                </div>

                <div>
                    <h4 class="font-extrabold text-slate-900 text-xs">3. Video Demostrativo</h4>
                    <p class="text-slate-500 text-[11px] mt-0.5">Enlace universal a YouTube, Google Drive o OneDrive con el funcionamiento del prototipo.</p>
                </div>

                @if(isset($proyecto->video_url) && $proyecto->video_url)
                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200/80 flex items-center justify-between">
                        <span class="font-mono text-[10px] text-blue-600 truncate max-w-[150px]">
                            {{ $proyecto->video_url }}
                        </span>
                        <a href="{{ $proyecto->video_url }}" target="_blank" 
                           class="text-[#841B44] hover:underline font-bold text-[10px] flex items-center gap-0.5">
                            <span class="material-icons-round text-xs">open_in_new</span> Probar Link
                        </a>
                    </div>
                @endif
            </div>

            <!-- Formulario de Enlace -->
            <form action="{{ route('titulacion.guardar-video') }}" method="POST" class="pt-2 border-t border-slate-100 space-y-3">
                @csrf
                <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">

                <div>
                    <input type="url" name="video_url" required value="{{ old('video_url', $proyecto->video_url ?? '') }}"
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2 font-medium text-[11px] focus:ring-1 focus:ring-[#841B44]"
                           placeholder="https://youtu.be/... o Drive link">
                </div>

                <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-colors cursor-pointer flex items-center justify-center gap-1">
                    <span class="material-icons-round text-sm">link</span> Guardar Enlace
                </button>
            </form>
        </div>

    </div>

</main>
@endsection
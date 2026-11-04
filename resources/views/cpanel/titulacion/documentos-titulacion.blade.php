@extends('cpanel/plantillaestudiante')
@section('title', 'Documentos de Titulación')

@section('content')
<main class="p-4 md:p-8 space-y-8 max-w-7xl w-full mx-auto text-xs md:text-sm transition-colors duration-200">

    {{-- ENCABEZADO Y BARRA DE PROGRESO --}}
    <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-2xs flex flex-col lg:flex-row lg:items-center justify-between gap-6 transition-colors duration-200">
        <div class="space-y-1.5 max-w-2xl">
            <div class="flex items-center gap-2 text-custom-primary font-black text-[11px] uppercase tracking-widest">
                <span class="material-icons-round text-base">folder_special</span>
                <span>Proceso de Recepción Profesional</span>
            </div>
            <h2 class="text-xl md:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                Expediente Digital de Titulación
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm font-medium leading-relaxed">
                Carga digitalmente tus requisitos institucionales en formato PDF. Cada documento será validado y cotejado minuciosamente por el departamento de Control Escolar.
            </p>
        </div>

        {{-- INDICADOR DE AVANCE TOTAL --}}
        <div class="bg-slate-50 dark:bg-slate-800/60 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 flex items-center gap-5 shrink-0 self-start lg:self-center">
            <div class="w-14 h-14 rounded-2xl bg-custom-primary/10 text-custom-primary font-black flex items-center justify-center text-lg border border-custom-primary/20 shadow-2xs">
                {{ $porcentaje }}%
            </div>
            <div class="space-y-1.5">
                <span class="text-[10px] text-slate-400 dark:text-slate-400 uppercase font-extrabold tracking-wider block">Avance del Expediente</span>
                <p class="font-extrabold text-xs text-slate-800 dark:text-slate-200">
                    <span class="text-custom-primary font-black">{{ $aprobados }}</span> de {{ $totalDocs }} requisitos liberados
                </p>
                <div class="w-40 bg-slate-200 dark:bg-slate-700 h-2 rounded-full overflow-hidden">
                    <div class="bg-custom-primary h-full rounded-full transition-all duration-500 ease-out" style="width: {{ $porcentaje }}%"></div>
                </div>
            </div>
        </div>
    </div>

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
        <div class="bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 p-4 rounded-2xl space-y-1.5 shadow-xs animate-fade-in">
            <div class="flex items-center gap-2 font-black text-xs md:text-sm">
                <span class="material-icons-round text-lg text-rose-600 dark:text-rose-400">error</span>
                <span>Hubo un problema al procesar el archivo:</span>
            </div>
            <p class="pl-7 font-medium text-xs">{{ $errors->first() }}</p>
        </div>
    @endif

    {{-- GRID DE TARJETAS DE DOCUMENTOS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($catalogo as $clave => $item)
            @php
                $doc = $documentosSubidos->get($clave);
                $estatus = $doc->estatus ?? 'Sin_Subir';
            @endphp

            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-6 flex flex-col justify-between space-y-5 transition-all hover:shadow-md hover:border-slate-300 dark:hover:border-slate-700">
                
                {{-- ENCABEZADO DE TARJETA --}}
                <div class="space-y-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800 text-custom-primary border border-slate-200/80 dark:border-slate-700 flex items-center justify-center shrink-0 shadow-2xs">
                            <span class="material-icons-round text-2xl">{{ $item['icono'] }}</span>
                        </div>

                        {{-- BADGE DE ESTATUS --}}
                        @if($estatus === 'Aprobado')
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aprobado
                            </span>
                        @elseif($estatus === 'En_Revision' || $estatus === 'Pendiente')
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> En Revisión
                            </span>
                        @elseif($estatus === 'Rechazado')
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Rechazado
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                Sin Subir
                            </span>
                        @endif
                    </div>

                    <div>
                        <h3 class="font-extrabold text-sm md:text-base text-slate-900 dark:text-white leading-snug">
                            {{ $item['nombre'] }}
                        </h3>
                        <span class="text-[10px] font-mono font-semibold text-slate-400 dark:text-slate-500 block mt-1 uppercase tracking-wider">
                            Clave: {{ $clave }}
                        </span>
                    </div>

                    {{-- DATOS DEL ARCHIVO CARGADO --}}
                    @if($doc)
                        <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-700/60 space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-mono text-slate-600 dark:text-slate-300 truncate max-w-[170px] font-semibold" title="{{ $doc->nombre_archivo }}">
                                    {{ $doc->nombre_archivo }}
                                </span>
                                <span class="font-black text-slate-400 dark:text-slate-500 text-[10px] bg-slate-200/60 dark:bg-slate-700 px-2 py-0.5 rounded-md">
                                    v{{ $doc->version }}
                                </span>
                            </div>

                            <div class="pt-1 border-t border-slate-200/50 dark:border-slate-700/50 flex items-center justify-between">
                                <a href="{{ asset('storage/' . $doc->ruta_archivo) }}" target="_blank"
                                   class="text-xs font-extrabold text-custom-primary hover:underline flex items-center gap-1">
                                    <span class="material-icons-round text-sm">picture_as_pdf</span>
                                    <span>Visualizar Documento</span>
                                </a>
                            </div>
                        </div>

                        {{-- OBSERVACIONES EN CASO DE RECHAZO --}}
                        @if(!empty($doc->observaciones))
                            <div class="p-3.5 rounded-2xl bg-rose-50/80 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 text-xs text-rose-800 dark:text-rose-300 space-y-1">
                                <strong class="flex items-center gap-1 font-extrabold">
                                    <span class="material-icons-round text-sm text-rose-600">error_outline</span>
                                    <span>Observaciones de Control Escolar:</span>
                                </strong>
                                <p class="leading-relaxed font-medium pl-5">{{ $doc->observaciones }}</p>
                            </div>
                        @endif
                    @endif
                </div>

                {{-- BOTÓN DE SUBIDA / REEMPLAZO --}}
                <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                    @if($estatus === 'Aprobado')
                        <button type="button" disabled 
                                class="w-full py-3 bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 font-bold text-xs rounded-2xl cursor-not-allowed flex items-center justify-center gap-2">
                            <span class="material-icons-round text-base text-emerald-500">task_alt</span>
                            <span>Documento Liberado</span>
                        </button>
                    @else
                        <button type="button" onclick="abrirModalSubida('{{ $clave }}', '{{ $item['nombre'] }}')"
                                class="w-full py-3 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold text-xs rounded-2xl shadow-xs transition-all active:scale-98 cursor-pointer flex items-center justify-center gap-2">
                            <span class="material-icons-round text-base">upload_file</span>
                            <span>{{ $doc ? 'Reemplazar Archivo' : 'Adjuntar Documento' }}</span>
                        </button>
                    @endif
                </div>

            </div>
        @endforeach
    </div>

</main>

{{-- MODAL FLOTANTE PARA CARGA DE PDF --}}
<div id="modalSubir" class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden animate-fade-in">
    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-3xl shadow-2xl overflow-hidden p-6 md:p-8 space-y-6 border border-slate-100 dark:border-slate-800 transition-colors">
        
        <div class="flex justify-between items-start border-b border-slate-100 dark:border-slate-800 pb-4">
            <div>
                <span class="text-[10px] font-black uppercase tracking-widest text-custom-primary">Carga de Documento</span>
                <h3 id="modalTituloDoc" class="font-black text-slate-900 dark:text-white text-base leading-snug mt-0.5"></h3>
            </div>
            <button onclick="cerrarModalSubida()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer p-1 rounded-lg transition-colors">
                <span class="material-icons-round text-2xl">close</span>
            </button>
        </div>

        <form action="{{ route('alumno.titulacion.documentos.subir') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="tipo_documento" id="inputTipoDoc">

            {{-- ÁREA DRAG & DROP Y SELECCIÓN DE ARCHIVO --}}
            <div id="dropZone" 
                 class="border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-custom-primary dark:hover:border-custom-primary bg-slate-50/50 dark:bg-slate-800/40 rounded-2xl p-6 text-center transition-all cursor-pointer space-y-2 group"
                 onclick="document.getElementById('inputArchivo').click()">
                
                <div class="w-14 h-14 rounded-2xl bg-custom-primary/10 text-custom-primary flex items-center justify-center mx-auto group-hover:scale-110 transition-transform">
                    <span class="material-icons-round text-3xl">cloud_upload</span>
                </div>
                
                <div>
                    <label class="font-extrabold text-xs text-slate-800 dark:text-slate-200 cursor-pointer block">
                        Haz clic o arrastra tu archivo PDF aquí
                    </label>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1 font-medium">Solo archivos con formato PDF (Máx. 5 MB)</p>
                </div>
                
                <input type="file" name="archivo" id="inputArchivo" accept="application/pdf" required class="hidden" onchange="actualizarNombreArchivo(this)">
                
                {{-- PREVISUALIZACIÓN DEL ARCHIVO SELECCIONADO --}}
                <div id="archivoSeleccionado" class="hidden mt-3 p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-between text-xs font-mono text-slate-700 dark:text-slate-200 font-bold animate-fade-in">
                    <div class="flex items-center gap-2 truncate">
                        <span class="material-icons-round text-custom-primary text-base">picture_as_pdf</span>
                        <span id="nombreArchivoTexto" class="truncate"></span>
                    </div>
                    <span class="material-icons-round text-emerald-500 text-base shrink-0">check_circle</span>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="cerrarModalSubida()" 
                        class="px-5 py-3 rounded-2xl font-bold text-xs text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-6 py-3 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold text-xs rounded-2xl shadow-md active:scale-98 transition-all cursor-pointer flex items-center gap-2">
                    <span class="material-icons-round text-base">send</span>
                    <span>Enviar a Revisión</span>
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function abrirModalSubida(tipo, nombre) {
        document.getElementById('inputTipoDoc').value = tipo;
        document.getElementById('modalTituloDoc').innerText = nombre;
        document.getElementById('inputArchivo').value = '';
        document.getElementById('archivoSeleccionado').classList.add('hidden');
        document.getElementById('modalSubir').classList.remove('hidden');
    }

    function cerrarModalSubida() {
        document.getElementById('modalSubir').classList.add('hidden');
    }

    function actualizarNombreArchivo(input) {
        const contenedor = document.getElementById('archivoSeleccionado');
        const texto = document.getElementById('nombreArchivoTexto');
        if (input.files && input.files[0]) {
            texto.innerText = input.files[0].name;
            contenedor.classList.remove('hidden');
        } else {
            contenedor.classList.add('hidden');
        }
    }

    // SOPORTE PARA DRAG & DROP EN EL ÁREA DEL MODAL
    const dropZone = document.getElementById('dropZone');

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.add('border-custom-primary', 'bg-custom-primary/5');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove('border-custom-primary', 'bg-custom-primary/5');
        }, false);
    });

    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        const fileInput = document.getElementById('inputArchivo');

        if (files && files.length > 0) {
            fileInput.files = files;
            actualizarNombreArchivo(fileInput);
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            cerrarModalSubida();
        }
    });
</script>
@endsection
@extends('cpanel/plantillaestudiante')
@section('title', 'Documentos de Titulación')

@section('content')
<main class="p-4 md:p-8 space-y-8 max-w-7xl w-full mx-auto text-sm md:text-base transition-colors duration-200">

    <!-- ENCABEZADO Y BARRA DE PROGRESO -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 text-custom-primary text-xs font-black uppercase tracking-widest mb-1">
                <span class="material-icons-round text-base">folder_special</span>
                <span>Proceso de Recepción Profesional</span>
            </div>
            <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-slate-100 flex items-center gap-2">
                Expediente Digital de Titulación
            </h2>
            <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Carga digitalmente tus requisitos en formato PDF. Cada documento será cotejado por el departamento de Control Escolar.
            </p>
        </div>

        <!-- Indicador de Avance Total -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs flex items-center gap-5 shrink-0">
            <div class="w-14 h-14 rounded-2xl bg-custom-light text-custom-primary font-black flex items-center justify-center text-base border border-custom-primary/30">
                {{ $porcentaje }}%
            </div>
            <div class="space-y-1">
                <span class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-black tracking-wider block">Avance del Expediente</span>
                <p class="font-extrabold text-xs text-slate-800 dark:text-slate-200">
                    <span class="text-custom-primary font-black">{{ $aprobados }}</span> de {{ $totalDocs }} requisitos liberados
                </p>
                <div class="w-36 bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                    <div class="bg-custom-primary h-full rounded-full transition-all duration-500" style="width: {{ $porcentaje }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ALERTAS -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 p-4 rounded-2xl text-xs md:text-sm font-semibold flex items-center justify-between shadow-3xs">
            <div class="flex items-center gap-2.5">
                <span class="material-icons-round text-lg text-emerald-600">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 cursor-pointer">
                <span class="material-icons-round text-base">close</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900 text-rose-800 dark:text-rose-200 p-4 rounded-2xl text-xs space-y-1 shadow-3xs">
            <div class="flex items-center gap-2 font-bold">
                <span class="material-icons-round text-base text-rose-600">error</span>
                <span>Hubo un problema al procesar el archivo:</span>
            </div>
            <p class="pl-6 font-medium">{{ $errors->first() }}</p>
        </div>
    @endif

    <!-- GRID DE TARJETAS DE DOCUMENTOS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($catalogo as $clave => $item)
            @php
                $doc = $documentosSubidos->get($clave);
                $estatus = $doc->estatus ?? 'Sin_Subir';
            @endphp

            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs p-5 md:p-6 flex flex-col justify-between space-y-5 transition-all hover:shadow-md">
                
                <!-- Encabezado de Tarjeta -->
                <div class="space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800 text-custom-primary border border-slate-200 dark:border-slate-700/80 flex items-center justify-center shrink-0 shadow-3xs">
                            <span class="material-icons-round text-2xl">{{ $item['icono'] }}</span>
                        </div>

                        <!-- Pill de Estatus -->
                        @if($estatus === 'Aprobado')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aprobado
                            </span>
                        @elseif($estatus === 'En_Revision' || $estatus === 'Pendiente')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> En Revisión
                            </span>
                        @elseif($estatus === 'Rechazado')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Rechazado
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-500 border border-slate-200 dark:border-slate-700">
                                Sin Subir
                            </span>
                        @endif
                    </div>

                    <div>
                        <h4 class="font-extrabold text-sm text-slate-900 dark:text-slate-100 leading-snug">
                            {{ $item['nombre'] }}
                        </h4>
                        <span class="text-[10px] font-mono text-slate-400 block mt-0.5">Clave: {{ $clave }}</span>
                    </div>

                    <!-- Datos del Archivo Cargado si existe -->
                    @if($doc)
                        <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-700/60 space-y-1">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-mono text-slate-500 dark:text-slate-400 truncate max-w-[170px]" title="{{ $doc->nombre_archivo }}">
                                    {{ $doc->nombre_archivo }}
                                </span>
                                <span class="font-bold text-slate-400 text-[10px]">v{{ $doc->version }}</span>
                            </div>

                            <div class="flex items-center gap-2 pt-1">
                                <a href="{{ asset('storage/' . $doc->ruta_archivo) }}" target="_blank"
                                   class="text-[11px] font-bold text-custom-primary hover:underline flex items-center gap-1">
                                    <span class="material-icons-round text-xs">visibility</span> Ver PDF
                                </a>
                            </div>
                        </div>

                        <!-- Observaciones en caso de Rechazo o Nota -->
                        @if(!empty($doc->observaciones))
                            <div class="p-3 rounded-2xl bg-rose-50/70 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 text-[11px] text-rose-800 dark:text-rose-300">
                                <strong class="block font-bold mb-0.5">Observaciones de Control Escolar:</strong>
                                <p class="leading-relaxed">{{ $doc->observaciones }}</p>
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Botón de Subida / Reemplazo -->
                <div class="pt-2 border-t border-slate-100 dark:border-slate-800">
                    @if($estatus === 'Aprobado')
                        <button type="button" disabled 
                                class="w-full py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-400 font-bold text-xs rounded-2xl cursor-not-allowed flex items-center justify-center gap-1.5">
                            <span class="material-icons-round text-base text-emerald-500">task_alt</span>
                            <span>Documento Liberado</span>
                        </button>
                    @else
                        <button type="button" onclick="abrirModalSubida('{{ $clave }}', '{{ $item['nombre'] }}')"
                                class="w-full py-2.5 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold text-xs rounded-2xl shadow-xs transition-all cursor-pointer flex items-center justify-center gap-1.5">
                            <span class="material-icons-round text-base">upload_file</span>
                            <span>{{ $doc ? 'Reemplazar Archivo' : 'Adjuntar Documento' }}</span>
                        </button>
                    @endif
                </div>

            </div>
        @endforeach
    </div>

</main>

<!-- MODAL FLOTANTE PARA CARGA DE PDF -->
<div id="modalSubir" class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-3xl shadow-2xl p-6 md:p-8 space-y-6 border border-slate-100 dark:border-slate-800">
        
        <div class="flex justify-between items-start border-b border-slate-100 dark:border-slate-800 pb-4">
            <div>
                <span class="text-[10px] font-black uppercase tracking-widest text-custom-primary">Subir Documento</span>
                <h3 id="modalTituloDoc" class="font-black text-slate-900 dark:text-slate-100 text-base leading-snug mt-0.5"></h3>
            </div>
            <button onclick="cerrarModalSubida()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                <span class="material-icons-round text-2xl">close</span>
            </button>
        </div>

        <form action="{{ route('alumno.titulacion.documentos.subir') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <input type="hidden" name="tipo_documento" id="inputTipoDoc">

            <!-- Área de Arrastre o Selección -->
            <div class="border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-2xl p-6 text-center hover:border-custom-primary transition-colors">
                <span class="material-icons-round text-4xl text-custom-primary mb-2 block">cloud_upload</span>
                <label for="inputArchivo" class="font-extrabold text-xs text-slate-800 dark:text-slate-200 cursor-pointer hover:underline block">
                    Haz clic aquí para seleccionar tu PDF
                </label>
                <p class="text-[11px] text-slate-400 mt-1">Solo formato .pdf (Máx. 5 MB)</p>
                
                <input type="file" name="archivo" id="inputArchivo" accept="application/pdf" required class="hidden" onchange="actualizarNombreArchivo(this)">
                
                <div id="archivoSeleccionado" class="hidden mt-3 p-2.5 bg-slate-50 dark:bg-slate-800 rounded-xl font-mono text-xs text-slate-700 dark:text-slate-300 font-bold truncate"></div>
            </div>

            <div class="flex justify-end gap-2.5 pt-2">
                <button type="button" onclick="cerrarModalSubida()" 
                        class="px-5 py-2.5 rounded-xl font-bold text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-6 py-2.5 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold text-xs rounded-xl shadow-xs transition-all cursor-pointer flex items-center gap-1.5">
                    <span class="material-icons-round text-sm">send</span>
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
        const caja = document.getElementById('archivoSeleccionado');
        if (input.files && input.files[0]) {
            caja.innerText = input.files[0].name;
            caja.classList.remove('hidden');
        } else {
            caja.classList.add('hidden');
        }
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            cerrarModalSubida();
        }
    });
</script>
@endsection

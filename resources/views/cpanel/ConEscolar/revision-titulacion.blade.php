@extends('cpanel/plantillaCE')
@section('title', 'Revisión de Expedientes de Titulación')

@section('content')
<main class="h-[calc(100vh-4.5rem)] flex flex-col p-4 md:p-6 space-y-4 max-w-7xl mx-auto w-full transition-colors duration-200">

    <!-- ENCABEZADO -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 shrink-0 pb-2 border-b border-slate-200 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2 text-custom-primary text-xs font-black uppercase tracking-widest">
                <span class="material-icons-round text-base">verified_user</span>
                <span>Control Escolar • Servicios Escolares</span>
            </div>
            <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-slate-100">
                Cotejo y Validación de Documentos de Titulación
            </h2>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 shadow-3xs">
                <span class="material-icons-round text-base text-emerald-600">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
    </div>

    <!-- PANEL PRINCIPAL SPLIT -->
    <div class="flex-1 grid grid-cols-1 lg:grid-cols-12 gap-5 min-h-0 overflow-hidden">
        
        <!-- 1. COLUMNA IZQUIERDA: DIRECTORIO DE ALUMNOS (4 Cols) -->
        <div class="lg:col-span-4 flex flex-col bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden min-h-0">
            
            <!-- Buscador -->
            <div class="p-3.5 bg-slate-50/80 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 shrink-0 space-y-2.5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black uppercase text-slate-500 dark:text-slate-400 tracking-wider">
                        Expedientes Activos
                    </span>
                    <span class="px-2 py-0.5 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-[10px] font-black font-mono">
                        {{ $alumnos->count() }}
                    </span>
                </div>

                <form action="{{ route('ce.titulacion.index') }}" method="GET">
                    <div class="relative">
                        <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por matrícula..." 
                               class="w-full bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-200 dark:border-slate-700 rounded-xl py-2 pl-8 pr-3 text-xs focus:ring-2 focus:ring-custom-primary focus:outline-hidden">
                        <span class="material-icons-round text-sm text-slate-400 absolute left-2.5 top-2.5">search</span>
                    </div>
                </form>
            </div>

            <!-- Lista de Alumnos con Scroll -->
            <div class="flex-1 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800/80">
                @forelse($alumnos as $al)
                    @php
                        $isSelected = $alumnoSeleccionado && $alumnoSeleccionado->id === $al->id;
                    @endphp
                    <a href="{{ route('ce.titulacion.index', ['alumno_id' => $al->id]) }}"
                       class="block p-4 transition-all hover:bg-slate-50 dark:hover:bg-slate-800/50 {{ $isSelected ? 'bg-slate-100/80 dark:bg-slate-800/90 border-l-4 border-custom-primary' : '' }}">
                        
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <span class="font-mono text-xs font-black text-custom-primary">{{ $al->matricula }}</span>
                            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase">
                                Grupo "{{ $al->grupo }}"
                            </span>
                        </div>

                        <h4 class="font-extrabold text-xs text-slate-900 dark:text-slate-100 leading-snug">
                            {{ $al->nombre_completo }}
                        </h4>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 truncate mt-0.5">
                            {{ $al->especialidad }}
                        </p>

                        <!-- Indicadores de avance de documentos -->
                        <div class="flex items-center gap-2 mt-2 pt-2 border-t border-slate-100 dark:border-slate-800 text-[10px] font-bold">
                            <span class="text-emerald-600 dark:text-emerald-400 flex items-center gap-0.5">
                                <span class="material-icons-round text-xs">check_circle</span> {{ $al->docs_aprobados }}
                            </span>
                            <span class="text-amber-600 dark:text-amber-400 flex items-center gap-0.5">
                                <span class="material-icons-round text-xs">pending</span> {{ $al->docs_pendientes }}
                            </span>
                            <span class="text-rose-600 dark:text-rose-400 flex items-center gap-0.5">
                                <span class="material-icons-round text-xs">cancel</span> {{ $al->docs_rechazados }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-slate-400 text-xs space-y-2">
                        <span class="material-icons-round text-4xl text-slate-300 dark:text-slate-700">folder_off</span>
                        <p>No se encontraron expedientes con documentos subidos.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- 2. COLUMNA DERECHA: EXPEDIENTE, VISOR Y REVISIÓN (8 Cols) -->
        <div class="lg:col-span-8 flex flex-col bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden min-h-0">
            @if($alumnoSeleccionado)

                <!-- Cabecera de Alumno y Selector de Documentos -->
                <div class="p-4 bg-slate-50/90 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-700/80 shrink-0 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <span class="text-[10px] font-mono font-bold text-custom-primary uppercase tracking-wider block">
                                Matrícula: {{ $alumnoSeleccionado->matricula }}
                            </span>
                            <h3 class="font-black text-sm md:text-base text-slate-900 dark:text-slate-100">
                                {{ $alumnoSeleccionado->nombre_completo }}
                            </h3>
                        </div>
                        <span class="text-xs text-slate-500 font-bold bg-white dark:bg-slate-800 px-3 py-1 rounded-xl border border-slate-200 dark:border-slate-700 shrink-0">
                            {{ $alumnoSeleccionado->especialidad }} ({{ $alumnoSeleccionado->grupo }})
                        </span>
                    </div>

                    <!-- Pestañas horizontales de los documentos del alumno -->
                    <div class="flex items-center gap-2 overflow-x-auto pb-1">
                        @foreach($documentosAlumno as $d)
                            @php
                                $isDocActivo = $docActivo && $docActivo->id === $d->id;
                            @endphp
                            <a href="{{ route('ce.titulacion.index', ['alumno_id' => $alumnoSeleccionado->id, 'doc_id' => $d->id]) }}"
                               class="shrink-0 px-3 py-1.5 rounded-xl text-[11px] font-extrabold transition-all border flex items-center gap-1.5
                                    {{ $isDocActivo 
                                        ? 'bg-custom-primary text-white border-custom-primary shadow-xs' 
                                        : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-custom-primary' }}">
                                <span>{{ $catalogo[$d->tipo_documento] ?? $d->tipo_documento }}</span>
                                
                                @if($d->estatus === 'Aprobado')
                                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                @elseif($d->estatus === 'Rechazado')
                                    <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                                @else
                                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                @if($docActivo)
                    <!-- VISOR PDF EMBEBIDO -->
                    <div class="flex-1 bg-slate-100 dark:bg-slate-950/50 relative overflow-hidden min-h-[280px]">
                        @if(!empty($docActivo->ruta_archivo))
                            <iframe src="{{ asset('storage/' . $docActivo->ruta_archivo) }}#toolbar=1&navpanes=0" 
                                    class="w-full h-full border-0" 
                                    title="Documento PDF">
                            </iframe>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">
                                Archivo no disponible en el almacenamiento.
                            </div>
                        @endif
                    </div>

                    <!-- FORMULARIO DE EVALUACIÓN Y DICTAMEN -->
                    <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 shrink-0">
                        <form action="{{ route('ce.titulacion.evaluar', $docActivo->id) }}" method="POST" class="space-y-3">
                            @csrf

                            <div>
                                <label class="block font-bold text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">
                                    Observaciones para el Alumno (Visible en su portal en caso de rechazo o nota)
                                </label>
                                <textarea name="observaciones" rows="2" 
                                          class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs focus:ring-2 focus:ring-custom-primary focus:outline-hidden resize-none transition-all placeholder:text-slate-400"
                                          placeholder="Indica si el documento presenta tachaduras, falta sello, o si se requiere volver a digitalizar...">{{ old('observaciones', $docActivo->observaciones) }}</textarea>
                            </div>

                            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-0.5">
                                <div class="text-[11px] text-slate-400">
                                    Estado actual: 
                                    <strong class="uppercase font-black text-slate-700 dark:text-slate-200">
                                        {{ str_replace('_', ' ', $docActivo->estatus) }} (v{{ $docActivo->version }})
                                    </strong>
                                </div>

                                <div class="flex items-center gap-2 w-full sm:w-auto">
                                    <button type="submit" name="estatus" value="Rechazado" 
                                            class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl font-bold text-xs text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/50 hover:bg-rose-100 dark:hover:bg-rose-900/60 border border-rose-200 dark:border-rose-900/60 transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                                        <span class="material-icons-round text-base">cancel</span>
                                        <span>Rechazar Documento</span>
                                    </button>

                                    <button type="submit" name="estatus" value="Aprobado" 
                                            class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl font-bold text-xs text-white bg-emerald-600 hover:bg-emerald-700 shadow-xs transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                                        <span class="material-icons-round text-base">verified</span>
                                        <span>Aprobar Documento</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-slate-400 p-8 text-center space-y-2">
                        <span class="material-icons-round text-3xl">picture_as_pdf</span>
                        <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Sin documentos para revisar</p>
                    </div>
                @endif

            @else
                <div class="flex-1 flex flex-col items-center justify-center text-slate-400 p-8 text-center space-y-2">
                    <span class="material-icons-round text-3xl text-slate-300 dark:text-slate-600">badge</span>
                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Ningún estudiante seleccionado</p>
                    <p class="text-xs text-slate-400 max-w-xs">Selecciona un alumno del directorio izquierdo para cotejar sus documentos de titulación.</p>
                </div>
            @endif
        </div>

    </div>

</main>
@endsection
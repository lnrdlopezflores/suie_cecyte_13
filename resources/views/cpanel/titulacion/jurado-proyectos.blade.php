@extends('cpanel/plantilladocente')
@section('title', 'Evaluación de Proyectos - Sínodo')

@section('content')
<main class="h-[calc(100vh-4.5rem)] flex flex-col p-3 md:p-5 space-y-3 max-w-7xl mx-auto w-full transition-colors duration-200">

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 px-4 py-2 rounded-2xl text-xs font-bold flex items-center justify-between shadow-3xs shrink-0">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-base text-emerald-600">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 cursor-pointer">
                <span class="material-icons-round text-sm">close</span>
            </button>
        </div>
    @endif

    <!-- PANEL PRINCIPAL SPLIT -->
    <div class="flex-1 grid grid-cols-1 lg:grid-cols-12 gap-4 min-h-0 overflow-hidden">
        
        <!-- COLUMNA IZQUIERDA: LISTA COMPACTA DE PROYECTOS (4 Cols) -->
        <div class="lg:col-span-4 flex flex-col bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden min-h-0">
            <div class="p-3.5 bg-slate-50/80 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center shrink-0">
                <span class="text-xs font-black uppercase text-slate-500 dark:text-slate-400 tracking-wider flex items-center gap-1.5">
                    <span class="material-icons-round text-sm text-custom-primary">folder_shared</span>
                    Proyectos Asignados
                </span>
                <span class="px-2 py-0.5 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-[10px] font-black font-mono">
                    {{ $proyectos->count() }}
                </span>
            </div>

            <!-- Listado con scroll -->
            <div class="flex-1 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800/80">
                @forelse($proyectos as $item)
                    @php
                        $isActive = $proyectoSeleccionado && $proyectoSeleccionado->id === $item->id;
                    @endphp
                    <a href="{{ route('docente.jurado.index', ['proyecto_id' => $item->id]) }}" 
                    class="block p-4 transition-all hover:bg-slate-50 dark:hover:bg-slate-800/50 {{ $isActive ? 'bg-slate-100/80 dark:bg-slate-800/90 border-l-4 border-custom-primary' : '' }}">
                        
                        <div class="flex items-center justify-between gap-2 mb-1.5">
                            <span class="text-[10px] font-mono font-bold text-slate-400 dark:text-slate-500">
                                Folio #{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                            </span>

                            @if($item->mi_voto === 'Aprobado')
                                <span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 font-extrabold text-[9px] uppercase border border-emerald-200 dark:border-emerald-800">
                                    Aprobado
                                </span>
                            @elseif($item->mi_voto === 'Rechazado')
                                <span class="px-2 py-0.5 rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 font-extrabold text-[9px] uppercase border border-rose-200 dark:border-rose-800">
                                    Rechazado
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 font-extrabold text-[9px] uppercase border border-amber-200 dark:border-amber-800">
                                    Pendiente
                                </span>
                            @endif
                        </div>

                        <h4 class="font-extrabold text-xs text-slate-900 dark:text-slate-100 leading-snug line-clamp-2">
                            {{ $item->titulo }}
                        </h4>

                        <div class="flex items-start gap-1.5 text-[11px] text-slate-500 dark:text-slate-400 mt-2">
                            <span class="material-icons-round text-sm text-slate-400 shrink-0">groups</span>
                            <span class="line-clamp-2 leading-tight font-medium">{{ $item->integrantes }}</span>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-slate-400 dark:text-slate-500 space-y-2">
                        <span class="material-icons-round text-4xl text-slate-300 dark:text-slate-700">folder_off</span>
                        <p class="text-xs">No tienes proyectos asignados como jurado.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- COLUMNA DERECHA: RECUADRO CONSOLIDADO + PDF + DICTAMEN (8 Cols) -->
        <div class="lg:col-span-8 flex flex-col bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden min-h-0">
            @if($proyectoSeleccionado)
                
                <!-- RECUADRO ÚNICO CONSOLIDADO (Título, Integrantes y Grupo) -->
                <div class="p-4 bg-slate-50/90 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-700/80 shrink-0 space-y-2">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                                    Folio #{{ str_pad($proyectoSeleccionado->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-custom-light text-custom-primary border border-custom-primary/20">
                                    {{ $proyectoSeleccionado->especialidad }} (Grupo "{{ $proyectoSeleccionado->grupo }}")
                                </span>
                            </div>
                            <h3 class="font-black text-sm md:text-base text-slate-900 dark:text-slate-100 leading-snug">
                                {{ $proyectoSeleccionado->titulo }}
                            </h3>
                        </div>

                        <!-- Botón Pantalla Completa PDF -->
                        @if(!empty($proyectoSeleccionado->documento_url))
                            <a href="{{ asset('storage/' . $proyectoSeleccionado->documento_url) }}" target="_blank"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:text-custom-primary transition-colors shadow-3xs shrink-0 self-start">
                                <span class="material-icons-round text-sm">open_in_new</span> Pantalla Completa
                            </a>
                        @endif
                    </div>

                    <!-- Fila de Integrantes / Sustentantes -->
                    <div class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-300 pt-1 border-t border-slate-200/60 dark:border-slate-700/60">
                        <span class="material-icons-round text-sm text-custom-primary">groups</span>
                        <span class="font-bold text-[11px] uppercase tracking-wider text-slate-400">Integrantes:</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $proyectoSeleccionado->integrantes }}</span>
                    </div>
                </div>

                <!-- VISOR PDF EMBEBIDO -->
                <div class="flex-1 bg-slate-100 dark:bg-slate-950/50 relative overflow-hidden min-h-[250px]">
                    @if(!empty($proyectoSeleccionado->documento_url))
                        <iframe src="{{ asset('storage/' . $proyectoSeleccionado->documento_url) }}#toolbar=1&navpanes=0" 
                                class="w-full h-full border-0" 
                                title="Reporte Técnico PDF">
                        </iframe>
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 text-xs space-y-2 p-6">
                            <span class="material-icons-round text-4xl text-slate-300 dark:text-slate-700">picture_as_pdf</span>
                            <p>El estudiante aún no ha cargado el documento PDF de este proyecto.</p>
                        </div>
                    @endif
                </div>

                <!-- SECCIÓN INFERIOR: COMENTARIOS Y BOTONES -->
                <div class="p-3.5 border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 shrink-0">
                    <form action="{{ route('docente.jurado.dictaminar', $proyectoSeleccionado->id) }}" method="POST" class="space-y-2.5">
                        @csrf
                        
                        <div>
                            <label class="block font-bold text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">
                                Observaciones y Retroalimentación
                            </label>
                            <textarea name="comentarios" rows="2" 
                                      class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 text-xs focus:ring-2 focus:ring-custom-primary focus:outline-hidden resize-none transition-all placeholder:text-slate-400"
                                      placeholder="Escribe aquí las observaciones técnicas o correcciones para el equipo...">{{ old('comentarios', $proyectoSeleccionado->mis_comentarios) }}</textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-0.5">
                            <span class="text-[11px] text-slate-400 dark:text-slate-500">
                                Tu dictamen actual: 
                                <strong class="text-slate-700 dark:text-slate-200 uppercase">{{ $proyectoSeleccionado->mi_voto ?? 'Sin emitir' }}</strong>
                            </span>

                            <div class="flex items-center gap-2 w-full sm:w-auto">
                                <button type="submit" name="voto" value="Rechazado" 
                                        class="flex-1 sm:flex-none px-4 py-2 rounded-xl font-bold text-xs text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/50 hover:bg-rose-100 dark:hover:bg-rose-900/60 border border-rose-200 dark:border-rose-900/60 transition-colors flex items-center justify-center gap-1 cursor-pointer">
                                    <span class="material-icons-round text-sm">cancel</span>
                                    <span>Rechazar</span>
                                </button>

                                <button type="submit" name="voto" value="Aprobado" 
                                        class="flex-1 sm:flex-none px-5 py-2 rounded-xl font-bold text-xs text-white bg-emerald-600 hover:bg-emerald-700 shadow-xs transition-colors flex items-center justify-center gap-1 cursor-pointer">
                                    <span class="material-icons-round text-sm">check_circle</span>
                                    <span>Aprobar</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            @else
                <div class="flex-1 flex flex-col items-center justify-center text-slate-400 p-8 text-center space-y-2">
                    <span class="material-icons-round text-3xl text-slate-300 dark:text-slate-600">rate_review</span>
                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Ningún proyecto seleccionado</p>
                    <p class="text-xs text-slate-400 max-w-xs">Elige un proyecto de la lista izquierda para revisar el documento y emitir tu voto.</p>
                </div>
            @endif
        </div>

    </div>

</main>
@endsection
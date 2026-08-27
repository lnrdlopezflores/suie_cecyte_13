@extends('cpanel/plantilladocente')
@section('title', 'Proyectos Asesorados y Sínodos')
@section('content')
<main class="flex-1 max-w-6xl w-full mx-auto p-4 md:p-6 space-y-6 text-xs">

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 p-4 rounded-2xl font-bold flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-base">check_circle</span>
                <p>{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-100 cursor-pointer">
                <span class="material-icons-round text-sm">close</span>
            </button>
        </div>
    @endif

    <!-- ENCABEZADO CON BOTÓN DE REGRESAR -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-xs border border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row justify-between sm:items-center gap-4 transition-colors duration-200">
        <div>
            <div class="flex items-center gap-2 text-slate-400 dark:text-slate-500 font-bold mb-1">
                <a href="{{ url('docente/index') }}" class="hover:text-[#841B44] dark:hover:text-rose-400 transition-colors flex items-center gap-0.5">
                    <span class="material-icons-round text-sm">arrow_back</span> Panel de Inicio
                </a>
                <span>/</span>
                <span class="text-slate-600 dark:text-slate-300">Proyectos de Titulación</span>
            </div>
            <h2 class="text-base font-extrabold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                <span class="material-icons-round text-[#841B44] dark:text-rose-400">supervisor_account</span> Asesoría y Jurado de Titulación
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-[11px] mt-0.5">Supervisa los 3 entregables técnicos, libera el proyecto para exposición y emite tu voto como sínodo.</p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <!-- BOTÓN PARA REGRESAR AL DASHBOARD -->
            

            <div class="bg-slate-50 dark:bg-slate-800/80 px-3.5 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 text-center">
                <span class="text-[10px] text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider block">Proyectos Asignados</span>
                <span class="font-extrabold text-slate-800 dark:text-slate-100 text-sm">{{ $proyectos->count() }} {{ Str::plural('Proyecto', $proyectos->count()) }}</span>
            </div>
        </div>
    </div>

    <!-- LISTADO DE PROYECTOS -->
    <div class="space-y-6">
        @forelse($proyectos as $item)
            @php
                $proyecto = $item['proyecto'];
                $integrantes = $item['integrantes'];
                $jurados = $item['jurados'];
                $votosAprobados = $item['votosAprobados'];
                $esAsesor = $item['esAsesor'];
                $miJurado = $item['miJurado'];
            @endphp

            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xs overflow-hidden p-6 space-y-5 transition-colors duration-200">
                
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 border-b border-slate-100 dark:border-slate-800 pb-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 bg-rose-50 dark:bg-rose-950/60 border border-rose-100 dark:border-rose-900 text-[#841B44] dark:text-rose-300 text-[10px] font-bold rounded-md uppercase">
                                {{ $proyecto->modalidad ?? 'Proyecto de Titulación' }}
                            </span>
                            <span class="text-slate-400 dark:text-slate-400 font-medium text-[11px]">
                                Especialidad: <strong class="text-slate-700 dark:text-slate-200">{{ $proyecto->especialidad_historica ?? 'General' }}</strong>
                            </span>
                        </div>
                        <h3 class="text-base font-black text-slate-900 dark:text-slate-100 leading-snug pt-1">
                            {{ $proyecto->titulo }}
                        </h3>
                    </div>

                    <!-- BADGES DE ESTATUS -->
                    <div class="flex flex-col items-end gap-1 shrink-0">
                        @if($proyecto->estatus == 'Liberado_Exposicion')
                            <span class="px-3 py-1 text-[10px] font-bold rounded-full border bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-900 flex items-center gap-1">
                                <span class="material-icons-round text-xs">record_voice_over</span> Liberado para Exposición
                            </span>
                        @elseif($proyecto->estatus == 'Aprobado')
                            <span class="px-3 py-1 text-[10px] font-bold rounded-full border bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-900 flex items-center gap-1">
                                <span class="material-icons-round text-xs">verified</span> Aprobado por Jurado ({{ $votosAprobados }}/3)
                            </span>
                        @elseif($proyecto->estatus == 'Rechazado')
                            <span class="px-3 py-1 text-[10px] font-bold rounded-full border bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-900">
                                Dictamen: Rechazado
                            </span>
                        @else
                            <span class="px-3 py-1 text-[10px] font-bold rounded-full border bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-900">
                                En Revisión Técnica
                            </span>
                        @endif
                    </div>
                </div>

                @if($proyecto->resumen)
                    <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-200/60 dark:border-slate-700/60 text-slate-600 dark:text-slate-300 leading-relaxed text-[11px]">
                        <strong class="text-slate-700 dark:text-slate-200 block mb-0.5">Resumen del Protocolo:</strong>
                        {{ $proyecto->resumen }}
                    </div>
                @endif

                <!-- PANEL DEL SÍNODO CALIFICADOR (3 JURADOS) -->
                <div class="bg-slate-50 dark:bg-slate-800/60 p-4 rounded-xl border border-slate-200/80 dark:border-slate-700/80 space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="font-extrabold text-slate-900 dark:text-slate-100 flex items-center gap-1.5 text-xs">
                            <span class="material-icons-round text-[#841B44] dark:text-rose-400 text-sm">how_to_vote</span> Evaluación del Sínodo (Mínimo 2 votos aprobatorios)
                        </h4>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $votosAprobados >= 2 ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800' : 'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-300 dark:border-amber-800' }}">
                            {{ $votosAprobados }} de 3 votos aprobatorios
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @forelse($jurados as $jurado)
                            @php
                                $nomJ = $jurado->nombre;
                                $patJ = $jurado->apellido_paterno;
                                try {
                                    if (is_string($nomJ) && (str_starts_with($nomJ, 'ey') || strlen($nomJ) > 50)) $nomJ = decrypt($nomJ);
                                    if (is_string($patJ) && (str_starts_with($patJ, 'ey') || strlen($patJ) > 50)) $patJ = decrypt($patJ);
                                } catch (\Throwable $e) {}
                            @endphp
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-xl border border-slate-200 dark:border-slate-800 shadow-3xs space-y-1">
                                <div class="flex justify-between items-center">
                                    <span class="text-[9px] uppercase font-bold text-slate-400 dark:text-slate-400">{{ $jurado->cargo }}</span>
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-sm {{ $jurado->voto == 'Aprobado' ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300' : ($jurado->voto == 'Rechazado' ? 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300') }}">
                                        {{ $jurado->voto }}
                                    </span>
                                </div>
                                <p class="font-bold text-slate-800 dark:text-slate-200 text-[11px] truncate">Prof. {{ $patJ }} {{ $nomJ }}</p>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-2 text-slate-400 dark:text-slate-500 italic text-[11px]">
                                Los 3 jurados aún no han sido asignados al proyecto.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                    <!-- Integrantes del Equipo -->
                    <div class="space-y-3">
                        <h4 class="font-bold text-slate-900 dark:text-slate-100 flex items-center gap-1.5 text-xs">
                            <span class="material-icons-round text-slate-400 dark:text-slate-500 text-sm">groups</span> Integrantes del Equipo ({{ $integrantes->count() }})
                        </h4>

                        <div class="divide-y divide-slate-100 dark:divide-slate-800 border border-slate-100 dark:border-slate-800 rounded-xl px-3 bg-slate-50/50 dark:bg-slate-800/40">
                            @foreach($integrantes as $integrante)
                                @php
                                    $nom = $integrante->nombre;
                                    $pat = $integrante->apellido_paterno;
                                    try {
                                        if (is_string($nom) && (str_starts_with($nom, 'ey') || strlen($nom) > 50)) $nom = decrypt($nom);
                                        if (is_string($pat) && (str_starts_with($pat, 'ey') || strlen($pat) > 50)) $pat = decrypt($pat);
                                    } catch (\Throwable $e) {}
                                @endphp
                                <div class="py-2 flex justify-between items-center">
                                    <div class="flex items-center gap-2">
                                        <span class="material-icons-round text-slate-400 dark:text-slate-500 text-base">account_circle</span>
                                        <div>
                                            <p class="font-bold text-slate-800 dark:text-slate-200">{{ $pat }} {{ $nom }}</p>
                                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-mono">{{ $integrante->username }}</p>
                                        </div>
                                    </div>
                                    <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase">
                                        {{ $integrante->alumno_id == $proyecto->alumno_id ? 'Líder' : 'Integrante' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Entregables desde la tabla proyectos_titulacion -->
                    <div class="space-y-3">
                        <h4 class="font-bold text-slate-900 dark:text-slate-100 flex items-center gap-1.5 text-xs">
                            <span class="material-icons-round text-slate-400 dark:text-slate-500 text-sm">folder_open</span> Entregables Cargados
                        </h4>

                        <div class="space-y-2">
                            <!-- 1. Reporte Escrito (documento_url) -->
                            <div class="p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between">
                                <span class="font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                    <span class="material-icons-round text-red-600 dark:text-red-400 text-lg">picture_as_pdf</span> Reporte Escrito
                                </span>
                                @if($proyecto->documento_url)
                                    <a href="{{ asset('storage/' . $proyecto->documento_url) }}" target="_blank" class="text-[#841B44] dark:text-rose-400 font-bold text-[10px] hover:underline flex items-center gap-0.5">
                                        <span class="material-icons-round text-xs">visibility</span> Ver PDF
                                    </a>
                                @else
                                    <span class="text-slate-400 dark:text-slate-500 italic text-[10px]">Sin subir</span>
                                @endif
                            </div>

                            <!-- 2. Presentación (presentacion_url) -->
                            <div class="p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between">
                                <span class="font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                    <span class="material-icons-round text-amber-600 dark:text-amber-400 text-lg">slideshow</span> Presentación Técnica
                                </span>
                                @if($proyecto->presentacion_url)
                                    <a href="{{ asset('storage/' . $proyecto->presentacion_url) }}" target="_blank" class="text-[#841B44] dark:text-rose-400 font-bold text-[10px] hover:underline flex items-center gap-0.5">
                                        <span class="material-icons-round text-xs">download</span> Descargar
                                    </a>
                                @else
                                    <span class="text-slate-400 dark:text-slate-500 italic text-[10px]">Sin subir</span>
                                @endif
                            </div>

                            <!-- 3. Video (video_url) -->
                            <div class="p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between">
                                <span class="font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                    <span class="material-icons-round text-blue-600 dark:text-blue-400 text-lg">video_library</span> Video Demostrativo
                                </span>
                                @if($proyecto->video_url)
                                    <a href="{{ $proyecto->video_url }}" target="_blank" class="text-blue-600 dark:text-blue-400 font-bold text-[10px] hover:underline flex items-center gap-0.5">
                                        <span class="material-icons-round text-xs">open_in_new</span> Abrir Enlace
                                    </a>
                                @else
                                    <span class="text-slate-400 dark:text-slate-500 italic text-[10px]">Sin enlace</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ACCIONES DE DICTAMEN Y VOTO -->
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex flex-wrap justify-end gap-3">
                    @if($esAsesor)
                        <button onclick="abrirModalAsesor('{{ $proyecto->id }}', '{{ addslashes($proyecto->titulo) }}', '{{ $proyecto->estatus }}')" 
                                class="px-4 py-2 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-bold rounded-xl transition-colors cursor-pointer flex items-center gap-1.5 text-xs">
                            <span class="material-icons-round text-sm">assignment_turned_in</span> Dictamen Asesor / Liberar para Exposición
                        </button>
                    @endif

                    @if($miJurado && $proyecto->estatus == 'Liberado_Exposicion')
                        <button onclick="abrirModalVoto('{{ $proyecto->id }}', '{{ addslashes($proyecto->titulo) }}', '{{ $miJurado->voto }}')" 
                                class="px-4 py-2 bg-[#841B44] hover:bg-[#681535] dark:bg-rose-800 dark:hover:bg-rose-700 text-white font-bold rounded-xl transition-colors cursor-pointer flex items-center gap-1.5 text-xs shadow-xs">
                            <span class="material-icons-round text-sm">how_to_vote</span> Emitir Voto como Jurado
                        </button>
                    @endif
                </div>

            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-200 dark:border-slate-800 text-center text-slate-400 dark:text-slate-500 font-medium space-y-2">
                <span class="material-icons-round text-3xl block text-slate-300 dark:text-slate-700">school</span>
                <p>Actualmente no tienes proyectos asignados para asesoría ni como jurado examinador.</p>
            </div>
        @endforelse
    </div>

</main>

<!-- MODAL ASESOR -->
<div id="modalAsesorEval" class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-2xl shadow-xl overflow-hidden p-6 space-y-4 text-xs border border-slate-100 dark:border-slate-800">
        <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-3">
            <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-sm flex items-center gap-1.5">
                <span class="material-icons-round text-[#841B44] dark:text-rose-400">rate_review</span> Dictamen del Asesor
            </h3>
            <button onclick="document.getElementById('modalAsesorEval').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <span class="material-icons-round">close</span>
            </button>
        </div>

        <form action="{{ route('docente.titulacion.evaluar') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="proyecto_id" id="asesor_proyecto_id">

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Proyecto</label>
                <p id="asesor_titulo" class="font-semibold text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-slate-800/80 p-2 rounded-xl border border-slate-200 dark:border-slate-700"></p>
            </div>

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Estatus del Protocolo / Entregables *</label>
                <select name="estatus" id="asesor_estatus" required class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-semibold text-slate-700 dark:text-slate-200 focus:ring-1 focus:ring-[#841B44] dark:focus:ring-rose-400">
                    <option value="Pendiente">Pendiente / En Revisión Técnica</option>
                    <option value="Liberado_Exposicion">Liberado para Exposición (Habilita votación de los 3 jurados)</option>
                    <option value="Rechazado">Rechazado (Requiere Correcciones)</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modalAsesorEval').classList.add('hidden')" class="px-4 py-2 border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-slate-900 dark:bg-rose-800 hover:bg-black dark:hover:bg-rose-700 text-white font-bold rounded-xl">Actualizar Estatus</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL JURADO -->
<div id="modalVotoJurado" class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-2xl shadow-xl overflow-hidden p-6 space-y-4 text-xs border border-slate-100 dark:border-slate-800">
        <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-3">
            <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-sm flex items-center gap-1.5">
                <span class="material-icons-round text-[#841B44] dark:text-rose-400">how_to_vote</span> Voto de Examen Profesional
            </h3>
            <button onclick="document.getElementById('modalVotoJurado').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <span class="material-icons-round">close</span>
            </button>
        </div>

        <form action="{{ route('docente.titulacion.votar-jurado') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="proyecto_id" id="voto_proyecto_id">

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Proyecto Evaluado</label>
                <p id="voto_titulo" class="font-semibold text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-slate-800/80 p-2 rounded-xl border border-slate-200 dark:border-slate-700"></p>
            </div>

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Tu Veredicto *</label>
                <select name="voto" id="voto_valor" required class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-semibold text-slate-700 dark:text-slate-200 focus:ring-1 focus:ring-[#841B44] dark:focus:ring-rose-400">
                    <option value="Aprobado">Aprobado</option>
                    <option value="Rechazado">Rechazado</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Observaciones / Acta (Opcional)</label>
                <textarea name="observaciones" rows="2" class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-medium text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-[#841B44] dark:focus:ring-rose-400" placeholder="Comentarios del examen oral..."></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modalVotoJurado').classList.add('hidden')" class="px-4 py-2 border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-[#841B44] hover:bg-[#681535] dark:bg-rose-800 dark:hover:bg-rose-700 text-white font-bold rounded-xl">Emitir Veredicto</button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModalAsesor(id, titulo, estatus) {
        document.getElementById('asesor_proyecto_id').value = id;
        document.getElementById('asesor_titulo').innerText = titulo;
        document.getElementById('asesor_estatus').value = estatus;
        document.getElementById('modalAsesorEval').classList.remove('hidden');
    }

    function abrirModalVoto(id, titulo, voto) {
        document.getElementById('voto_proyecto_id').value = id;
        document.getElementById('voto_titulo').innerText = titulo;
        document.getElementById('voto_valor').value = (voto === 'Pendiente') ? 'Aprobado' : voto;
        document.getElementById('modalVotoJurado').classList.remove('hidden');
    }
</script>
@endsection
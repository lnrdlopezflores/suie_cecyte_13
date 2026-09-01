@extends('cpanel/plantillacoordinacion')
@section('title', 'Asignación de Jurados - Animación Digital')

@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-6 md:p-8 space-y-8 text-sm md:text-base">

    <!-- MENSAJES FLASH -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 p-4 md:p-5 rounded-2xl flex items-center justify-between shadow-xs font-semibold">
            <div class="flex items-center gap-3">
                <span class="material-icons-round text-2xl text-emerald-600 dark:text-emerald-400">check_circle</span>
                <p>{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-100 cursor-pointer">
                <span class="material-icons-round text-lg">close</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 p-4 md:p-5 rounded-2xl flex items-center justify-between shadow-xs font-semibold">
            <div class="flex items-center gap-3">
                <span class="material-icons-round text-2xl text-rose-600 dark:text-rose-400">error</span>
                <p>{{ session('error') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-600 dark:text-rose-400 hover:text-rose-900 dark:hover:text-rose-100 cursor-pointer">
                <span class="material-icons-round text-lg">close</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 p-4 md:p-5 rounded-2xl space-y-2 shadow-xs">
            <div class="flex items-center gap-2 font-bold">
                <span class="material-icons-round text-xl text-rose-600 dark:text-rose-400">error</span>
                <span>Ocurrieron errores al procesar la asignación del sínodo:</span>
            </div>
            <ul class="list-disc pl-8 text-xs md:text-sm space-y-1 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ENCABEZADO DE LA CARRERA -->
    <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6 transition-colors duration-200">
        <div class="space-y-1.5">
            <div class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 font-extrabold text-xs uppercase tracking-wider">
                <span class="material-icons-round text-base">animation</span>
                <span>Coordinación de Carrera</span>
            </div>
            <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-slate-100 flex items-center gap-3">
                Tribunal y Sínodos: Animación Digital
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm">Asigna o actualiza a los 3 jurados docentes (Presidente, Secretario y Vocal) para la defensa del proyecto.</p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <div class="bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-900 px-5 py-3 rounded-2xl text-center">
                <span class="text-[10px] text-indigo-700 dark:text-indigo-300 font-extrabold uppercase tracking-wider block">Proyectos Activos</span>
                <span class="font-black text-indigo-900 dark:text-indigo-100 text-base md:text-lg">{{ $proyectos->count() }}</span>
            </div>
        </div>
    </div>

    <!-- LISTADO DE PROYECTOS PARA ASIGNACIÓN DE JURADOS -->
    <div class="space-y-6">
        @forelse($proyectos as $proyecto)
            @php
                $jurados = $proyecto->jurados ?? collect();
                $presidente = $jurados->firstWhere('cargo', 'Presidente');
                $secretario = $jurados->firstWhere('cargo', 'Secretario');
                $vocal      = $jurados->firstWhere('cargo', 'Vocal');
                
                $tieneSinodoCompleto = ($presidente && $secretario && $vocal);
            @endphp

            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 md:p-8 space-y-6 transition-colors duration-200">
                
                <!-- Cabecera del Proyecto -->
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 border-b border-slate-100 dark:border-slate-800 pb-5">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-900 text-indigo-700 dark:text-indigo-300 text-xs font-black rounded-lg uppercase">
                                {{ $proyecto->modalidad }}
                            </span>
                            <span class="text-xs text-slate-400 dark:text-slate-500 font-mono">
                                Folio: #{{ \Illuminate\Support\Str::padLeft($proyecto->id, 4, '0') }}
                            </span>
                        </div>
                        <h3 class="text-base md:text-lg font-black text-slate-900 dark:text-slate-100 leading-snug">
                            {{ $proyecto->titulo }}
                        </h3>
                    </div>

                    <!-- Estado del Sínodo -->
                    <div class="shrink-0">
                        @if($tieneSinodoCompleto)
                            <span class="px-3.5 py-1.5 text-xs font-extrabold rounded-full uppercase border bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-900 flex items-center gap-1.5">
                                <span class="material-icons-round text-sm">groups</span> Sínodo Completo (3/3)
                            </span>
                        @else
                            <span class="px-3.5 py-1.5 text-xs font-extrabold rounded-full uppercase border bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-900 flex items-center gap-1.5">
                                <span class="material-icons-round text-sm">pending</span> Sínodo Incompleto ({{ $jurados->count() }}/3)
                            </span>
                        @endif
                    </div>
                </div>

                <!-- CARDS DE LOS 3 JURADOS (PRESIDENTE, SECRETARIO, VOCAL) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    <!-- 1. Presidente -->
                    <div class="bg-slate-50/80 dark:bg-slate-800/40 p-4 md:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-[11px] font-black uppercase text-indigo-600 dark:text-indigo-400 tracking-wider">Presidente</span>
                            @if($presidente)
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $presidente->voto == 'Aprobado' ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                                    Voto: {{ $presidente->voto }}
                                </span>
                            @endif
                        </div>
                        @if($presidente)
                            <p class="font-extrabold text-slate-900 dark:text-slate-100 text-xs md:text-sm">
                                Prof. {{ $presidente->apellido_paterno }} {{ $presidente->nombre }}
                            </p>
                            <p class="text-[11px] text-slate-400 font-mono">ID: {{ $presidente->username }}</p>
                        @else
                            <p class="font-bold text-amber-600 dark:text-amber-400 text-xs italic">Sin asignar</p>
                        @endif
                    </div>

                    <!-- 2. Secretario -->
                    <div class="bg-slate-50/80 dark:bg-slate-800/40 p-4 md:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-[11px] font-black uppercase text-indigo-600 dark:text-indigo-400 tracking-wider">Secretario</span>
                            @if($secretario)
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $secretario->voto == 'Aprobado' ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                                    Voto: {{ $secretario->voto }}
                                </span>
                            @endif
                        </div>
                        @if($secretario)
                            <p class="font-extrabold text-slate-900 dark:text-slate-100 text-xs md:text-sm">
                                Prof. {{ $secretario->apellido_paterno }} {{ $secretario->nombre }}
                            </p>
                            <p class="text-[11px] text-slate-400 font-mono">ID: {{ $secretario->username }}</p>
                        @else
                            <p class="font-bold text-amber-600 dark:text-amber-400 text-xs italic">Sin asignar</p>
                        @endif
                    </div>

                    <!-- 3. Vocal -->
                    <div class="bg-slate-50/80 dark:bg-slate-800/40 p-4 md:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-[11px] font-black uppercase text-indigo-600 dark:text-indigo-400 tracking-wider">Vocal</span>
                            @if($vocal)
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $vocal->voto == 'Aprobado' ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                                    Voto: {{ $vocal->voto }}
                                </span>
                            @endif
                        </div>
                        @if($vocal)
                            <p class="font-extrabold text-slate-900 dark:text-slate-100 text-xs md:text-sm">
                                Prof. {{ $vocal->apellido_paterno }} {{ $vocal->nombre }}
                            </p>
                            <p class="text-[11px] text-slate-400 font-mono">ID: {{ $vocal->username }}</p>
                        @else
                            <p class="font-bold text-amber-600 dark:text-amber-400 text-xs italic">Sin asignar</p>
                        @endif
                    </div>

                </div>

                <!-- ACCIÓN: ABRIR MODAL DE ASIGNACIÓN -->
                <div class="flex justify-end pt-2">
                    <button type="button" 
                            onclick="abrirModalJurados('{{ $proyecto->id }}', '{{ addslashes($proyecto->titulo) }}', '{{ $presidente->docente_id ?? '' }}', '{{ $secretario->docente_id ?? '' }}', '{{ $vocal->docente_id ?? '' }}')"
                            class="px-5 py-3 bg-[#841B44] hover:bg-[#681535] dark:bg-rose-800 dark:hover:bg-rose-700 text-white font-extrabold rounded-2xl transition-all shadow-md flex items-center gap-2 cursor-pointer text-xs md:text-sm">
                        <span class="material-icons-round text-base">how_to_reg</span>
                        <span>{{ $tieneSinodoCompleto ? 'Modificar Jurados' : 'Asignar Jurados (3 Sínodos)' }}</span>
                    </button>
                </div>

            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 p-12 rounded-3xl border border-slate-200 dark:border-slate-800 text-center text-slate-400 dark:text-slate-500 font-medium space-y-3">
                <span class="material-icons-round text-5xl block text-slate-300 dark:text-slate-700">animation</span>
                <p class="text-base">No hay proyectos registrados para la carrera de Animación Digital.</p>
            </div>
        @endforelse
    </div>

</main>

<!-- MODAL PARA ASIGNAR LOS 3 JURADOS -->
<div id="modalAsignarJurados" class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 w-full max-w-xl rounded-3xl shadow-2xl overflow-hidden p-6 md:p-8 space-y-6 border border-slate-100 dark:border-slate-800 text-xs md:text-sm">
        
        <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-4">
            <h3 class="font-black text-slate-900 dark:text-slate-100 text-base md:text-lg flex items-center gap-2">
                <span class="material-icons-round text-xl text-[#841B44] dark:text-rose-400">gavel</span>
                Asignación del Sínodo Examinador
            </h3>
            <button onclick="document.getElementById('modalAsignarJurados').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <span class="material-icons-round text-2xl">close</span>
            </button>
        </div>

        <form action="{{ route('coordinador.jurados.guardar') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="proyecto_id" id="modal_proyecto_id">
            <input type="hidden" name="carrera" value="animacion_digital">

            <!-- Título del proyecto -->
            <div>
                <label class="block font-black text-slate-400 dark:text-slate-400 uppercase tracking-wider text-[11px] mb-1">Proyecto de Titulación</label>
                <p id="modal_proyecto_titulo" class="font-bold text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-slate-800/80 p-3 rounded-2xl border border-slate-200 dark:border-slate-700"></p>
            </div>

            <!-- 1. Presidente -->
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">1. Docente Presidente *</label>
                <select name="presidente_id" id="select_presidente" required class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-[#841B44]">
                    <option value="" disabled selected>Selecciona al Presidente del Sínodo...</option>
                    @foreach($docentesActivos ?? [] as $doc)
                        <option value="{{ $doc->id }}">Prof. {{ $doc->apellido_paterno }} {{ $doc->nombre }} ({{ $doc->username }})</option>
                    @endforeach
                </select>
            </div>

            <!-- 2. Secretario -->
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">2. Docente Secretario *</label>
                <select name="secretario_id" id="select_secretario" required class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-[#841B44]">
                    <option value="" disabled selected>Selecciona al Secretario del Sínodo...</option>
                    @foreach($docentesActivos ?? [] as $doc)
                        <option value="{{ $doc->id }}">Prof. {{ $doc->apellido_paterno }} {{ $doc->nombre }} ({{ $doc->username }})</option>
                    @endforeach
                </select>
            </div>

            <!-- 3. Vocal -->
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">3. Docente Vocal *</label>
                <select name="vocal_id" id="select_vocal" required class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-[#841B44]">
                    <option value="" disabled selected>Selecciona al Vocal del Sínodo...</option>
                    @foreach($docentesActivos ?? [] as $doc)
                        <option value="{{ $doc->id }}">Prof. {{ $doc->apellido_paterno }} {{ $doc->nombre }} ({{ $doc->username }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modalAsignarJurados').classList.add('hidden')" 
                        class="px-5 py-3 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    Cancelar
                </button>
                <button type="submit" class="px-6 py-3 bg-[#841B44] hover:bg-[#681535] dark:bg-rose-800 dark:hover:bg-rose-700 text-white font-extrabold rounded-2xl shadow-md transition-colors">
                    Guardar Sínodo (3 Jurados)
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function abrirModalJurados(proyectoId, titulo, presidenteId, secretarioId, vocalId) {
        document.getElementById('modal_proyecto_id').value = proyectoId;
        document.getElementById('modal_proyecto_titulo').innerText = titulo;
        
        document.getElementById('select_presidente').value = presidenteId || '';
        document.getElementById('select_secretario').value = secretarioId || '';
        document.getElementById('select_vocal').value = vocalId || '';

        document.getElementById('modalAsignarJurados').classList.remove('hidden');
    }
</script>
@endsection
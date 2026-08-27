@extends('cpanel/plantillacoordinacion')
@section('title', 'Proyectos Registrados')

@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-6 md:p-8 space-y-8 text-sm md:text-base">

    <!-- ENCABEZADO Y CONTADORES RÁPIDOS -->
    <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6 transition-colors duration-200">
        <div>
            <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-slate-100 flex items-center gap-3">
                <span class="material-icons-round text-2xl md:text-3xl text-[#841B44] dark:text-rose-400">folder_special</span>
                Monitoreo de Proyectos de Titulación
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm mt-1">Supervisa los protocolos dados de alta por los alumnos, asesorías y dictámenes del sínodo.</p>
        </div>

        <!-- Indicadores condensados -->
        <div class="flex items-center gap-3 shrink-0 flex-wrap">
            <div class="bg-slate-50 dark:bg-slate-800/80 px-4 py-2.5 rounded-2xl border border-slate-200 dark:border-slate-700 text-center">
                <span class="text-[10px] text-slate-400 dark:text-slate-400 font-extrabold uppercase tracking-wider block">Total Proyectos</span>
                <span class="font-black text-slate-800 dark:text-slate-100 text-base md:text-lg">{{ $proyectos->total() }}</span>
            </div>
            <div class="bg-emerald-50 dark:bg-emerald-950/60 px-4 py-2.5 rounded-2xl border border-emerald-200 dark:border-emerald-900 text-center">
                <span class="text-[10px] text-emerald-700 dark:text-emerald-300 font-extrabold uppercase tracking-wider block">Aprobados</span>
                <span class="font-black text-emerald-800 dark:text-emerald-200 text-base md:text-lg">{{ $totalAprobados ?? 0 }}</span>
            </div>
        </div>
    </div>

    <!-- BARRA DE FILTROS Y BÚSQUEDA -->
    <div class="bg-white dark:bg-slate-900 p-4 md:p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4 transition-colors duration-200">
        <!-- Filtro por Carrera -->
        <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0">
            <a href="{{ route('coordinador.proyectos.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all shrink-0 {{ !request('carrera') ? 'bg-[#841B44] text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                Todas las Carreras
            </a>
            <a href="{{ route('coordinador.proyectos.index', ['carrera' => 'Animación Digital']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all shrink-0 flex items-center gap-1.5 {{ request('carrera') == 'Animación Digital' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                <span class="material-icons-round text-sm">animation</span> Animación Digital
            </a>
            <a href="{{ route('coordinador.proyectos.index', ['carrera' => 'Química Industrial']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all shrink-0 flex items-center gap-1.5 {{ request('carrera') == 'Química Industrial' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                <span class="material-icons-round text-sm">science</span> Química Industrial
            </a>
        </div>

        <!-- Buscador -->
        <form action="{{ route('coordinador.proyectos.index') }}" method="GET" class="w-full md:w-72">
            @if(request('carrera'))
                <input type="hidden" name="carrera" value="{{ request('carrera') }}">
            @endif
            <div class="relative">
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por título o alumno..." 
                       class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-xl pl-9 pr-4 py-2 text-xs md:text-sm font-medium text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-[#841B44] focus:outline-hidden transition-all">
                <span class="material-icons-round absolute left-2.5 top-2.5 text-slate-400 text-base">search</span>
            </div>
        </form>
    </div>

    <!-- LISTADO DE TARJETAS DE PROYECTOS -->
    <div class="space-y-6">
        @forelse($proyectos as $proyecto)
            @php
                // Asesor
                $asesorNom = $proyecto->asesor_nombre ?? '';
                $asesorPat = $proyecto->asesor_paterno ?? '';
                try {
                    if (is_string($asesorNom) && (str_starts_with($asesorNom, 'ey') || strlen($asesorNom) > 50)) $asesorNom = decrypt($asesorNom);
                    if (is_string($asesorPat) && (str_starts_with($asesorPat, 'ey') || strlen($asesorPat) > 50)) $asesorPat = decrypt($asesorPat);
                } catch (\Throwable $e) {}

                $integrantes = $proyecto->integrantes ?? collect();
            @endphp

            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 md:p-8 space-y-6 hover:border-slate-300 dark:hover:border-slate-700 transition-all duration-200">
                
                <!-- Encabezado del Proyecto -->
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 border-b border-slate-100 dark:border-slate-800 pb-5">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-3 py-1 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900 text-[#841B44] dark:text-rose-300 text-xs font-black rounded-lg uppercase tracking-wider">
                                {{ $proyecto->modalidad }}
                            </span>
                            <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-lg border border-slate-200 dark:border-slate-700">
                                {{ $proyecto->especialidad ?? 'Especialidad General' }}
                            </span>
                        </div>
                        <h3 class="text-base md:text-lg font-black text-slate-900 dark:text-slate-100 leading-snug">
                            {{ $proyecto->titulo }}
                        </h3>
                    </div>

                    <!-- Badge de Estado -->
                    <div class="shrink-0">
                        @switch($proyecto->estatus)
                            @case('Aprobado')
                                <span class="px-3.5 py-1.5 text-xs font-black rounded-full uppercase border bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-900 flex items-center gap-1.5 shadow-3xs">
                                    <span class="material-icons-round text-sm">verified</span> Aprobado
                                </span>
                                @break
                            @case('Liberado_Exposicion')
                                <span class="px-3.5 py-1.5 text-xs font-black rounded-full uppercase border bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-900 flex items-center gap-1.5 shadow-3xs">
                                    <span class="material-icons-round text-sm">record_voice_over</span> Liberado para Exposición
                                </span>
                                @break
                            @case('Rechazado')
                                <span class="px-3.5 py-1.5 text-xs font-black rounded-full uppercase border bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-900 flex items-center gap-1.5 shadow-3xs">
                                    <span class="material-icons-round text-sm">cancel</span> Rechazado
                                </span>
                                @break
                            @default
                                <span class="px-3.5 py-1.5 text-xs font-black rounded-full uppercase border bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-900 flex items-center gap-1.5 shadow-3xs">
                                    <span class="material-icons-round text-sm">pending</span> En Revisión Técnica
                                </span>
                        @endswitch
                    </div>
                </div>

                @if($proyecto->resumen)
                    <div class="bg-slate-50 dark:bg-slate-800/60 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 text-xs md:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                        <strong class="text-slate-800 dark:text-slate-200 block mb-1">Resumen del Protocolo:</strong>
                        {{ $proyecto->resumen }}
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pt-1">
                    
                    <!-- 1. Integrantes del Equipo -->
                    <div class="space-y-3">
                        <h4 class="font-black text-slate-900 dark:text-slate-100 flex items-center gap-2 text-xs md:text-sm">
                            <span class="material-icons-round text-slate-400 text-base">groups</span> Alumnos Registrados ({{ $integrantes->count() }})
                        </h4>

                        <div class="divide-y divide-slate-100 dark:divide-slate-800 border border-slate-200/80 dark:border-slate-700/80 rounded-2xl px-4 bg-slate-50/50 dark:bg-slate-800/30">
                            @foreach($integrantes as $integrante)
                                @php
                                    $nomAlu = $integrante->nombre;
                                    $patAlu = $integrante->apellido_paterno;
                                    try {
                                        if (is_string($nomAlu) && (str_starts_with($nomAlu, 'ey') || strlen($nomAlu) > 50)) $nomAlu = decrypt($nomAlu);
                                        if (is_string($patAlu) && (str_starts_with($patAlu, 'ey') || strlen($patAlu) > 50)) $patAlu = decrypt($patAlu);
                                    } catch (\Throwable $e) {}
                                @endphp
                                <div class="py-3 flex justify-between items-center text-xs md:text-sm">
                                    <div class="flex items-center gap-2.5">
                                        <span class="material-icons-round text-slate-400 text-lg">person</span>
                                        <div>
                                            <p class="font-bold text-slate-800 dark:text-slate-200">{{ $patAlu }} {{ $nomAlu }}</p>
                                            <p class="text-[11px] text-slate-400 font-mono">{{ $integrante->username }}</p>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">
                                        {{ $integrante->alumno_id == $proyecto->alumno_id ? 'Líder' : 'Colaborador' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- 2. Asesor Docente Asignado -->
                    <div class="space-y-3">
                        <h4 class="font-black text-slate-900 dark:text-slate-100 flex items-center gap-2 text-xs md:text-sm">
                            <span class="material-icons-round text-slate-400 text-base">supervisor_account</span> Asesor de Proyecto
                        </h4>

                        <div class="p-4 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/30 flex items-center gap-3.5">
                            <div class="w-10 h-10 bg-rose-50 dark:bg-rose-950/60 text-[#841B44] dark:text-rose-300 rounded-xl flex items-center justify-center font-black text-xs border border-rose-100 dark:border-rose-900/60 shrink-0">
                                AS
                            </div>
                            <div>
                                @if($asesorNom || $asesorPat)
                                    <p class="font-bold text-slate-800 dark:text-slate-100 text-xs md:text-sm">Prof. {{ $asesorPat }} {{ $asesorNom }}</p>
                                    <p class="text-[11px] text-slate-400 font-mono">Clave: {{ $proyecto->asesor_username ?? 'DOC' }}</p>
                                @else
                                    <p class="font-bold text-amber-600 dark:text-amber-400 text-xs italic">Sin asesor asignado</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 3. Entregables Técnicos -->
                    <div class="space-y-3">
                        <h4 class="font-black text-slate-900 dark:text-slate-100 flex items-center gap-2 text-xs md:text-sm">
                            <span class="material-icons-round text-slate-400 text-base">attach_file</span> Entregables
                        </h4>

                        <div class="space-y-2 text-xs">
                            <!-- Reporte PDF -->
                            <div class="p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/30 flex items-center justify-between">
                                <span class="font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                    <span class="material-icons-round text-red-500 text-base">picture_as_pdf</span> Reporte
                                </span>
                                @if($proyecto->documento_url)
                                    <a href="{{ asset('storage/' . $proyecto->documento_url) }}" target="_blank" class="text-[#841B44] dark:text-rose-400 font-bold hover:underline flex items-center gap-0.5">
                                        <span class="material-icons-round text-xs">visibility</span> Ver
                                    </a>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">No cargado</span>
                                @endif
                            </div>

                            <!-- Presentación -->
                            <div class="p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/30 flex items-center justify-between">
                                <span class="font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                    <span class="material-icons-round text-amber-500 text-base">slideshow</span> Diapositivas
                                </span>
                                @if($proyecto->presentacion_url)
                                    <a href="{{ asset('storage/' . $proyecto->presentacion_url) }}" target="_blank" class="text-[#841B44] dark:text-rose-400 font-bold hover:underline flex items-center gap-0.5">
                                        <span class="material-icons-round text-xs">download</span> Bajar
                                    </a>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">No cargado</span>
                                @endif
                            </div>

                            <!-- Video -->
                            <div class="p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/30 flex items-center justify-between">
                                <span class="font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                    <span class="material-icons-round text-blue-500 text-base">video_library</span> Video Demo
                                </span>
                                @if($proyecto->video_url)
                                    <a href="{{ $proyecto->video_url }}" target="_blank" class="text-blue-600 dark:text-blue-400 font-bold hover:underline flex items-center gap-0.5">
                                        <span class="material-icons-round text-xs">open_in_new</span> Abrir
                                    </a>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Sin enlace</span>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 p-12 rounded-3xl border border-slate-200 dark:border-slate-800 text-center text-slate-400 dark:text-slate-500 font-medium space-y-3">
                <span class="material-icons-round text-5xl block text-slate-300 dark:text-slate-700">folder_open</span>
                <p class="text-base">No se encontraron proyectos registrados con los criterios seleccionados.</p>
            </div>
        @endforelse
    </div>

    @if($proyectos->hasPages())
        <div class="p-4 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800">
            {{ $proyectos->links() }}
        </div>
    @endif

</main>
@endsection
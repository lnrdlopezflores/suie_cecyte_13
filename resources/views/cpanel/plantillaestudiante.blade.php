<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Estudiantil SUIE - @yield('title')</title>
    
    <!-- Tailwind CSS v4 Browser -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <!-- Configuración para que Tailwind v4 responda a la clase .dark en el HTML -->
    <style type="text/tailwindcss">
        @custom-variant dark (&:where(.dark, .dark *));
    </style>

    <link class="rounded-xs" rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Inicialización inmediata del tema -->
    <script>
        (function() {
            const userKey = 'suie_theme_u_{{ auth()->id() ?? "guest" }}';
            const userDbTheme = "{{ auth()->user()->tema ?? '' }}";
            const localTheme = localStorage.getItem(userKey);

            let activeTheme = userDbTheme || localTheme;
            if (!activeTheme) {
                activeTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            if (activeTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            localStorage.setItem(userKey, activeTheme);

            const dbPrimary = "{{ $colorPrimario ?? '#841B44' }}";
            const dbHover   = "{{ $colorHover ?? '#681535' }}";
            localStorage.setItem('suie_primary_color', dbPrimary);
            localStorage.setItem('suie_primary_hover', dbHover);
        })();
    </script>

    <!-- Inyección Dinámica y Sobrescritura Global de Colores SUIE -->
<style>
    :root {
        --color-primary: {{ $colorPrimario ?? '#841B44' }};
        --color-primary-hover: {{ $colorHover ?? '#681535' }};
        --color-primary-light: {{ $colorLight ?? '#fdf2f4' }};
    }

    /* 1. Clases utilitarias directas */
    .bg-custom-primary { background-color: var(--color-primary) !important; }
    .text-custom-primary { color: var(--color-primary) !important; }
    .border-custom-primary { border-color: var(--color-primary) !important; }
    .hover\:bg-custom-primary-hover:hover { background-color: var(--color-primary-hover) !important; }

    /* 2. Sobrescritura mágica para selectores de Tailwind fijos existentes */
    [class*="bg-[#841B44]"],
    [class*="bg-\[\#841B44\]"] {
        background-color: var(--color-primary) !important;
    }

    [class*="text-[#841B44]"],
    [class*="text-\[\#841B44\]"] {
        color: var(--color-primary) !important;
    }

    [class*="border-[#841B44]"],
    [class*="border-\[\#841B44\]"] {
        border-color: var(--color-primary) !important;
    }

    [class*="hover:bg-[#681535]"]:hover,
    [class*="hover:bg-[#6b1536]"]:hover,
    [class*="hover:bg-\[\#681535\]"]:hover,
    [class*="hover:bg-\[\#6b1536\]"]:hover {
        background-color: var(--color-primary-hover) !important;
    }

    [class*="hover:text-[#841B44]"]:hover,
    [class*="hover:text-\[\#841B44\]"]:hover {
        color: var(--color-primary) !important;
    }

    [class*="bg-rose-50"] {
        background-color: var(--color-primary-light) !important;
    }
</style>
</head>
<body class="bg-slate-100 dark:bg-slate-950 font-sans text-slate-800 dark:text-slate-100 text-sm h-screen flex flex-col overflow-hidden transition-colors duration-200">

    <!-- HEADER PRINCIPAL -->
    <header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-4 md:px-7 h-16 md:h-18 flex justify-between items-center shrink-0 z-50 relative shadow-2xs transition-colors duration-200">
        <div class="flex items-center space-x-3 md:space-x-4">
            <button id="btn-toggle-sidebar" class="md:hidden text-slate-600 dark:text-slate-300 hover:text-[#841B44] dark:hover:text-rose-400 hover:bg-slate-100 dark:hover:bg-slate-800 p-2 rounded-xl focus:outline-hidden inline-flex items-center cursor-pointer transition-colors">
                <span class="material-icons-round text-2xl">menu</span>
            </button>
            
            <div class="flex items-center space-x-2.5">
                <span class="material-icons-round text-3xl text-[#841B44] dark:text-rose-400">school</span>
                <div>
                    <h1 class="text-lg md:text-xl font-black tracking-wider leading-none text-[#841B44] dark:text-rose-400">SUIE</h1>
                    <p class="text-[10px] md:text-xs text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest mt-0.5 xs:block">Portal Estudiantes</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-3 md:space-x-4">
            <!-- BOTÓN TOGGLE MODO OSCURO -->
            <button id="btn-theme-toggle" type="button" aria-label="Cambiar tema"
                    class="p-2.5 text-slate-500 dark:text-slate-300 hover:text-[#841B44] dark:hover:text-rose-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all cursor-pointer border border-slate-200 dark:border-slate-700/80">
                <span id="theme-icon-light" class="material-icons-round text-xl hidden dark:block text-amber-400">light_mode</span>
                <span id="theme-icon-dark" class="material-icons-round text-xl block dark:hidden text-slate-600">dark_mode</span>
            </button>

            <div class="text-right hidden sm:block border-l border-slate-200 dark:border-slate-800 pl-4">
                <p class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ auth()->user()->username ?? 'Matrícula Alumno' }}</p>
                <p class="text-[11px] text-rose-700 dark:text-rose-400 font-bold uppercase tracking-wider mt-0.5">
                    @yield('grupo_badge', 'Estudiante Activo')
                </p>
            </div>

            <div class="w-9 h-9 md:w-10 md:h-10 bg-rose-50 dark:bg-rose-950/50 text-[#841B44] dark:text-rose-300 rounded-xl flex items-center justify-center font-black border border-rose-200 dark:border-rose-900/60 text-xs md:text-sm shrink-0 select-none shadow-3xs">
                ST
            </div>
        </div>
    </header>

    <div class="flex-1 flex overflow-hidden relative">
        
        <!-- OVERLAY MÓVIL -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-slate-950/50 backdrop-blur-xs z-30 transition-opacity duration-300 opacity-0 pointer-events-none md:hidden"></div>
        
        <!-- SIDEBAR -->
        <aside id="sidebar-menu" class="fixed md:static top-16 md:top-18 bottom-0 left-0 w-68 bg-slate-900 dark:bg-slate-950 text-slate-300 flex flex-col h-[calc(100vh-4rem)] md:h-full shrink-0 border-r border-slate-800 dark:border-slate-800/80 z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out justify-between">
            
            <div class="p-4 space-y-7 overflow-y-auto flex-1">
                
                <!-- SECCIÓN GENERAL -->
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-3 block mb-2.5">General</span>
                    <nav class="space-y-1.5">
                        <a href="{{ route('indexalumnos.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('indexalumnos.index') ? 'bg-[#841B44] text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100' }}">
                            <span class="material-icons-round text-base">dashboard</span> Panel de Inicio
                        </a>
                    </nav>
                </div>

                <!-- SECCIÓN SERVICIOS ESCOLARES -->
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-3 block mb-2.5">Servicios Escolares</span>
                    <nav class="space-y-1.5">
                        <a href="{{ route('indexmaterias.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('indexmaterias.index') ? 'bg-[#841B44] text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100' }}">
                            <span class="material-icons-round text-base">auto_stories</span> Mis Materias
                        </a>
                        <a href="{{ route('alumnoPagos.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('alumnoPagos.index') ? 'bg-[#841B44] text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100' }}">
                            <span class="material-icons-round text-base">payments</span> Control de Pagos
                        </a>

                        @php
                            $alumnoGlobal = DB::table('alumnos')
                                ->leftJoin('grupos', 'alumnos.grupo_id', '=', 'grupos.id')
                                ->select('alumnos.id', 'grupos.semestre')
                                ->where('alumnos.usuario_id', auth()->id())
                                ->first();

                            $semestreAlumno = $alumnoGlobal->semestre ?? 0;

                            $proyectoGlobal = null;
                            $votosAprobados = 0;
                            if ($alumnoGlobal) {
                                $proyectoGlobal = DB::table('proyectos_titulacion')
                                    ->join('proyecto_alumno', 'proyectos_titulacion.id', '=', 'proyecto_alumno.proyecto_id')
                                    ->select('proyectos_titulacion.id', 'proyectos_titulacion.estatus')
                                    ->where('proyecto_alumno.alumno_id', $alumnoGlobal->id)
                                    ->first();

                                if ($proyectoGlobal) {
                                    $votosAprobados = DB::table('proyecto_jurados')
                                        ->where('proyecto_id', $proyectoGlobal->id)
                                        ->where('voto', 'Aprobado')
                                        ->count();
                                }
                            }

                            $procesoHabilitado = ($semestreAlumno >= 6 && $proyectoGlobal && $proyectoGlobal->estatus === 'Aprobado' && $votosAprobados >= 2);
                        @endphp

                        <!-- 1. MÓDULO: PROYECTOS DE TITULACIÓN (Solo 6to Semestre) -->
                        @if($semestreAlumno >= 6)
                            <a href="{{ route('titulacion.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('titulacion.*') ? 'bg-[#841B44] text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100' }}">
                                <span class="material-icons-round text-base">history_edu</span> Proyectos de Titulación
                            </a>
                        @else
                            <button type="button" onclick="mostrarAlertaBloqueo('ModuloBloqueadoSemestre')" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-800/50 dark:hover:bg-slate-900/50 hover:text-slate-400 transition-colors cursor-pointer">
                                <div class="flex items-center gap-3">
                                    <span class="material-icons-round text-base">history_edu</span> Proyectos de Titulación
                                </div>
                                <span class="material-icons-round text-sm text-amber-500">lock</span>
                            </button>
                        @endif

                        <!-- 2. MÓDULO: PROCESO DE TITULACIÓN (Solo si el Proyecto está Aprobado) -->
                        @if($procesoHabilitado)
                            <a href="{{ route('proceso.titulacion.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('proceso.titulacion.*') ? 'bg-[#841B44] text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100' }}">
                                <span class="material-icons-round text-base">assignment_turned_in</span> Proceso de Titulación
                            </a>
                        @else
                            <button type="button" onclick="mostrarAlertaBloqueo('ModuloBloqueadoProyecto')" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-800/50 dark:hover:bg-slate-900/50 hover:text-slate-400 transition-colors cursor-pointer">
                                <div class="flex items-center gap-3">
                                    <span class="material-icons-round text-base">assignment_turned_in</span> Proceso de Titulación
                                </div>
                                <span class="material-icons-round text-sm text-amber-500">lock</span>
                            </button>
                        @endif

                    </nav>
                </div>
            </div>

            <!-- FOOTER SIDEBAR -->
            <div class="p-4 border-t border-slate-800 dark:border-slate-800/80 bg-slate-950/40 shrink-0">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 text-sm font-bold text-rose-400 hover:bg-rose-950/30 rounded-xl transition-all cursor-pointer">
                        <span class="material-icons-round text-base">logout</span> Cerrar Sesión
                    </button>
                </form>
            </div>
        </aside>

        <!-- CONTENEDOR PRINCIPAL DINÁMICO -->
        <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-slate-900/60 w-full transition-colors duration-200">
            @yield('content')
        </main>

    </div>

    <!-- MODAL DE ALERTA DE MÓDULO BLOQUEADO -->
    <div id="modalBloqueo" class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white dark:bg-slate-900 w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden p-6 md:p-7 text-center space-y-4 border border-slate-100 dark:border-slate-800 transition-colors duration-200">
            <div class="w-14 h-14 bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 rounded-2xl flex items-center justify-center mx-auto border border-amber-100 dark:border-amber-900/60 shadow-3xs">
                <span class="material-icons-round text-3xl">lock</span>
            </div>

            <div class="space-y-1.5">
                <h3 id="tituloBloqueo" class="text-base font-extrabold text-slate-900 dark:text-slate-100">Acceso Restringido</h3>
                <p id="mensajeBloqueo" class="text-xs md:text-sm text-slate-500 dark:text-slate-400 leading-relaxed"></p>
            </div>

            <button onclick="cerrarAlertaBloqueo()" class="w-full py-3 bg-[#841B44] hover:bg-[#681535] dark:bg-rose-800 dark:hover:bg-rose-700 text-white font-bold rounded-2xl transition-colors cursor-pointer text-sm shadow-xs">
                Entendido
            </button>
        </div>
    </div>

    <!-- SCRIPTS DE CONTROL -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sidebar móvil
            const btnToggle = document.getElementById('btn-toggle-sidebar');
            const sidebar = document.getElementById('sidebar-menu');
            const overlay = document.getElementById('sidebar-overlay');

            function toggleSidebar() {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('opacity-0');
                overlay.classList.toggle('pointer-events-none');
            }

            if (btnToggle && sidebar && overlay) {
                btnToggle.addEventListener('click', toggleSidebar);
                overlay.addEventListener('click', toggleSidebar);
            }

            // Alternar Modo Oscuro
            const btnTheme = document.getElementById('btn-theme-toggle');
            if (btnTheme) {
                btnTheme.addEventListener('click', function () {
                    const isDark = document.documentElement.classList.toggle('dark');
                    const selectedTheme = isDark ? 'dark' : 'light';
                    const userKey = 'suie_theme_u_{{ auth()->id() ?? "guest" }}';

                    // 1. Guardar en localStorage bajo la clave del usuario
                    localStorage.setItem(userKey, selectedTheme);

                    // 2. Guardar en la base de datos de manera asíncrona
                    @if(auth()->check())
                        fetch("{{ route('usuario.actualizar-tema') }}", {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ tema: selectedTheme })
                        }).catch(err => console.error('Error guardando preferencia de tema:', err));
                    @endif
                });
            }
        });

        function mostrarAlertaBloqueo(tipo) {
            const modal = document.getElementById('modalBloqueo');
            const titulo = document.getElementById('tituloBloqueo');
            const mensaje = document.getElementById('mensajeBloqueo');

            if (tipo === 'ModuloBloqueadoSemestre') {
                titulo.innerText = 'Trámite no Disponible';
                mensaje.innerText = 'El módulo de Proyectos de Titulación se habilita únicamente para estudiantes inscritos en el 6° semestre.';
            } else if (tipo === 'ModuloBloqueadoProyecto') {
                titulo.innerText = 'Proyecto en Revisión o No Registrado';
                mensaje.innerText = 'El Proceso de Titulación oficial se habilitará una vez que tu proyecto haya sido liberado y aprobado por la mayoría del sínodo examinador (mínimo 2 votos favorables).';
            }

            modal.classList.remove('hidden');
        }

        function cerrarAlertaBloqueo() {
            document.getElementById('modalBloqueo').classList.add('hidden');
        }
    </script>

</body>
</html>
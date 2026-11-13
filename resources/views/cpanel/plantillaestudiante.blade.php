<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal Estudiantil SUIE - @yield('title')</title>
    
    <!-- Tailwind CSS v4 Browser -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <!-- Configuración para habilitar variante dark con la clase .dark en Tailwind v4 -->
    <style type="text/tailwindcss">
        @custom-variant dark (&:where(.dark, .dark *));
    </style>

    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <!-- Inicialización de Tema por Usuario y Sincronización de Color -->
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

    <!-- Inyección Dinámica, Responsividad Base y Sobrescritura Global de Colores SUIE -->
    <style>
        :root {
            --color-primary: {{ $colorPrimario ?? '#841B44' }};
            --color-primary-hover: {{ $colorHover ?? '#681535' }};
            --color-primary-light: {{ $colorLight ?? '#fdf2f4' }};
        }

        /* 1. Fondo fijo inalterable del layout y comportamiento táctil */
        html, body {
            background-color: #f8fafc !important; /* slate-50 */
            -webkit-tap-highlight-color: transparent;
        }
        html.dark, html.dark body {
            background-color: #020617 !important; /* slate-950 */
        }

        /* Scrollbars limpios y adaptables */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.35);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.55);
        }

        /* Desplazamiento táctil fluido para tablas y contenedores */
        .overflow-x-auto, .overflow-y-auto {
            -webkit-overflow-scrolling: touch;
        }

        /* 2. Clases utilitarias directas */
        .bg-custom-primary { background-color: var(--color-primary) !important; color: #ffffff !important; }
        .text-custom-primary { color: var(--color-primary) !important; }
        .border-custom-primary { border-color: var(--color-primary) !important; }
        .hover\:bg-custom-primary-hover:hover { background-color: var(--color-primary-hover) !important; color: #ffffff !important; }
        .bg-custom-light { background-color: var(--color-primary-light) !important; }

        /* 3. Sobrescritura para elementos y botones (excluyendo fondos de layout) */
        button[class*="bg-[#841B44]"],
        a[class*="bg-[#841B44]"],
        span[class*="bg-[#841B44]"],
        div[class*="bg-[#841B44]"]:not(body):not(html),
        button[class*="bg-\[\#841B44\]"],
        a[class*="bg-\[\#841B44\]"],
        span[class*="bg-\[\#841B44\]"] {
            background-color: var(--color-primary) !important;
            color: #ffffff !important;
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
            color: #ffffff !important;
        }

        [class*="hover:text-[#841B44]"]:hover,
        [class*="hover:text-\[\#841B44\]"]:hover {
            color: var(--color-primary) !important;
        }

        span[class*="bg-rose-50"],
        div[class*="bg-rose-50"]:not(main):not(section):not(body) {
            background-color: var(--color-primary-light) !important;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 font-sans text-slate-800 dark:text-slate-100 text-sm h-[100dvh] flex flex-col overflow-hidden transition-colors duration-200">

    @php
        // Consulta única y optimizada del alumno autenticado (datos personales, grupo y titulación)
        $alumnoNombreCompleto = auth()->user()->username ?? 'Alumno';
        $alumnoIniciales = strtoupper(substr(auth()->user()->username ?? 'AL', 0, 2));
        $semestreAlumno = 0;
        $procesoHabilitado = false;

        if (auth()->check()) {
            $alumnoGlobal = \Illuminate\Support\Facades\DB::table('alumnos')
                ->leftJoin('grupos', 'alumnos.grupo_id', '=', 'grupos.id')
                ->select(
                    'alumnos.id',
                    'alumnos.nombre',
                    'alumnos.apellido_paterno',
                    'grupos.semestre'
                )
                ->where('alumnos.usuario_id', auth()->id())
                ->first();

            if ($alumnoGlobal) {
                $descifrar = function ($val) {
                    if (empty($val)) return '';
                    try {
                        if (is_string($val) && (str_starts_with($val, 'ey') || strlen($val) > 50)) {
                            return decrypt($val);
                        }
                    } catch (\Throwable $e) {}
                    return $val;
                };

                $nom = trim($descifrar(str_replace(' (Plain)', '', $alumnoGlobal->nombre ?? '')));
                $pat = trim($descifrar(str_replace(' (Plain)', '', $alumnoGlobal->apellido_paterno ?? '')));

                $completo = trim("{$nom} {$pat}");
                if (!empty($completo)) {
                    $alumnoNombreCompleto = $completo;
                    $l1 = mb_substr($nom, 0, 1);
                    $l2 = !empty($pat) ? mb_substr($pat, 0, 1) : mb_substr($nom, 1, 1);
                    $alumnoIniciales = mb_strtoupper($l1 . $l2);
                }

                $semestreAlumno = $alumnoGlobal->semestre ?? 0;

                $proyectoGlobal = \Illuminate\Support\Facades\DB::table('proyectos_titulacion')
                    ->join('proyecto_alumno', 'proyectos_titulacion.id', '=', 'proyecto_alumno.proyecto_id')
                    ->select('proyectos_titulacion.id', 'proyectos_titulacion.estatus')
                    ->where('proyecto_alumno.alumno_id', $alumnoGlobal->id)
                    ->first();

                $votosAprobados = 0;
                if ($proyectoGlobal) {
                    $votosAprobados = \Illuminate\Support\Facades\DB::table('proyecto_jurados')
                        ->where('proyecto_id', $proyectoGlobal->id)
                        ->where('voto', 'Aprobado')
                        ->count();
                }

                $procesoHabilitado = ($semestreAlumno >= 6 && $proyectoGlobal && $proyectoGlobal->estatus === 'Aprobado' && $votosAprobados >= 2);
            }
        }
    @endphp

    <!-- HEADER PRINCIPAL -->
    <header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-3 sm:px-5 md:px-7 h-16 md:h-18 flex justify-between items-center shrink-0 z-50 relative shadow-2xs transition-colors duration-200">
        
        <!-- Lado Izquierdo: Menú Móvil + Identidad -->
        <div class="flex items-center gap-2.5 sm:gap-3.5 min-w-0">
            <button id="btn-toggle-sidebar" type="button" aria-label="Abrir menú de navegación" aria-expanded="false"
                    class="lg:hidden text-slate-600 dark:text-slate-300 hover:text-custom-primary hover:bg-slate-100 dark:hover:bg-slate-800 p-2 rounded-xl focus:outline-hidden inline-flex items-center justify-center cursor-pointer transition-colors shrink-0 active:scale-95">
                <span class="material-icons-round text-2xl">menu</span>
            </button>
            
            <a href="{{ route('indexalumnos.index') }}" class="flex items-center gap-2.5 sm:gap-3 min-w-0 group">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-custom-primary flex items-center justify-center shadow-xs shrink-0 group-hover:scale-105 transition-transform">
                    <span class="material-icons-round text-xl sm:text-2xl text-white">school</span>
                </div>
                <div class="truncate">
                    <h1 class="text-base sm:text-lg md:text-xl font-black tracking-wider leading-none text-custom-primary">SUIE</h1>
                    <p class="hidden min-[380px]:block text-[10px] md:text-xs text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest mt-0.5 truncate">
                        Portal Estudiantes
                    </p>
                </div>
            </a>
        </div>
        
        <!-- Lado Derecho: Modo Oscuro + Dropdown de Perfil -->
        <div class="flex items-center gap-2 sm:gap-3 md:gap-4 shrink-0">
            
            <!-- Botón Alternar Modo Oscuro -->
            <button id="btn-theme-toggle" type="button" aria-label="Cambiar tema claro/oscuro" title="Cambiar tema"
                    class="p-2 sm:p-2.5 text-slate-500 dark:text-slate-400 hover:text-custom-primary hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all cursor-pointer border border-slate-200 dark:border-slate-700/80 shadow-3xs active:scale-95">
                <span id="theme-icon-light" class="material-icons-round text-lg sm:text-xl hidden dark:block text-amber-400">light_mode</span>
                <span id="theme-icon-dark" class="material-icons-round text-lg sm:text-xl block dark:hidden text-slate-600">dark_mode</span>
            </button>

            <!-- Dropdown Unificado de Alumno -->
            <div class="relative" id="user-menu-container">
                <button id="btn-user-dropdown" type="button" aria-haspopup="true" aria-expanded="false"
                        class="flex items-center gap-2.5 p-1 sm:p-1.5 md:pr-3 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800/80 border border-transparent hover:border-slate-200 dark:hover:border-slate-700 transition-all cursor-pointer select-none active:scale-98">
                    
                    <div class="w-9 h-9 md:w-10 md:h-10 bg-slate-100 dark:bg-slate-800 text-custom-primary font-black rounded-xl flex items-center justify-center border border-slate-200 dark:border-slate-700 text-xs md:text-sm shrink-0 shadow-3xs">
                        {{ $alumnoIniciales }}
                    </div>

                    <div class="text-left hidden sm:block max-w-[160px] md:max-w-[200px]">
                        <p class="text-xs md:text-sm font-bold text-slate-900 dark:text-slate-100 leading-tight truncate">
                            {{ $alumnoNombreCompleto }}
                        </p>
                        <p class="text-[10px] text-custom-primary font-bold uppercase tracking-wider truncate">
                            @yield('grupo_badge', 'Estudiante Activo')
                        </p>
                    </div>

                    <span class="material-icons-round text-base text-slate-400 hidden sm:block">expand_more</span>
                </button>

                <!-- Menú Desplegable Responsivo -->
                <div id="user-dropdown-menu" 
                     class="hidden absolute right-0 mt-2 w-64 max-w-[calc(100vw-1.5rem)] bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800 p-2 space-y-1.5 z-50">
                    
                    <div class="px-3 py-2.5 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-100 dark:border-slate-700/60">
                        <p class="text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Estudiante</p>
                        <p class="text-xs font-black text-slate-900 dark:text-slate-100 mt-0.5 truncate">{{ $alumnoNombreCompleto }}</p>
                        <p class="text-[11px] font-mono text-slate-500 dark:text-slate-400 truncate">Matrícula: {{ auth()->user()->username ?? 'Sin matrícula' }}</p>
                        <span class="inline-block mt-1.5 px-2 py-0.5 text-[9px] font-black uppercase rounded-md bg-rose-50 dark:bg-rose-950/60 text-custom-primary border border-rose-200 dark:border-rose-900/60">
                            @yield('grupo_badge', 'Rol: Estudiante')
                        </span>
                    </div>

                    <!-- Enlace directo a Google Authenticator 2FA -->
                    @if(Route::has('2fa.setup') && !empty(auth()->user()->google_id))
                        <a href="{{ route('2fa.setup') }}" 
                           class="flex items-center gap-2.5 px-3 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                            <span class="material-icons-round text-base text-custom-primary">phonelink_lock</span>
                            <span>Google Authenticator (2FA)</span>
                        </a>
                    @endif

                    <div class="border-t border-slate-100 dark:border-slate-800 pt-1">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center gap-2.5 px-3 py-2.5 text-xs font-bold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition-colors cursor-pointer">
                                <span class="material-icons-round text-base">logout</span>
                                <span>Cerrar Sesión</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </header>

    <!-- Barra de contexto de grupo/semestre exclusiva para celulares (< 640px) -->
    @if(View::hasSection('grupo_badge'))
        <div class="sm:hidden bg-white dark:bg-slate-900/90 border-b border-slate-200 dark:border-slate-800 px-3 py-1.5 flex items-center justify-center shrink-0">
            <span class="inline-flex items-center px-2.5 py-0.5 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900/80 text-custom-primary text-[10px] font-black rounded-lg uppercase tracking-wider truncate max-w-full">
                @yield('grupo_badge')
            </span>
        </div>
    @endif

    <!-- CONTENEDOR INFERIOR: SIDEBAR + ÁREA PRINCIPAL -->
    <div class="flex-1 flex overflow-hidden relative min-h-0">
        
        <!-- OVERLAY MÓVIL / TABLET -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-40 transition-opacity duration-300 opacity-0 pointer-events-none lg:hidden"></div>
        
        <!-- SIDEBAR (Drawer en móvil/tablet, fijo en desktop lg+) -->
        <aside id="sidebar-menu" 
               class="fixed lg:static inset-y-0 left-0 w-72 max-w-[82vw] bg-slate-900 dark:bg-slate-950 text-slate-300 flex flex-col h-[100dvh] lg:h-full shrink-0 border-r border-slate-800 dark:border-slate-800/80 z-50 lg:z-30 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-2xl lg:shadow-none">
            
            <!-- Cabecera del Sidebar exclusiva para Móvil / Tablet -->
            <div class="lg:hidden flex items-center justify-between px-5 h-16 border-b border-slate-800 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-custom-primary flex items-center justify-center">
                        <span class="material-icons-round text-lg text-white">school</span>
                    </div>
                    <span class="font-black text-white tracking-wider text-base">MENÚ SUIE</span>
                </div>
                <button id="btn-close-sidebar" type="button" aria-label="Cerrar menú"
                        class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-colors cursor-pointer">
                    <span class="material-icons-round text-xl">close</span>
                </button>
            </div>

            <!-- Enlaces de Navegación -->
            <div class="p-4 space-y-6 overflow-y-auto flex-1">
                
                <!-- SECCIÓN 1: GENERAL -->
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-3 block mb-2.5">General</span>
                    <nav class="space-y-1">
                        <a href="{{ route('indexalumnos.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('indexalumnos.index') ? 'bg-custom-primary text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100 text-slate-300' }}">
                            <span class="material-icons-round text-lg shrink-0">dashboard</span>
                            <span class="truncate">Panel de Inicio</span>
                        </a>
                    </nav>
                </div>

                <!-- SECCIÓN 2: SERVICIOS ESCOLARES -->
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-3 block mb-2.5">Servicios Escolares</span>
                    <nav class="space-y-1">
                        <a href="{{ route('indexmaterias.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('indexmaterias.index') ? 'bg-custom-primary text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100 text-slate-300' }}">
                            <span class="material-icons-round text-lg shrink-0">auto_stories</span>
                            <span class="truncate">Mis Materias</span>
                        </a>
                        <a href="{{ route('alumnoPagos.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('alumnoPagos.index') ? 'bg-custom-primary text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100 text-slate-300' }}">
                            <span class="material-icons-round text-lg shrink-0">payments</span>
                            <span class="truncate">Control de Pagos</span>
                        </a>

                        <!-- 1. MÓDULO: PROYECTOS DE TITULACIÓN (Solo 6to Semestre) -->
                        @if($semestreAlumno >= 6)
                            <a href="{{ route('titulacion.index') }}" 
                               class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('titulacion.*') ? 'bg-custom-primary text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100 text-slate-300' }}">
                                <span class="material-icons-round text-lg shrink-0">history_edu</span>
                                <span class="truncate">Proyectos de Titulación</span>
                            </a>
                        @else
                            <button type="button" onclick="mostrarAlertaBloqueo('ModuloBloqueadoSemestre')" 
                                    class="w-full flex items-center justify-between gap-2 px-3.5 py-3 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-800/50 dark:hover:bg-slate-900/50 hover:text-slate-400 transition-colors cursor-pointer">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="material-icons-round text-lg shrink-0">history_edu</span>
                                    <span class="truncate">Proyectos de Titulación</span>
                                </div>
                                <span class="material-icons-round text-sm text-amber-500 shrink-0">lock</span>
                            </button>
                        @endif

                        <!-- 2. MÓDULO: PROCESO DE TITULACIÓN (Solo si el Proyecto está Aprobado) -->
                        @if($procesoHabilitado)
                            <a href="{{ route('proceso.titulacion.index') }}" 
                               class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('proceso.titulacion.*') ? 'bg-custom-primary text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100 text-slate-300' }}">
                                <span class="material-icons-round text-lg shrink-0">assignment_turned_in</span>
                                <span class="truncate">Proceso de Titulación</span>
                            </a>
                        @else
                            <button type="button" onclick="mostrarAlertaBloqueo('ModuloBloqueadoProyecto')" 
                                    class="w-full flex items-center justify-between gap-2 px-3.5 py-3 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-800/50 dark:hover:bg-slate-900/50 hover:text-slate-400 transition-colors cursor-pointer">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="material-icons-round text-lg shrink-0">assignment_turned_in</span>
                                    <span class="truncate">Proceso de Titulación</span>
                                </div>
                                <span class="material-icons-round text-sm text-amber-500 shrink-0">lock</span>
                            </button>
                        @endif

                    </nav>
                </div>

                <!-- SECCIÓN 3: SEGURIDAD Y CUENTA -->
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-3 block mb-2.5">Seguridad</span>
                    <nav class="space-y-1">
                        @if(Route::has('2fa.setup'))
                            <a href="{{ route('2fa.setup') }}" 
                               class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('2fa.*') ? 'bg-custom-primary text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100 text-slate-300' }}">
                                <span class="material-icons-round text-lg shrink-0">phonelink_lock</span>
                                <span class="truncate">Google Authenticator</span>
                            </a>
                        @endif
                    </nav>
                </div>

            </div>

            <!-- Pie del Sidebar (Acceso rápido en móviles) -->
            <div class="p-4 border-t border-slate-800/90 shrink-0 lg:hidden">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-xs font-extrabold uppercase tracking-wider text-rose-400 bg-rose-950/30 hover:bg-rose-950/60 border border-rose-900/50 transition-colors cursor-pointer">
                        <span class="material-icons-round text-base">logout</span>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- CONTENEDOR PRINCIPAL DINÁMICO -->
        <div id="main-scroll-container" class="flex-1 overflow-y-auto overflow-x-hidden bg-slate-50 dark:bg-slate-950 w-full min-w-0 transition-colors duration-200 flex flex-col">
            <div class="flex-1 w-full">
                @yield('content')
            </div>
        </div>

    </div>

    <!-- MODAL DE ALERTA DE MÓDULO BLOQUEADO -->
    <div id="modalBloqueo" onclick="if(event.target === this) cerrarAlertaBloqueo()" 
         class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs z-[60] flex items-center justify-center p-4 hidden">
        <div class="bg-white dark:bg-slate-900 w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden p-6 md:p-7 text-center space-y-4 border border-slate-100 dark:border-slate-800 transition-colors duration-200">
            <div class="w-14 h-14 bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 rounded-2xl flex items-center justify-center mx-auto border border-amber-100 dark:border-amber-900/60 shadow-3xs">
                <span class="material-icons-round text-3xl">lock</span>
            </div>

            <div class="space-y-1.5">
                <h3 id="tituloBloqueo" class="text-base font-extrabold text-slate-900 dark:text-slate-100">Acceso Restringido</h3>
                <p id="mensajeBloqueo" class="text-xs md:text-sm text-slate-500 dark:text-slate-400 leading-relaxed"></p>
            </div>

            <button type="button" onclick="cerrarAlertaBloqueo()" 
                    class="w-full py-3 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold rounded-2xl transition-all cursor-pointer text-xs md:text-sm shadow-xs active:scale-98">
                Entendido
            </button>
        </div>
    </div>

    <!-- SCRIPTS DE CONTROL -->
    <script>
        let cerrarSidebarGlobal = function() {};

        document.addEventListener('DOMContentLoaded', function () {
            // 1. Sidebar móvil y tablet (< 1024px)
            const btnToggle = document.getElementById('btn-toggle-sidebar');
            const btnClose  = document.getElementById('btn-close-sidebar');
            const sidebar   = document.getElementById('sidebar-menu');
            const overlay   = document.getElementById('sidebar-overlay');

            function setSidebarState(open) {
                if (!sidebar || !overlay) return;
                if (open) {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('opacity-0', 'pointer-events-none');
                    if (btnToggle) btnToggle.setAttribute('aria-expanded', 'true');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('opacity-0', 'pointer-events-none');
                    if (btnToggle) btnToggle.setAttribute('aria-expanded', 'false');
                }
            }

            cerrarSidebarGlobal = () => setSidebarState(false);

            if (btnToggle && sidebar && overlay) {
                btnToggle.addEventListener('click', function () {
                    const isClosed = sidebar.classList.contains('-translate-x-full');
                    setSidebarState(isClosed);
                });
                overlay.addEventListener('click', () => setSidebarState(false));
                if (btnClose) {
                    btnClose.addEventListener('click', () => setSidebarState(false));
                }
            }

            // 2. Dropdown de Perfil
            const userBtn  = document.getElementById('btn-user-dropdown');
            const userMenu = document.getElementById('user-dropdown-menu');

            if (userBtn && userMenu) {
                userBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const willOpen = userMenu.classList.contains('hidden');
                    userMenu.classList.toggle('hidden');
                    userBtn.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
                });

                document.addEventListener('click', function(e) {
                    if (!userMenu.contains(e.target) && !userBtn.contains(e.target)) {
                        userMenu.classList.add('hidden');
                        userBtn.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            // 3. Alternar Modo Oscuro con persistencia
            const btnTheme = document.getElementById('btn-theme-toggle');
            if (btnTheme) {
                btnTheme.addEventListener('click', function () {
                    const isDark = document.documentElement.classList.toggle('dark');
                    const selectedTheme = isDark ? 'dark' : 'light';
                    const userKey = 'suie_theme_u_{{ auth()->id() ?? "guest" }}';

                    localStorage.setItem(userKey, selectedTheme);

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

            // 4. Tecla Escape o redimensión para cerrar modales y menús
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (userMenu) {
                        userMenu.classList.add('hidden');
                        if (userBtn) userBtn.setAttribute('aria-expanded', 'false');
                    }
                    cerrarAlertaBloqueo();
                    if (sidebar && !sidebar.classList.contains('-translate-x-full') && window.innerWidth < 1024) {
                        setSidebarState(false);
                    }
                }
            });

            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    setSidebarState(false);
                }
            });
        });

        function mostrarAlertaBloqueo(tipo) {
            // Cierra el menú lateral móvil si estaba abierto para enfocar el modal
            if (window.innerWidth < 1024) {
                cerrarSidebarGlobal();
            }

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
            const modal = document.getElementById('modalBloqueo');
            if (modal) modal.classList.add('hidden');
        }
    </script>
</body>
</html>
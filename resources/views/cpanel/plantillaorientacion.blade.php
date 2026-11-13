<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SUIE Orientación - @yield('title')</title>
    
    <!-- Tailwind CSS v4 Browser -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <!-- Configuración para compatibilidad con clase .dark en Tailwind v4 -->
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

        /* 3. Sobrescritura forzada de clases estáticas heredadas */
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
        // Obtener y desencriptar de forma segura el perfil del Orientador(a) autenticado
        $oeNombreCompleto = 'Orientador(a)';
        $oeIniciales = 'OE';

        if (auth()->check()) {
            $oePerfil = null;
            try {
                foreach (['orientador', 'orientadores', 'orientacion'] as $tablaOe) {
                    if (\Illuminate\Support\Facades\Schema::hasTable($tablaOe)) {
                        $oePerfil = \Illuminate\Support\Facades\DB::table($tablaOe)
                            ->where('usuario_id', auth()->id())
                            ->first();
                        if ($oePerfil) break;
                    }
                }
            } catch (\Throwable $e) {}

            if ($oePerfil) {
                $descifrar = function ($val) {
                    if (empty($val)) return '';
                    try {
                        if (is_string($val) && (str_starts_with($val, 'ey') || strlen($val) > 50)) {
                            return decrypt($val);
                        }
                    } catch (\Throwable $e) {}
                    return $val;
                };

                $nom = trim($descifrar($oePerfil->nombre ?? ''));
                $pat = trim($descifrar($oePerfil->apaterno ?? ($oePerfil->apellido_paterno ?? '')));

                $completo = trim("{$nom} {$pat}");
                if (!empty($completo)) {
                    $oeNombreCompleto = $completo;
                    $l1 = mb_substr($nom, 0, 1);
                    $l2 = !empty($pat) ? mb_substr($pat, 0, 1) : mb_substr($nom, 1, 1);
                    $oeIniciales = mb_strtoupper($l1 . $l2);
                }
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
            
            <a href="{{ route('asistencias.criticas') }}" class="flex items-center gap-2.5 sm:gap-3 min-w-0 group">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-custom-primary flex items-center justify-center shadow-xs shrink-0 group-hover:scale-105 transition-transform">
                    <span class="material-icons-round text-xl sm:text-2xl text-white">psychology</span>
                </div>
                <div class="truncate">
                    <h1 class="text-base sm:text-lg md:text-xl font-black tracking-wider leading-none text-custom-primary">SUIE</h1>
                    <p class="hidden min-[380px]:block text-[10px] md:text-xs text-slate-400 dark:text-slate-500 font-extrabold uppercase tracking-widest mt-0.5 truncate">
                        Orientación Educativa
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

            <!-- Dropdown Unificado de Usuario -->
            @if(auth()->check())
                <div class="relative" id="user-menu-container">
                    <button id="btn-user-dropdown" type="button" aria-haspopup="true" aria-expanded="false"
                            class="flex items-center gap-2.5 p-1 sm:p-1.5 md:pr-3 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800/80 border border-transparent hover:border-slate-200 dark:hover:border-slate-700 transition-all cursor-pointer select-none active:scale-98">
                        
                        <div class="w-9 h-9 md:w-10 md:h-10 bg-cyan-50 dark:bg-cyan-950/60 text-cyan-700 dark:text-cyan-400 font-black rounded-xl flex items-center justify-center border border-cyan-200 dark:border-cyan-800 text-xs md:text-sm shrink-0 shadow-3xs">
                            {{ $oeIniciales }}
                        </div>

                        <div class="text-left hidden sm:block max-w-[160px] md:max-w-[200px]">
                            <p class="text-xs md:text-sm font-bold text-slate-900 dark:text-slate-100 leading-tight truncate">
                                {{ $oeNombreCompleto }}
                            </p>
                            <p class="text-[10px] text-custom-primary font-bold uppercase tracking-wider font-mono truncate">
                                {{ auth()->user()->username ?? 'ORIENTACION' }}
                            </p>
                        </div>

                        <span class="material-icons-round text-base text-slate-400 hidden sm:block">expand_more</span>
                    </button>

                    <!-- Menú Desplegable Responsivo -->
                    <div id="user-dropdown-menu" 
                         class="hidden absolute right-0 mt-2 w-64 max-w-[calc(100vw-1.5rem)] bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800 p-2 space-y-1.5 z-50">
                        
                        <div class="px-3 py-2.5 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-100 dark:border-slate-700/60">
                            <p class="text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Módulo Operativo</p>
                            <p class="text-xs font-black text-slate-900 dark:text-slate-100 mt-0.5 truncate">{{ $oeNombreCompleto }}</p>
                            <p class="text-[11px] font-mono text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->username }}</p>
                            <span class="inline-block mt-1.5 px-2 py-0.5 text-[9px] font-black uppercase rounded-md bg-cyan-50 dark:bg-cyan-950/60 text-cyan-700 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-800/80">
                                Rol: Orientación Educativa
                            </span>
                        </div>

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
            @endif

        </div>
    </header>

    <!-- CONTENEDOR INFERIOR: SIDEBAR + ÁREA PRINCIPAL -->
    <div class="flex-1 flex overflow-hidden relative min-h-0">
        
        <!-- OVERLAY MÓVIL / TABLET -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-40 transition-opacity duration-300 opacity-0 pointer-events-none lg:hidden"></div>
        
        <!-- SIDEBAR DE NAVEGACIÓN (Drawer en móvil/tablet, fijo en desktop lg+) -->
        <aside id="sidebar-menu" 
               class="fixed lg:static inset-y-0 left-0 w-72 max-w-[82vw] bg-slate-900 dark:bg-slate-950 text-slate-300 flex flex-col h-[100dvh] lg:h-full shrink-0 border-r border-slate-800 dark:border-slate-800/80 z-50 lg:z-30 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-2xl lg:shadow-none">
            
            <!-- Cabecera del Sidebar exclusiva para Móvil / Tablet -->
            <div class="lg:hidden flex items-center justify-between px-5 h-16 border-b border-slate-800 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-custom-primary flex items-center justify-center">
                        <span class="material-icons-round text-lg text-white">psychology</span>
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
                
                <!-- SECCIÓN: MONITOREO Y SEGUIMIENTO -->
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-3 block mb-2.5">Acompañamiento Escolar</span>
                    <nav class="space-y-1">
                        
                        <!-- 1. Alertas de Asistencias Críticas -->
                        <a href="{{ route('asistencias.criticas') }}" 
                           class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('asistencias.criticas') ? 'bg-custom-primary text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100 text-slate-300' }}">
                            <span class="material-icons-round text-lg shrink-0">warning_amber</span>
                            <span class="truncate">Alertas de Asistencia</span>
                        </a>

                        <!-- 2. Estadísticas y Gráficas de Asistencia -->
                        @if(Route::has('orientacion.graficas.index'))
                            <a href="{{ route('orientacion.graficas.index') }}" 
                               class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('orientacion.graficas.*') ? 'bg-custom-primary text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100 text-slate-300' }}">
                                <span class="material-icons-round text-lg shrink-0">bar_chart</span>
                                <span class="truncate">Gráficas y Estadísticas</span>
                            </a>
                        @else
                            <a href="{{ url('/orientacion/graficas') }}" 
                               class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-bold transition-all {{ request()->is('*orientacion/graficas*') ? 'bg-custom-primary text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100 text-slate-300' }}">
                                <span class="material-icons-round text-lg shrink-0">bar_chart</span>
                                <span class="truncate">Gráficas y Estadísticas</span>
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

        <!-- ÁREA DE CONTENIDO DINÁMICO -->
        <div id="main-scroll-container" class="flex-1 overflow-y-auto overflow-x-hidden bg-slate-50 dark:bg-slate-950 w-full min-w-0 transition-colors duration-200 flex flex-col">
            <div class="flex-1 w-full">
                @yield('content')
            </div>
        </div>

    </div>

    <!-- SCRIPTS DE CONTROL -->
    <script>
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

            // 3. Alternancia y Persistencia del Tema Oscuro
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

            // 4. Cerrar menús con tecla Escape o al redimensionar a escritorio
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (userMenu) {
                        userMenu.classList.add('hidden');
                        if (userBtn) userBtn.setAttribute('aria-expanded', 'false');
                    }
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
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SUIE Docente - @yield('title')</title>
    
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
<body class="bg-slate-50 dark:bg-slate-950 font-sans text-slate-800 dark:text-slate-100 text-sm md:text-base antialiased transition-colors duration-200 min-h-[100dvh] flex flex-col">
    <div class="min-h-[100dvh] flex flex-col bg-slate-50 dark:bg-slate-950 overflow-x-hidden">
        
        <!-- HEADER / TOPBAR -->
        <header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-xs px-3 sm:px-5 md:px-7 h-16 md:h-18 flex justify-between items-center sticky top-0 z-40 transition-colors duration-200">
            
            <!-- Lado Izquierdo: Identidad y Badge Opcional -->
            <div class="flex items-center gap-2.5 sm:gap-3.5 min-w-0">
                <a href="{{ Route::has('docente.dashboard') ? route('docente.dashboard') : url('/') }}" class="flex items-center gap-2.5 sm:gap-3 min-w-0 group">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-custom-primary flex items-center justify-center shadow-xs shrink-0 group-hover:scale-105 transition-transform">
                        <span class="material-icons-round text-xl sm:text-2xl text-white">hub</span>
                    </div>
                    <div class="truncate">
                        <h1 class="text-base sm:text-lg md:text-xl font-black tracking-wider leading-none text-custom-primary">SUIE</h1>
                        <p class="hidden min-[380px]:block text-[10px] md:text-xs text-slate-400 dark:text-slate-500 font-extrabold uppercase tracking-widest mt-0.5 truncate">
                            Portal Institucional Docente
                        </p>
                    </div>
                </a>

                @if(View::hasSection('grupo_badge'))
                    <div class="hidden lg:inline-flex items-center px-3 py-1 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900/80 text-custom-primary text-xs font-black rounded-lg uppercase tracking-wider ml-2 truncate max-w-xs">
                        @yield('grupo_badge')
                    </div>
                @endif
            </div>
            
            <!-- Lado Derecho: Tema y Menú Desplegable -->
            <div class="flex items-center gap-2 sm:gap-3 md:gap-4 shrink-0">
                
                <!-- Botón Alternar Modo Oscuro -->
                <button id="btn-theme-toggle" type="button" aria-label="Alternar tema claro/oscuro" title="Cambiar tema"
                        class="p-2 sm:p-2.5 text-slate-500 dark:text-slate-400 hover:text-custom-primary hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all cursor-pointer border border-slate-200 dark:border-slate-700/80 shadow-3xs active:scale-95">
                    <span id="theme-icon-light" class="material-icons-round text-lg sm:text-xl hidden dark:block text-amber-400">light_mode</span>
                    <span id="theme-icon-dark" class="material-icons-round text-lg sm:text-xl block dark:hidden text-slate-600">dark_mode</span>
                </button>

                @if(auth()->check())
                    @php
                        $docenteNombre = '';
                        $docenteApellido = '';
                        $inicialesAvatar = '';

                        if (auth()->user()->docente) {
                            $rawNombre = str_replace(' (Plain)', '', auth()->user()->docente->nombre ?? '');
                            $rawApellido = str_replace(' (Plain)', '', auth()->user()->docente->apellido_paterno ?? '');

                            $descifrar = function ($val) {
                                if (empty($val)) return '';
                                try {
                                    if (is_string($val) && (str_starts_with($val, 'ey') || strlen($val) > 50)) {
                                        return decrypt($val);
                                    }
                                } catch (\Throwable $e) {}
                                return $val;
                            };

                            $docenteNombre = trim($descifrar($rawNombre));
                            $docenteApellido = trim($descifrar($rawApellido));

                            $iniN = !empty($docenteNombre) ? mb_substr($docenteNombre, 0, 1) : 'P';
                            $iniA = !empty($docenteApellido) ? mb_substr($docenteApellido, 0, 1) : 'R';
                            $inicialesAvatar = mb_strtoupper($iniN . $iniA);
                        } else {
                            $inicialesAvatar = mb_strtoupper(mb_substr(auth()->user()->username ?? 'US', 0, 2));
                        }
                    @endphp

                    <!-- Dropdown de Perfil -->
                    <div class="relative" id="profile-menu-container">
                        <button id="profileDropdownBtn" type="button" aria-haspopup="true" aria-expanded="false"
                                class="flex items-center gap-2.5 p-1 sm:p-1.5 md:pr-3 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800/80 border border-transparent hover:border-slate-200 dark:hover:border-slate-700 transition-all cursor-pointer select-none active:scale-98">
                            
                            <div class="w-9 h-9 md:w-10 md:h-10 bg-slate-100 dark:bg-slate-800 text-custom-primary font-black rounded-xl flex items-center justify-center border border-slate-200 dark:border-slate-700 text-xs md:text-sm shrink-0 shadow-3xs">
                                {{ $inicialesAvatar }}
                            </div>

                            <div class="text-left hidden sm:block max-w-[160px] md:max-w-[200px]">
                                <p class="text-xs md:text-sm font-bold text-slate-900 dark:text-slate-100 leading-tight truncate">
                                    @if(auth()->user()->docente)
                                        Prof. {{ $docenteNombre }} {{ $docenteApellido }}
                                    @else
                                        {{ auth()->user()->rol ?? 'Usuario' }}
                                    @endif
                                </p>
                                <p class="text-[10px] text-custom-primary font-bold uppercase font-mono tracking-wider mt-0.5 truncate">
                                    ID: {{ auth()->user()->username }}
                                </p>
                            </div>

                            <span class="material-icons-round text-base text-slate-400 hidden sm:block">expand_more</span>
                        </button>

                        <!-- Menú Desplegable Responsivo -->
                        <div id="profileDropdown" 
                             class="hidden absolute right-0 mt-2 w-64 max-w-[calc(100vw-1.5rem)] bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800 p-2 space-y-1.5 z-50">
                            
                            <!-- Tarjeta interna con información del usuario -->
                            <div class="px-3 py-2.5 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-100 dark:border-slate-700/60">
                                <p class="text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Identidad Registrada</p>
                                <p class="text-xs font-black text-slate-900 dark:text-slate-100 mt-0.5 truncate">
                                    @if(auth()->user()->docente)
                                        {{ $docenteNombre }} {{ $docenteApellido }}
                                    @else
                                        {{ auth()->user()->username }}
                                    @endif
                                </p>
                                <p class="text-[11px] font-mono text-slate-500 dark:text-slate-400 truncate">ID: {{ auth()->user()->username }}</p>
                                <span class="inline-block mt-1.5 px-2 py-0.5 text-[9px] font-black uppercase rounded-md bg-rose-50 dark:bg-rose-950/60 text-custom-primary border border-rose-200 dark:border-rose-900/60">
                                    Rol: {{ auth()->user()->rol ?? 'Docente' }}
                                </span>
                            </div>

                            @if(Route::has('2fa.setup') && !empty(auth()->user()->google_id))
                                <a href="{{ route('2fa.setup') }}" 
                                   class="flex items-center gap-2.5 px-3 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                                    <span class="material-icons-round text-base text-custom-primary">phonelink_lock</span>
                                    <span>Google Authenticator (2FA)</span>
                                </a>
                            @endif

                            <!-- Formulario de Cierre de Sesión -->
                            <form action="{{ route('logout') }}" method="POST" class="border-t border-slate-100 dark:border-slate-800 pt-1">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center gap-2.5 px-3 py-2.5 text-xs font-bold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition-colors cursor-pointer">
                                    <span class="material-icons-round text-base">logout</span>
                                    <span>Cerrar Sesión</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400">Usuario Invitado</p>
                    </div>
                @endif

            </div>
        </header>

        <!-- Barra de contexto de grupo/materia exclusiva para Móvil/Tablet -->
        @if(View::hasSection('grupo_badge'))
            <div class="lg:hidden bg-white dark:bg-slate-900/90 border-b border-slate-200 dark:border-slate-800 px-3 sm:px-5 py-2 flex items-center justify-center">
                <span class="inline-flex items-center px-3 py-1 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900/80 text-custom-primary text-[11px] font-black rounded-lg uppercase tracking-wider truncate max-w-full">
                    @yield('grupo_badge')
                </span>
            </div>
        @endif
        
        <!-- CONTENIDO PRINCIPAL -->
        <div class="flex-1 w-full min-w-0 bg-slate-50 dark:bg-slate-950 flex flex-col">
            @yield('content')
        </div>
    </div>

    <!-- SCRIPTS DE CONTROL -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Manejo del Dropdown de Perfil
            const dropdownBtn = document.getElementById('profileDropdownBtn');
            const dropdownMenu = document.getElementById('profileDropdown');

            if (dropdownBtn && dropdownMenu) {
                dropdownBtn.addEventListener('click', function (event) {
                    event.stopPropagation();
                    const willOpen = dropdownMenu.classList.contains('hidden');
                    dropdownMenu.classList.toggle('hidden');
                    dropdownBtn.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
                });

                document.addEventListener('click', function (event) {
                    if (!dropdownMenu.contains(event.target) && !dropdownBtn.contains(event.target)) {
                        dropdownMenu.classList.add('hidden');
                        dropdownBtn.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            // 2. Alternancia y Persistencia del Tema Oscuro por Usuario
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

            // 3. Accesibilidad: Cerrar menú con tecla Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && dropdownMenu) {
                    dropdownMenu.classList.add('hidden');
                    if (dropdownBtn) dropdownBtn.setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>
</body>
</html>
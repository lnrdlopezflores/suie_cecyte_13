<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <!-- Inyección Dinámica y Sobrescritura Global de Colores SUIE -->
    <style>
        :root {
            --color-primary: {{ $colorPrimario ?? '#841B44' }};
            --color-primary-hover: {{ $colorHover ?? '#681535' }};
            --color-primary-light: {{ $colorLight ?? '#fdf2f4' }};
        }

        /* 1. Fondo fijo inalterable del layout */
        body {
            background-color: #f8fafc !important; /* slate-50 */
        }
        html.dark body {
            background-color: #020617 !important; /* slate-950 */
        }

        /* 2. Clases utilitarias directas */
        .bg-custom-primary { background-color: var(--color-primary) !important; color: #ffffff !important; }
        .text-custom-primary { color: var(--color-primary) !important; }
        .border-custom-primary { border-color: var(--color-primary) !important; }
        .hover\:bg-custom-primary-hover:hover { background-color: var(--color-primary-hover) !important; color: #ffffff !important; }

        /* 3. Sobrescritura forzada de clases estáticas heredadas */
        [class*="bg-[#841B44]"],
        [class*="bg-\[\#841B44\]"] {
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
<body class="bg-slate-50 dark:bg-slate-950 font-sans text-slate-800 dark:text-slate-100 text-sm md:text-base antialiased transition-colors duration-200 min-h-screen flex flex-col">

    <div class="min-h-screen flex flex-col bg-slate-50 dark:bg-slate-950">
        
        <!-- HEADER PRINCIPAL -->
        <header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-xs px-4 md:px-7 h-16 md:h-18 flex justify-between items-center sticky top-0 z-40 transition-colors duration-200">
            
            <!-- Lado Izquierdo: Identidad de Orientación Educativa -->
            <div class="flex items-center space-x-3 md:space-x-4">
                <div class="w-10 h-10 rounded-xl bg-custom-primary flex items-center justify-center shadow-xs shrink-0">
                    <span class="material-icons-round text-2xl text-white">psychology</span>
                </div>
                <div>
                    <h1 class="text-lg md:text-xl font-black tracking-wider leading-none text-custom-primary">SUIE</h1>
                    <p class="text-[10px] md:text-xs text-slate-400 dark:text-slate-500 font-extrabold uppercase tracking-widest mt-0.5">Orientación Educativa</p>
                </div>
            </div>

            <!-- Lado Derecho: Modo Oscuro + Dropdown de Perfil -->
            <div class="flex items-center space-x-3 md:space-x-4">
                
                <!-- Botón Alternar Modo Oscuro -->
                <button id="btn-theme-toggle" type="button" aria-label="Alternar tema"
                        class="p-2.5 text-slate-500 dark:text-slate-400 hover:text-custom-primary hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all cursor-pointer border border-slate-200 dark:border-slate-700/80 shadow-3xs">
                    <span id="theme-icon-light" class="material-icons-round text-xl hidden dark:block text-amber-400">light_mode</span>
                    <span id="theme-icon-dark" class="material-icons-round text-xl block dark:hidden text-slate-600">dark_mode</span>
                </button>

                @if(auth()->check())
                    <!-- Dropdown de Usuario -->
                    <div class="relative" id="user-menu-container">
                        <button id="btn-user-dropdown" type="button" 
                                class="flex items-center gap-3 p-1.5 md:pr-3 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800/80 border border-transparent hover:border-slate-200 dark:hover:border-slate-700 transition-all cursor-pointer select-none">
                            
                            <div class="w-9 h-9 md:w-10 md:h-10 bg-slate-100 dark:bg-slate-800 text-custom-primary font-black rounded-xl flex items-center justify-center border border-slate-200 dark:border-slate-700 text-xs md:text-sm shrink-0 shadow-3xs">
                                OE
                            </div>

                            <div class="text-left hidden sm:block">
                                <p class="text-xs md:text-sm font-bold text-slate-900 dark:text-slate-100 leading-tight">
                                    Orientador(a)
                                </p>
                                <p class="text-[10px] text-custom-primary font-bold uppercase tracking-wider font-mono">
                                    {{ auth()->user()->username ?? 'ORIENTADOR' }}
                                </p>
                            </div>

                            <span class="material-icons-round text-base text-slate-400 hidden sm:block">expand_more</span>
                        </button>

                        <!-- Menú Flotante -->
                        <div id="user-dropdown-menu" 
                             class="hidden absolute right-0 mt-2 w-64 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800 p-2 space-y-1.5 z-50">
                            
                            <div class="px-3 py-2.5 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-100 dark:border-slate-700/60">
                                <p class="text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Sesión iniciada como</p>
                                <p class="text-xs font-black text-slate-900 dark:text-slate-100 mt-0.5 truncate">{{ auth()->user()->username }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 text-[9px] font-black uppercase rounded-md bg-rose-50 dark:bg-rose-950/60 text-custom-primary border border-rose-200 dark:border-rose-900/60">
                                    Rol: Orientación Educativa
                                </span>
                            </div>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-bold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition-colors cursor-pointer">
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

        <!-- ÁREA DE CONTENIDO DINÁMICO -->
        <main class="flex-1 w-full mx-auto bg-slate-50 dark:bg-slate-950">
            @yield('content')
        </main>
        
    </div>

    <!-- SCRIPTS DE CONTROL -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Dropdown de Perfil
            const userBtn = document.getElementById('btn-user-dropdown');
            const userMenu = document.getElementById('user-dropdown-menu');

            if (userBtn && userMenu) {
                userBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userMenu.classList.toggle('hidden');
                });

                document.addEventListener('click', function(e) {
                    if (!userMenu.contains(e.target) && !userBtn.contains(e.target)) {
                        userMenu.classList.add('hidden');
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
                if (e.key === 'Escape' && userMenu) {
                    userMenu.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>
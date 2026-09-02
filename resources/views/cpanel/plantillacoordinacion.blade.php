<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coordinación Académica SUIE - @yield('title')</title>
    
    <!-- Tailwind CSS v4 Browser -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <!-- Configuración para compatibilidad con clase .dark en Tailwind v4 -->
    <style type="text/tailwindcss">
        @custom-variant dark (&:where(.dark, .dark *));
    </style>

    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

        .bg-custom-primary { background-color: var(--color-primary) !important; color: #ffffff !important; }
        .text-custom-primary { color: var(--color-primary) !important; }
        .border-custom-primary { border-color: var(--color-primary) !important; }
        .hover\:bg-custom-primary-hover:hover { background-color: var(--color-primary-hover) !important; color: #ffffff !important; }

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

        [class*="bg-rose-50"] {
            background-color: var(--color-primary-light) !important;
        }
    </style>
</head>
<body class="bg-slate-100 dark:bg-slate-950 font-sans text-slate-800 dark:text-slate-100 text-sm h-screen flex flex-col overflow-hidden transition-colors duration-200">

    <!-- HEADER PRINCIPAL -->
    <header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-4 md:px-7 h-16 md:h-18 flex justify-between items-center shrink-0 z-50 relative shadow-2xs transition-colors duration-200">
        
        <!-- Lado Izquierdo: Botón Menú Móvil + Identidad -->
        <div class="flex items-center space-x-3 md:space-x-4">
            <button id="btn-toggle-sidebar" type="button" aria-label="Abrir menú" 
                    class="md:hidden text-slate-600 dark:text-slate-300 hover:text-custom-primary hover:bg-slate-100 dark:hover:bg-slate-800 p-2 rounded-xl focus:outline-hidden inline-flex items-center cursor-pointer transition-colors">
                <span class="material-icons-round text-2xl">menu</span>
            </button>
            
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-custom-primary flex items-center justify-center shadow-xs">
                    <span class="material-icons-round text-2xl text-white">admin_panel_settings</span>
                </div>
                <div>
                    <h1 class="text-lg md:text-xl font-black tracking-wider leading-none text-custom-primary">SUIE</h1>
                    <p class="text-[10px] md:text-xs text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest mt-0.5">Coordinación Académica</p>
                </div>
            </div>
        </div>
        
        <!-- Lado Derecho: Tema Oscuro + Menú Desplegable de Usuario -->
        <div class="flex items-center space-x-3 md:space-x-4">
            
            <!-- Botón Alternar Modo Oscuro -->
            <button id="btn-theme-toggle" type="button" aria-label="Cambiar tema"
                    class="p-2.5 text-slate-500 dark:text-slate-400 hover:text-custom-primary hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all cursor-pointer border border-slate-200 dark:border-slate-700/80 shadow-3xs">
                <span id="theme-icon-light" class="material-icons-round text-xl hidden dark:block text-amber-400">light_mode</span>
                <span id="theme-icon-dark" class="material-icons-round text-xl block dark:hidden text-slate-600">dark_mode</span>
            </button>

            <!-- Dropdown Unificado de Perfil y Acciones -->
            <div class="relative" id="user-menu-container">
                <button id="btn-user-dropdown" type="button" 
                        class="flex items-center gap-3 p-1.5 md:pr-3 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800/80 border border-transparent hover:border-slate-200 dark:hover:border-slate-700 transition-all cursor-pointer select-none">
                    
                    <div class="w-9 h-9 md:w-10 md:h-10 bg-slate-100 dark:bg-slate-800 text-custom-primary font-black rounded-xl flex items-center justify-center border border-slate-200 dark:border-slate-700 text-xs md:text-sm shrink-0 shadow-3xs">
                        {{ strtoupper(substr(auth()->user()->username ?? 'CO', 0, 2)) }}
                    </div>

                    <div class="text-left hidden sm:block">
                        <p class="text-xs md:text-sm font-bold text-slate-900 dark:text-slate-100 leading-tight">
                            {{ auth()->user()->username ?? 'Coordinador' }}
                        </p>
                        <p class="text-[10px] text-custom-primary font-bold uppercase tracking-wider">
                            Coordinación
                        </p>
                    </div>

                    <span class="material-icons-round text-base text-slate-400 hidden sm:block">expand_more</span>
                </button>

                <!-- Menú Flotante -->
                <div id="user-dropdown-menu" 
                     class="hidden absolute right-0 mt-2 w-64 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800 p-2 space-y-1.5 z-50">
                    
                    <!-- Datos de la cuenta -->
                    <div class="px-3 py-2.5 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-100 dark:border-slate-700/60">
                        <p class="text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Sesión iniciada como</p>
                        <p class="text-xs font-black text-slate-900 dark:text-slate-100 mt-0.5 truncate">{{ auth()->user()->username ?? 'Coordinador' }}</p>
                        <span class="inline-block mt-1 px-2 py-0.5 text-[9px] font-black uppercase rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/80">
                            Rol: {{ auth()->user()->rol ?? 'Coordinador' }}
                        </span>
                    </div>

                    <!-- Botón Cerrar Sesión -->
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

        </div>
    </header>

    <div class="flex-1 flex overflow-hidden relative">
        
        <!-- OVERLAY MÓVIL -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-slate-950/50 backdrop-blur-xs z-30 transition-opacity duration-300 opacity-0 pointer-events-none md:hidden"></div>
        
        <!-- SIDEBAR DE NAVEGACIÓN -->
        <aside id="sidebar-menu" 
               class="fixed md:static top-16 md:top-18 bottom-0 left-0 w-72 bg-slate-900 dark:bg-slate-950 text-slate-300 flex flex-col h-[calc(100vh-4rem)] md:h-full shrink-0 border-r border-slate-800 dark:border-slate-800/80 z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
            
            <div class="p-4 space-y-6 overflow-y-auto flex-1">
                
                <!-- SECCIÓN 1: VISTA GENERAL -->
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-3 block mb-2.5">Panel de Control</span>
                    <nav class="space-y-1">
                        <a href="{{ route('coordinador.dashboard') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('coordinador.dashboard') ? 'bg-custom-primary text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100 text-slate-300' }}">
                            <span class="material-icons-round text-base">insights</span>
                            <span>Dashboard Global</span>
                        </a>
                    </nav>
                </div>

                <!-- SECCIÓN 2: GESTIÓN ACADÉMICA -->
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-3 block mb-2.5">Supervisión y Proyectos</span>
                    <nav class="space-y-1">
                        <a href="{{ route('coordinador.cargas.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('coordinador.cargas.*') ? 'bg-custom-primary text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100 text-slate-300' }}">
                            <span class="material-icons-round text-base">menu_book</span>
                            <span>Cargas Académicas</span>
                        </a>
                        <a href="{{ route('coordinador.proyectos.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('coordinador.proyectos.*') ? 'bg-custom-primary text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100 text-slate-300' }}">
                            <span class="material-icons-round text-base">folder_special</span>
                            <span>Proyectos de Titulación</span>
                        </a>
                    </nav>
                </div>

                <!-- SECCIÓN 3: SÍNODOS POR CARRERA -->
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-3 block mb-2.5">Tribunal Examinador</span>
                    <nav class="space-y-1">
                        <!-- Animación Digital -->
                        <a href="{{ route('coordinador.jurados.carrera', ['carrera' => 'animacion_digital']) }}" 
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('coordinador.jurados.*') && request()->route('carrera') === 'animacion_digital' ? 'bg-custom-primary text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100 text-slate-300' }}">
                            <div class="flex items-center gap-3">
                                <span class="material-icons-round text-base text-indigo-400">animation</span>
                                <span>Animación Digital</span>
                            </div>
                            <span class="text-[10px] bg-slate-800 px-2 py-0.5 rounded-md font-mono text-slate-300">3 Sínodos</span>
                        </a>

                        <!-- Química Industrial -->
                        <a href="{{ route('coordinador.jurados.carrera', ['carrera' => 'quimica_industrial']) }}" 
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('coordinador.jurados.*') && request()->route('carrera') === 'quimica_industrial' ? 'bg-custom-primary text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100 text-slate-300' }}">
                            <div class="flex items-center gap-3">
                                <span class="material-icons-round text-base text-emerald-400">science</span>
                                <span>Química Industrial</span>
                            </div>
                            <span class="text-[10px] bg-slate-800 px-2 py-0.5 rounded-md font-mono text-slate-300">3 Sínodos</span>
                        </a>
                    </nav>
                </div>

            </div>
        </aside>

        <!-- ÁREA DE CONTENIDO DINÁMICO -->
        <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-slate-900/60 w-full transition-colors duration-200">
            @yield('content')
        </main>

    </div>

    <!-- SCRIPTS DE CONTROL -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Manejo del Sidebar Móvil
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

            // 2. Dropdown de Perfil
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

            // 4. Cerrar menús con la tecla Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (userMenu) userMenu.classList.add('hidden');
                    if (sidebar && !sidebar.classList.contains('-translate-x-full') && window.innerWidth < 768) {
                        toggleSidebar();
                    }
                }
            });
        });
    </script>
</body>
</html>
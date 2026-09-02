<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SUIE Admin - @yield('title')</title>
    
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
            // Clave única para evitar conflictos entre diferentes cuentas en el mismo navegador
            const userKey = 'suie_theme_u_{{ auth()->id() ?? "guest" }}';
            const userDbTheme = "{{ auth()->user()->tema ?? '' }}";
            const localTheme = localStorage.getItem(userKey);

            // Prioridad: 1. BD del usuario autenticado, 2. LocalStorage por ID, 3. Preferencia del SO
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

            // Sincronizar colores institucionales
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
        .bg-custom-primary { background-color: var(--color-primary) !important; color: #ffffff !important; }
        .text-custom-primary { color: var(--color-primary) !important; }
        .border-custom-primary { border-color: var(--color-primary) !important; }
        .hover\:bg-custom-primary-hover:hover { background-color: var(--color-primary-hover) !important; color: #ffffff !important; }

        /* 2. Sobrescritura forzada de clases estáticas heredadas */
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

    <!-- BARRA SUPERIOR (HEADER UNIFICADO) -->
    <header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-4 md:px-7 h-16 md:h-18 flex justify-between items-center shrink-0 z-50 relative shadow-2xs transition-colors duration-200">
        <div class="flex items-center space-x-3 md:space-x-4">
            <button id="btn-toggle-sidebar" class="md:hidden text-slate-600 dark:text-slate-300 hover:text-custom-primary hover:bg-slate-100 dark:hover:bg-slate-800 p-2 rounded-xl focus:outline-hidden inline-flex items-center cursor-pointer transition-colors">
                <span class="material-icons-round text-2xl">menu</span>
            </button>
            
            <div class="flex items-center space-x-2.5">
                <span class="material-icons-round text-3xl text-custom-primary">hub</span>
                <div>
                    <h1 class="text-lg md:text-xl font-black tracking-wider leading-none text-custom-primary">SUIE</h1>
                    <p class="text-[10px] md:text-xs text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest mt-0.5 xs:block">Panel de Administración Central</p>
                </div>
            </div>
        </div>
        
        <!-- Acciones Rápidas y Perfil -->
        <div class="flex items-center space-x-3 md:space-x-4">
            <!-- Botón Ajustes de Color -->
            <a href="{{ route('admin.colores.index') }}" title="Configuración de Colores y Apariencia"
               class="p-2.5 text-slate-500 dark:text-slate-400 hover:text-custom-primary hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all cursor-pointer border border-slate-200 dark:border-slate-700/80 shadow-3xs flex items-center justify-center">
                <span class="material-icons-round text-xl">palette</span>
            </a>

            <!-- Botón Modo Oscuro -->
            <button id="btn-theme-toggle" type="button" aria-label="Cambiar tema"
                    class="p-2.5 text-slate-500 dark:text-slate-400 hover:text-custom-primary hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all cursor-pointer border border-slate-200 dark:border-slate-700/80 shadow-3xs">
                <span id="theme-icon-light" class="material-icons-round text-xl hidden dark:block text-amber-400">light_mode</span>
                <span id="theme-icon-dark" class="material-icons-round text-xl block dark:hidden text-slate-600">dark_mode</span>
            </button>

            <!-- Perfil Administrador -->
            <div class="text-right hidden sm:block border-l border-slate-200 dark:border-slate-800 pl-4">
                <p class="text-sm font-bold text-slate-900 dark:text-slate-100">Administrador</p>
                <p class="text-[11px] text-custom-primary font-semibold uppercase font-mono tracking-wider">{{ auth()->user()->username ?? 'ADMIN' }}</p>
            </div>
            <div class="w-9 h-9 md:w-10 md:h-10 bg-slate-100 dark:bg-slate-800 text-custom-primary rounded-xl flex items-center justify-center font-black border border-slate-200 dark:border-slate-700 text-xs md:text-sm shrink-0 select-none shadow-3xs">
                AD
            </div>
        </div>
    </header>

    <!-- CONTENEDOR PRINCIPAL -->
    <div class="flex-1 flex overflow-hidden relative">
        
        <!-- CAPA OSCURA MÓVIL -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-slate-950/50 backdrop-blur-xs z-30 transition-opacity duration-300 opacity-0 pointer-events-none md:hidden"></div>
        
        <!-- SIDEBAR DE NAVEGACIÓN -->
        <aside id="sidebar-menu" class="fixed md:static top-16 md:top-18 bottom-0 left-0 w-72 bg-slate-900 dark:bg-slate-950 text-slate-300 flex flex-col h-[calc(100vh-4rem)] md:h-full shrink-0 border-r border-slate-800 dark:border-slate-800/80 z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out justify-between">
            
            <div class="p-4 space-y-6 overflow-y-auto flex-1">
                
                <!-- Control de Accesos -->
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-3 block mb-2.5">Control de Accesos</span>
                    <nav class="space-y-1.5">
                        <a href="{{ route('usuarios.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('usuarios.*') ? 'bg-custom-primary text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100' }}">
                            <span class="material-icons-round text-base">supervised_user_circle</span> Control de Usuarios
                        </a>
                    </nav>
                </div>

                <!-- Estructura Escolar -->
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-3 block mb-2.5">Estructura Escolar</span>
                    <nav class="space-y-1.5"> 
                        <a href="{{ route('AdAlumnos.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('AdAlumnos.*') ? 'bg-custom-primary text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100' }}">
                            <span class="material-icons-round text-base">school</span> Alumnos y Matrícula
                        </a>
                        <a href="{{ route('docentes.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('docentes.*') ? 'bg-custom-primary text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100' }}">
                            <span class="material-icons-round text-base">badge</span> Plantilla Docente
                        </a>
                    </nav>
                </div>

                <!-- Configuración del Sistema -->
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-3 block mb-2.5">Configuración General</span>
                    <nav class="space-y-1.5">
                        <a href="{{ route('admin.colores.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('admin.colores.*') ? 'bg-custom-primary text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100' }}">
                            <span class="material-icons-round text-base">palette</span> Colores y Apariencia
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Botón Cerrar Sistema -->
            <div class="p-4 border-t border-slate-800 dark:border-slate-800/80 bg-slate-950/40 shrink-0">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 text-sm font-bold text-rose-400 hover:bg-rose-950/30 rounded-xl transition-all cursor-pointer">
                        <span class="material-icons-round text-base">logout</span> Cerrar Sistema
                    </button>
                </form>
            </div>
        </aside>

        <!-- ESPACIO DE TRABAJO -->
        <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-slate-900/60 w-full transition-colors duration-200">
            @yield('content')
        </main>

    </div>

    <!-- SCRIPT DE CONTROL -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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

            // Alternancia y guardado individual del tema
            const btnTheme = document.getElementById('btn-theme-toggle');
            if (btnTheme) {
                btnTheme.addEventListener('click', function () {
                    const isDark = document.documentElement.classList.toggle('dark');
                    const selectedTheme = isDark ? 'dark' : 'light';
                    const userKey = 'suie_theme_u_{{ auth()->id() ?? "guest" }}';

                    // 1. Guardar en localStorage de este usuario
                    localStorage.setItem(userKey, selectedTheme);

                    // 2. Guardar en Base de Datos de manera asíncrona
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
    </script>
</body>
</html>
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

    <link class="rounded-xs" rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <!-- Inicialización del Modo Oscuro antes de renderizar -->
    <script>
        const savedTheme = localStorage.getItem('theme');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
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
                <span class="material-icons-round text-3xl text-[#841B44] dark:text-rose-400">admin_panel_settings</span>
                <div>
                    <h1 class="text-lg md:text-xl font-black tracking-wider leading-none text-[#841B44] dark:text-rose-400">SUIE</h1>
                    <p class="text-[10px] md:text-xs text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest mt-0.5">Coordinación Académica</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-3 md:space-x-4">
            <!-- BOTÓN TOGGLE MODO OSCURO -->
            <button id="btn-theme-toggle" type="button" aria-label="Cambiar tema"
                    class="p-2.5 text-slate-500 dark:text-slate-400 hover:text-[#841B44] dark:hover:text-rose-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all cursor-pointer border border-slate-200 dark:border-slate-700/80">
                <span id="theme-icon-light" class="material-icons-round text-xl hidden dark:block text-amber-400">light_mode</span>
                <span id="theme-icon-dark" class="material-icons-round text-xl block dark:hidden text-slate-600">dark_mode</span>
            </button>

            <!-- INFORMACIÓN DEL COORDINADOR -->
            <div class="text-right hidden sm:block border-l border-slate-200 dark:border-slate-800 pl-4">
                <p class="text-sm font-bold text-slate-900 dark:text-slate-100">
                    {{ auth()->user()->username ?? 'Coordinador' }}
                </p>
                <p class="text-[11px] text-rose-700 dark:text-rose-400 font-bold uppercase tracking-wider mt-0.5">
                    Coordinación General
                </p>
            </div>

            <div class="w-9 h-9 md:w-10 md:h-10 bg-rose-50 dark:bg-rose-950/50 text-[#841B44] dark:text-rose-300 rounded-xl flex items-center justify-center font-black border border-rose-200 dark:border-rose-900/60 text-xs md:text-sm shrink-0 select-none shadow-3xs">
                CD
            </div>
        </div>
    </header>

    <div class="flex-1 flex overflow-hidden relative">
        
        <!-- OVERLAY MÓVIL -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-slate-950/50 backdrop-blur-xs z-30 transition-opacity duration-300 opacity-0 pointer-events-none md:hidden"></div>
        
        <!-- SIDEBAR DE NAVEGACIÓN -->
        <aside id="sidebar-menu" class="fixed md:static top-16 md:top-18 bottom-0 left-0 w-72 bg-slate-900 dark:bg-slate-950 text-slate-300 flex flex-col h-[calc(100vh-4rem)] md:h-full shrink-0 border-r border-slate-800 dark:border-slate-800/80 z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out justify-between">
            
            <div class="p-4 space-y-6 overflow-y-auto flex-1">
                
                <!-- GENERAL -->
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-3 block mb-2.5">General</span>
                    <nav class="space-y-1.5">
                        <a href="#" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('coordinador.dashboard') ? 'bg-[#841B44] text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100' }}">
                            <span class="material-icons-round text-base">dashboard</span> Panel Principal
                        </a>
                    </nav>
                </div>

                <!-- GESTIÓN ACADÉMICA -->
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-3 block mb-2.5">Gestión Docente</span>
                    <nav class="space-y-1.5">
                        <a href="{{ route('coordinador.cargas.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('coordinador.cargas.*') ? 'bg-[#841B44] text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100' }}">
                            <span class="material-icons-round text-base">menu_book</span> Cargas Académicas
                        </a>
                        <a href="{{ route('coordinador.proyectos.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('coordinador.proyectos.*') ? 'bg-[#841B44] text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100' }}">
                            <span class="material-icons-round text-base">folder_special</span> Proyectos Registrados
                        </a>
                    </nav>
                </div>

                <!-- ASIGNACIÓN DE JURADOS (3 SÍNODOS POR CARRERA) -->
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-3 block mb-2.5">Asignación de Jurados</span>
                    <nav class="space-y-1.5">
                        <!-- Animación Digital -->
                        <a href="{{ route('coordinador.jurados.carrera', ['carrera' => 'animacion_digital']) }}" 
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('coordinador.jurados.*') && request()->route('carrera') === 'animacion_digital' ? 'bg-[#841B44] text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100' }}">
                            <div class="flex items-center gap-3">
                                <span class="material-icons-round text-base text-indigo-400">animation</span>
                                <span>Animación Digital</span>
                            </div>
                            <span class="text-[10px] bg-slate-800 dark:bg-slate-900 px-2 py-0.5 rounded-md font-mono text-slate-300">3 Sínodos</span>
                        </a>

                        <!-- Química Industrial -->
                        <a href="{{ route('coordinador.jurados.carrera', ['carrera' => 'quimica_industrial']) }}" 
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('coordinador.jurados.*') && request()->route('carrera') === 'quimica_industrial' ? 'bg-[#841B44] text-white shadow-xs' : 'hover:bg-slate-800 dark:hover:bg-slate-900 hover:text-slate-100' }}">
                            <div class="flex items-center gap-3">
                                <span class="material-icons-round text-base text-emerald-400">science</span>
                                <span>Química Industrial</span>
                            </div>
                            <span class="text-[10px] bg-slate-800 dark:bg-slate-900 px-2 py-0.5 rounded-md font-mono text-slate-300">3 Sínodos</span>
                        </a>
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

        <!-- ÁREA DE CONTENIDO DINÁMICO -->
        <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-slate-900/60 w-full transition-colors duration-200">
            @yield('content')
        </main>

    </div>

    <!-- SCRIPTS -->
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

            const btnTheme = document.getElementById('btn-theme-toggle');
            if (btnTheme) {
                btnTheme.addEventListener('click', function () {
                    const isDark = document.documentElement.classList.toggle('dark');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                });
            }
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUIE - @yield('title')</title>
    
    <!-- Tailwind CSS v4 Browser -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <!-- Configuración para habilitar variante dark con la clase .dark en Tailwind v4 -->
    <style type="text/tailwindcss">
        @custom-variant dark (&:where(.dark, .dark *));
    </style>

    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <!-- Inicialización del tema antes del renderizado -->
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
<body class="bg-slate-50 dark:bg-slate-950 font-sans text-slate-800 dark:text-slate-100 text-sm md:text-base antialiased selection:bg-[#841B44]/20 transition-colors duration-200 min-h-screen flex flex-col">
    <div class="min-h-screen flex flex-col">
        
        <!-- HEADER / TOPBAR -->
        <header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-xs px-5 md:px-8 py-4 flex justify-between items-center sticky top-0 z-40 transition-colors duration-200">
            <div class="flex items-center space-x-3.5">
                <span class="material-icons-round text-3xl md:text-4xl text-[#841B44] dark:text-rose-400">hub</span>
                <div>
                    <h1 class="text-lg md:text-xl font-black tracking-wider leading-none text-[#841B44] dark:text-rose-400">SUIE</h1>
                    <p class="text-[10px] md:text-xs text-slate-400 dark:text-slate-500 font-extrabold uppercase tracking-widest mt-1 xs:block">Sistema Unificado de Integración Educativa</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3 md:space-x-5">
                <!-- BOTÓN TOGGLE MODO OSCURO -->
                <button id="btn-theme-toggle" type="button" aria-label="Alternar tema"
                        class="p-2.5 text-slate-500 dark:text-slate-300 hover:text-[#841B44] dark:hover:text-rose-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-2xl transition-all cursor-pointer border border-slate-200 dark:border-slate-700/80 shadow-3xs">
                    <span id="theme-icon-light" class="material-icons-round text-xl hidden dark:block text-amber-400">light_mode</span>
                    <span id="theme-icon-dark" class="material-icons-round text-xl block dark:hidden text-slate-600">dark_mode</span>
                </button>

                @if(auth()->check())
                    <!-- BADGE GRADO/GRUPO DINÁMICO -->
                    @if(View::hasSection('grupo_badge'))
                        <div class="hidden sm:inline-flex items-center px-4 py-1.5 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900 text-[#841B44] dark:text-rose-300 text-xs font-bold rounded-full uppercase tracking-wider">
                            @yield('grupo_badge')
                        </div>
                    @endif

                    @php
                        // Descifrado dinámico y seguro de la identidad
                        $docenteNombre = '';
                        $docenteApellido = '';
                        $inicialesAvatar = '';

                        if (auth()->user()->docente) {
                            $rawNombre = str_replace(' (Plain)', '', auth()->user()->docente->nombre ?? '');
                            $rawApellido = str_replace(' (Plain)', '', auth()->user()->docente->apellido_paterno ?? '');

                            try {
                                if (is_string($rawNombre) && (str_starts_with($rawNombre, 'ey') || strlen($rawNombre) > 50)) {
                                    $docenteNombre = decrypt($rawNombre);
                                } else {
                                    $docenteNombre = $rawNombre;
                                }
                            } catch (\Throwable $e) {
                                $docenteNombre = $rawNombre;
                            }

                            try {
                                if (is_string($rawApellido) && (str_starts_with($rawApellido, 'ey') || strlen($rawApellido) > 50)) {
                                    $docenteApellido = decrypt($rawApellido);
                                } else {
                                    $docenteApellido = $rawApellido;
                                }
                            } catch (\Throwable $e) {
                                $docenteApellido = $rawApellido;
                            }

                            $iniN = !empty($docenteNombre) ? mb_substr($docenteNombre, 0, 1) : 'P';
                            $iniA = !empty($docenteApellido) ? mb_substr($docenteApellido, 0, 1) : 'R';
                            $inicialesAvatar = strtoupper($iniN . $iniA);
                        } else {
                            $inicialesAvatar = strtoupper(mb_substr(auth()->user()->username ?? 'US', 0, 2));
                        }
                    @endphp

                    <div class="text-right hidden md:block border-l border-slate-200 dark:border-slate-800 pl-4">
                        @if(auth()->user()->docente)
                            <p class="text-sm font-bold text-slate-900 dark:text-slate-100 truncate max-w-[220px]">
                                Prof. {{ $docenteNombre }} {{ $docenteApellido }}
                            </p>
                        @else
                            <p class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase">
                                {{ auth()->user()->rol ?? 'Usuario' }}
                            </p>
                        @endif
                        <p class="text-xs text-[#841B44] dark:text-rose-400 font-bold uppercase font-mono mt-0.5">
                            ID: {{ auth()->user()->username }}
                        </p>
                    </div>

                    <!-- DROPDOWN DE PERFIL -->
                    <div class="relative">
                        <button id="profileDropdownBtn" class="w-11 h-11 bg-rose-50 dark:bg-rose-950/60 hover:bg-rose-100 dark:hover:bg-rose-900/80 text-[#841B44] dark:text-rose-300 rounded-2xl flex items-center justify-center font-black border border-rose-200 dark:border-rose-900/60 text-sm uppercase cursor-pointer focus:outline-hidden transition-colors select-none shadow-3xs">
                            {{ $inicialesAvatar }}
                        </button>

                        <!-- MENÚ DESPLEGABLE -->
                        <div id="profileDropdown" class="hidden absolute right-0 mt-3 w-60 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl py-2 z-50 transform origin-top-right transition-all">
                            
                            <!-- Información móvil condensada -->
                            <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800 md:hidden bg-slate-50/60 dark:bg-slate-800/40 rounded-t-2xl">
                                <p class="text-sm font-black text-slate-900 dark:text-slate-100 truncate">
                                    @if(auth()->user()->docente)
                                        Prof. {{ $docenteNombre }} {{ $docenteApellido }}
                                    @else
                                        {{ auth()->user()->rol ?? 'Usuario' }}
                                    @endif
                                </p>
                                <p class="text-xs font-mono text-slate-500 dark:text-slate-400 mt-0.5 truncate">
                                    Matrícula: {{ auth()->user()->username }}
                                </p>
                            </div>

                            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-3 text-xs md:text-sm font-bold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors cursor-pointer">
                                    <span class="material-icons-round text-lg">logout</span> Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="text-right hidden md:block">
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400">Usuario Invitado</p>
                    </div>
                @endif
            </div>
        </header>
        
        <!-- CUERPO PRINCIPAL -->
        <main class="flex-1 w-full mx-auto">
            @yield('content')
        </main>
    </div>

    <!-- SCRIPT DE DROPDOWN Y MODO OSCURO -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Manejo del Dropdown de Perfil
            const dropdownBtn = document.getElementById('profileDropdownBtn');
            const dropdownMenu = document.getElementById('profileDropdown');

            if (dropdownBtn && dropdownMenu) {
                dropdownBtn.addEventListener('click', function (event) {
                    event.stopPropagation();
                    dropdownMenu.classList.toggle('hidden');
                });

                document.addEventListener('click', function (event) {
                    if (!dropdownMenu.contains(event.target) && !dropdownBtn.contains(event.target)) {
                        dropdownMenu.classList.add('hidden');
                    }
                });
            }

            // Alternancia de Modo Oscuro
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
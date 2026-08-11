<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUIE - @yield('title')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased selection:bg-[#841B44]/10">
    <div class="min-h-screen flex flex-col">
        
        <!-- HEADER / TOPBAR -->
        <header class="bg-white border-b border-slate-200 shadow-2xs px-4 md:px-6 py-3 flex justify-between items-center sticky top-0 z-40">
            <div class="flex items-center space-x-3">
                <span class="material-icons-round text-3xl text-[#841B44]">hub</span>
                <div>
                    <h1 class="text-base font-black tracking-wider leading-none text-[#841B44]">SUIE</h1>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1 xs:block">Sistema Unificado de Integración Educativa</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3 md:space-x-4">
                @if(auth()->check())
                    <!-- BADGE GRADO/GRUPO DINÁMICO (Inyección desde sub-vistas) -->
                    @if(View::hasSection('grupo_badge'))
                        <div class="hidden sm:inline-flex items-center px-3 py-1 bg-rose-50 border border-rose-100 text-[#841B44] text-[10px] font-bold rounded-full uppercase tracking-wider">
                            @yield('grupo_badge')
                        </div>
                    @endif

                    @php
                        // Lógica de Descifrado Dinámico para la Identidad del Docente
                        $docenteNombre = '';
                        $docenteApellido = '';
                        $inicialesAvatar = '';

                        if (auth()->user()->docente) {
                            $rawNombre = str_replace(' (Plain)', '', auth()->user()->docente->nombre ?? '');
                            $rawApellido = str_replace(' (Plain)', '', auth()->user()->docente->apellido_paterno ?? '');

                            // Descifrado seguro del Nombre
                            try {
                                if (is_string($rawNombre) && (str_starts_with($rawNombre, 'ey') || strlen($rawNombre) > 50)) {
                                    $docenteNombre = decrypt($rawNombre);
                                } else {
                                    $docenteNombre = $rawNombre;
                                }
                            } catch (\Throwable $e) {
                                $docenteNombre = $rawNombre;
                            }

                            // Descifrado seguro del Apellido Paterno
                            try {
                                if (is_string($rawApellido) && (str_starts_with($rawApellido, 'ey') || strlen($rawApellido) > 50)) {
                                    $docenteApellido = decrypt($rawApellido);
                                } else {
                                    $docenteApellido = $rawApellido;
                                }
                            } catch (\Throwable $e) {
                                $docenteApellido = $rawApellido;
                            }

                            // Cálculo seguro de iniciales para el avatar
                            $iniN = !empty($docenteNombre) ? mb_substr($docenteNombre, 0, 1) : 'P';
                            $iniA = !empty($docenteApellido) ? mb_substr($docenteApellido, 0, 1) : 'R';
                            $inicialesAvatar = strtoupper($iniN . $iniA);
                        } else {
                            $inicialesAvatar = strtoupper(mb_substr(auth()->user()->username ?? 'US', 0, 2));
                        }
                    @endphp

                    <div class="text-right hidden md:block">
                        @if(auth()->user()->docente)
                            <p class="text-xs font-bold text-slate-900 truncate max-w-[200px]">
                                Prof. {{ $docenteNombre }} {{ $docenteApellido }}
                            </p>
                        @else
                            <p class="text-xs font-bold text-slate-900 uppercase">
                                {{ auth()->user()->rol ?? 'Estudiante' }}
                            </p>
                        @endif
                        <p class="text-[10px] text-[#841B44] font-semibold uppercase font-mono mt-0.5">
                            ID: {{ auth()->user()->username }}
                        </p>
                    </div>

                    <!-- DROPDOWN DE PERFIL -->
                    <div class="relative">
                        <button id="profileDropdownBtn" class="w-9 h-9 bg-rose-50 hover:bg-rose-100 text-[#841B44] rounded-xl flex items-center justify-center font-black border border-rose-200/60 text-xs uppercase cursor-pointer focus:outline-hidden transition-colors select-none">
                            {{ $inicialesAvatar }}
                        </button>

                        <!-- MENÚ DESPLEGABLE -->
                        <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-52 bg-white border border-slate-200 rounded-xl shadow-lg py-1 z-50 transform origin-top-right transition-all">
                            
                            <!-- Información móvil condensada -->
                            <div class="px-4 py-2.5 border-b border-slate-100 md:hidden bg-slate-50/50 rounded-t-xl">
                                <p class="text-xs font-black text-slate-900 truncate">
                                    @if(auth()->user()->docente)
                                        Prof. {{ $docenteNombre }} {{ $docenteApellido }}
                                    @else
                                        {{ auth()->user()->rol ?? 'Estudiante' }}
                                    @endif
                                </p>
                                <p class="text-[10px] font-mono text-slate-400 mt-0.5 truncate">
                                    Matrícula: {{ auth()->user()->username }}
                                </p>
                            </div>

                            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer">
                                    <span class="material-icons-round text-sm">logout</span> Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="text-right hidden md:block">
                        <p class="text-xs font-bold text-slate-500">Usuario Invitado</p>
                    </div>
                @endif
            </div>
        </header>
        
        <!-- CUERPO PRINCIPAL -->
        <main class="flex-1 w-full mx-auto">
            @yield('content')
        </main>
    </div>

    <!-- MANEJO FLUIDO DEL DROPDOWN -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
        });
    </script>
</body>
</html>
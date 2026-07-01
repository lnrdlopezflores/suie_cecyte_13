<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUIE - @yield('title')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="icon" href="/assets/images/logo.png" type="icon">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
</head>
<body class="bg-slate-50 font-sans text-slate-800">
    <div class="min-h-screen flex flex-col">
        
        <header class="bg-white border-b border-slate-200 shadow-2xs px-6 py-3 flex justify-between items-center sticky top-0 z-40">
            <div class="flex items-center space-x-3">
                <span class="material-icons-round text-3xl text-[#841B44]">hub</span>
                <div>
                    <h1 class="text-base font-black tracking-wider leading-none text-[#841B44]">SUIE</h1>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Sistema Unificado de Integración Educativa</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                @if(auth()->check())
                    <div class="text-right hidden md:block">
                        @if(auth()->user()->docente)
                            <p class="text-xs font-bold text-slate-900">
                                Prof. {{ auth()->user()->docente->nombre }} {{ auth()->user()->docente->apellido_paterno }}
                            </p>
                        @else
                            <p class="text-xs font-bold text-slate-900">
                                {{ auth()->user()->rol ?? 'Usuario Registrado' }}
                            </p>
                        @endif
                        <p class="text-[10px] text-[#841B44] font-semibold uppercase font-mono mt-0.5">
                            ID: {{ auth()->user()->username }}
                        </p>
                    </div>

                    <div class="relative">
                        <button id="profileDropdownBtn" class="w-9 h-9 bg-rose-50 text-[#841B44] rounded-xl flex items-center justify-center font-black border border-rose-200 text-xs uppercase cursor-pointer focus:outline-hidden transition-all hover:bg-rose-100">
                            @if(auth()->user()->docente)
                                {{ mb_substr(auth()->user()->docente->nombre, 0, 1) }}{{ mb_substr(auth()->user()->docente->apellido_paterno, 0, 1) }}
                            @else
                                {{ mb_substr(auth()->user()->username, 0, 2) }}
                            @endif
                        </button>

                        <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-xl shadow-lg py-1 z-50 transform origin-top-right transition-all">
                            
                            <div class="px-4 py-2 border-b border-slate-100 md:hidden">
                                <p class="text-xs font-bold text-slate-900 truncate">
                                    {{ auth()->user()->docente->nombre ?? auth()->user()->username }}
                                </p>
                                <p class="text-[10px] font-mono text-slate-400 truncate">
                                    {{ auth()->user()->username }}
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
        
        <main class="flex-1">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownBtn = document.getElementById('profileDropdownBtn');
            const dropdownMenu = document.getElementById('profileDropdown');

            if (dropdownBtn && dropdownMenu) {
                // Alternar visibilidad al hacer clic en la burbuja de perfil
                dropdownBtn.addEventListener('click', function (event) {
                    event.stopPropagation();
                    dropdownMenu.classList.toggle('hidden');
                });

                // Cerrar el menú automáticamente si se da clic en cualquier otra parte de la pantalla
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

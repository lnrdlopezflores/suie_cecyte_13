<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUIE Admin - @yield('title')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="icon" href="/assets/images/logo.png" type="icon">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
</head>
<body class="bg-slate-100 font-sans text-slate-800 h-screen flex flex-col overflow-hidden">

    <header class="bg-white border-b border-slate-200 px-4 md:px-6 h-16 flex justify-between items-center shrink-0 z-40 relative shadow-2xs">
        <div class="flex items-center space-x-3">
            <!-- Botón del Menú Móvil -->
            <button id="btn-toggle-sidebar" class="md:hidden text-slate-600 hover:text-[#841B44] p-1 rounded-lg focus:outline-hidden inline-flex items-center cursor-pointer">
                <span class="material-icons-round text-2xl">menu</span>
            </button>
            
            <div class="flex items-center space-x-2">
                <span class="material-icons-round text-2xl text-[#841B44]">hub</span>
                <div>
                    <h1 class="text-base font-black tracking-wider leading-none text-[#841B44]">SUIE</h1>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5 hidden xs:block">Panel de Administración Central</p>
                </div>
            </div>
        </div>
        
        <!-- Perfil de Usuario -->
        <div class="flex items-center space-x-3">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-bold text-slate-900">Mtro. Leo Lopez</p>
                <p class="text-[10px] text-rose-700 font-semibold uppercase font-mono">{{ auth()->user()->username ?? 'ADMIN' }}</p>
            </div>
            <div class="w-8 h-8 bg-rose-50 text-[#841B44] rounded-xl flex items-center justify-center font-black border border-rose-200 text-xs shrink-0">
                LL
            </div>
        </div>
    </header>

    <!-- CONTENEDOR PRINCIPAL -->
    <div class="flex-1 flex overflow-hidden relative">
        
        <!-- CAPA OSCURA DE FONDO (z-45 para estar justo debajo del menú pero sobre el contenido) -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/40 z-45 transition-opacity duration-300 opacity-0 pointer-events-none md:hidden"></div>
        
        <!-- BARRA LATERAL (SIDEBAR OPTIMIZADO) -->
        <!-- Cambiado a z-50 en móviles y posicionado desde top-0 para cubrir toda la pantalla limpiamente -->
        <aside id="sidebar-menu" class="fixed md:static top-0 bottom-0 left-0 w-64 bg-slate-900 text-slate-400 flex flex-col h-screen md:h-full shrink-0 border-r border-slate-800 z-50 md:z-30 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out justify-between">
            
            <!-- Bloque de Enlaces (Añadido pt-16 en móvil para que el contenido baje y no lo tape la navbar) -->
            <div class="p-4 pt-20 md:pt-4 space-y-6 overflow-y-auto flex-1">
                <!-- Control de Accesos -->
                <div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-3 block mb-2">Control de Accesos</span>
                    <nav class="space-y-1">
                        <a href="{{ route('usuarios.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('usuarios.index') ? 'bg-[#841B44] text-white' : 'hover:bg-slate-800 hover:text-slate-200' }}">
                            <span class="material-icons-round text-sm">supervised_user_circle</span> Usuarios
                        </a>
                    </nav>
                </div>

                <!-- Estructura Escolar -->
                <div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-3 block mb-2">Estructura Escolar</span>
                    <nav class="space-y-1"> 
                        <a href="{{ route('AdAlumnos.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('AdAlumnos.index') ? 'bg-[#841B44] text-white' : 'hover:bg-slate-800 hover:text-slate-200' }}">
                            <span class="material-icons-round text-sm">school</span> Alumnos y Matrícula
                        </a>
                        <a href="{{ route('docentes.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('docentes.index') ? 'bg-[#841B44] text-white' : 'hover:bg-slate-800 hover:text-slate-200' }}">
                            <span class="material-icons-round text-sm">badge</span> Plantilla Docente
                        </a>
                        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors hover:bg-slate-800 hover:text-slate-200">
                            <span class="material-icons-round text-sm">engineering</span> Personal Administrativo
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Botón Cerrar Sistema -->
            <div class="p-4 border-t border-slate-800 bg-slate-950/40 shrink-0">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-xs font-bold text-rose-400 hover:bg-rose-950/30 rounded-xl transition-colors cursor-pointer">
                        <span class="material-icons-round text-sm">logout</span> Cerrar Sistema
                    </button>
                </form>
            </div>
        </aside>

        <!-- ESPACIO DE TRABAJO -->
        <main class="flex-1 overflow-y-auto bg-slate-50">
            @yield('content')
        </main>

    </div>

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
        });
    </script>

</body>
</html>
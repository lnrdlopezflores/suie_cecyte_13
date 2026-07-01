<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Estudiantil SUIE - @yield('title')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link rel="icon" href="/assets/images/logo.png" type="icon">
</head>
<body class="bg-slate-100 font-sans text-slate-800 h-screen flex flex-col overflow-hidden">

    <header class="bg-white border-b border-slate-200 px-4 md:px-6 h-16 flex justify-between items-center shrink-0 z-40 relative shadow-2xs">
        <div class="flex items-center space-x-3">
            <button id="btn-toggle-sidebar" class="md:hidden text-slate-600 hover:text-[#841B44] p-1 rounded-lg focus:outline-hidden inline-flex items-center cursor-pointer">
                <span class="material-icons-round text-2xl">menu</span>
            </button>
            
            <div class="flex items-center space-x-2">
                <span class="material-icons-round text-2xl text-[#841B44]">school</span>
                <div>
                    <h1 class="text-base font-black tracking-wider leading-none text-[#841B44]">SUIE</h1>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Estudiantes</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-bold text-slate-900">{{ auth()->user()->username ?? 'Matrícula Alumno' }}</p>
                <p class="text-[10px] text-indigo-600 font-bold uppercase tracking-wider">
                    @yield('grupo_badge', 'Estudiante Activo')
                </p>
            </div>
            <div class="w-8 h-8 bg-indigo-50 text-indigo-700 rounded-xl flex items-center justify-center font-black border border-indigo-100 text-xs shrink-0">
                ST
            </div>
        </div>
    </header>

    <div class="flex-1 flex overflow-hidden relative">
        
        <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/40 z-45 transition-opacity duration-300 opacity-0 pointer-events-none md:hidden"></div>
        
        <aside id="sidebar-menu" class="fixed md:static top-0 bottom-0 left-0 w-64 bg-slate-900 text-slate-400 flex flex-col h-screen md:h-full shrink-0 border-r border-slate-800 z-50 md:z-30 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out justify-between">
            
            <div class="p-4 pt-20 md:pt-4 space-y-6 overflow-y-auto flex-1">
                
                <div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-3 block mb-2">General</span>
                    <nav class="space-y-1">
                        <a href="{{ route('indexalumnos.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('estudiante.dashboard') ? 'bg-[#841B44] text-white' : 'hover:bg-slate-800 hover:text-slate-200' }}">
                            <span class="material-icons-round text-sm">dashboard</span> Panel de Inicio
                        </a>
                    </nav>
                </div>

                <div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-3 block mb-2">Servicios Escolares</span>
                    <nav class="space-y-1">
                        <a href="{{ route('indexmaterias.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('indexmaterias.index') ? 'bg-[#841B44] text-white' : 'hover:bg-slate-800 hover:text-slate-200' }}">
                            <span class="material-icons-round text-sm">auto_stories</span> Mis Materias
                        </a>
                        <a href="{{ route('alumnoPagos.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('alumnoPagos.index') ? 'bg-[#841B44] text-white' : 'hover:bg-slate-800 hover:text-slate-200' }}">
                            <span class="material-icons-round text-sm">payments</span> Control de Pagos
                        </a>
                    </nav>
                </div>
            </div>

            <div class="p-4 border-t border-slate-800 bg-slate-950/40 shrink-0">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-xs font-bold text-rose-400 hover:bg-rose-950/30 rounded-xl transition-colors cursor-pointer">
                        <span class="material-icons-round text-sm">logout</span> Cerrar Sesión Portal
                    </button>
                </form>
            </div>
        </aside>

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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUIE Control Escolar - @yield('title')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
</head>
<body class="bg-slate-100 font-sans text-slate-800 h-screen flex flex-col overflow-hidden">

    <header class="bg-white border-b border-slate-200 px-4 md:px-6 py-3 flex justify-between items-center shrink-0 z-50 shadow-2xs relative">
        <div class="flex items-center space-x-3">
            <label for="sidebar-menu-toggle" class="p-2 rounded-xl hover:bg-slate-100 text-slate-600 lg:hidden cursor-pointer flex items-center justify-center select-none">
                <span class="material-icons-round text-2xl">menu</span>
            </label>

            <div class="flex items-center space-x-2">
                <span class="material-icons-round text-2xl text-[#841B44]">hub</span>
                <div>
                    <h1 class="text-base font-black tracking-wider leading-none text-[#841B44]">SUIE</h1>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5 xs:block">Control Escolar y Matrícula</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-bold text-slate-900">Oficina de Registro Técnico</p>
                <p class="text-[10px] text-emerald-700 font-bold uppercase font-mono tracking-wider">
                    {{ auth()->user()->username ?? 'Control Escolar' }}
                </p>
            </div>
            <div class="w-8 h-8 bg-rose-50 text-[#841B44] rounded-xl flex items-center justify-center font-black border border-rose-200 text-xs shrink-0">
                CE
            </div>
        </div>
    </header>

    <div class="flex-1 flex overflow-hidden relative">
        
        <input type="checkbox" id="sidebar-menu-toggle" class="peer hidden" />

        <label for="sidebar-menu-toggle" class="fixed inset-0 bg-slate-950/40 z-30 opacity-0 pointer-events-none peer-checked:opacity-100 peer-checked:pointer-events-auto lg:hidden transition-opacity duration-300"></label>
        
        <aside class="fixed lg:static top-0 bottom-0 left-0 w-64 bg-slate-900 text-slate-400 flex flex-col h-screen lg:h-full shrink-0 border-r border-slate-800 justify-between z-40 
                     -translate-x-full peer-checked:translate-x-0 lg:translate-x-0 transition-transform duration-300 ease-in-out">
            
            <div class="p-4 pt-20 lg:pt-4 space-y-6 overflow-y-auto flex-1">
                <div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-3 block mb-2">Administración Matricular</span>
                    <nav class="space-y-1">
                        <a href="{{ route('alumnos.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('alumnos.index') ? 'bg-[#841B44] text-white' : 'hover:bg-slate-800 hover:text-slate-200' }}">
                            <span class="material-icons-round text-sm">groups</span> Alumnos Inscritos
                        </a>
                        <a href="{{ route('grupos.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('grupos.index') ? 'bg-[#841B44] text-white' : 'hover:bg-slate-800 hover:text-slate-200' }}">
                            <span class="material-icons-round text-sm">grid_view</span> Grupos y Secciones
                        </a>
                        <a href="{{ route('materias.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('materias.index') ? 'bg-[#841B44] text-white' : 'hover:bg-slate-800 hover:text-slate-200' }}">
                            <span class="material-icons-round text-sm">auto_stories</span> Materias
                        </a>
                        <a href="{{ route('cargas.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('cargas.index') ? 'bg-[#841B44] text-white' : 'hover:bg-slate-800 hover:text-slate-200' }}">
                            <span class="material-icons-round text-sm">assignment</span> Carga Académica
                        </a>
                    </nav>
                </div>

                <div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-3 block mb-2">Trámites y Certificación</span>
                    <nav class="space-y-1">
                        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors hover:bg-slate-800 hover:text-slate-200">
                            <span class="material-icons-round text-sm">analytics</span> Actas de Calificaciones
                        </a>
                        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors hover:bg-slate-800 hover:text-slate-200">
                            <span class="material-icons-round text-sm">assignment_turned_in</span> Expedientes de Titulación
                        </a>
                    </nav>
                </div>
            </div>

            <div class="p-4 border-t border-slate-800 bg-slate-950/40 shrink-0">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-xs font-bold text-rose-400 hover:bg-rose-950/30 rounded-xl transition-colors cursor-pointer">
                        <span class="material-icons-round text-sm">logout</span> Salir del Sistema
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto bg-slate-50 w-full">
            @yield('content')
        </main>

    </div>

</body>
</html>
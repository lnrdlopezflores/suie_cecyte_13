<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUIE Admin - @yield('title')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
</head>
<body class="bg-slate-100 font-sans text-slate-800 h-screen flex flex-col overflow-hidden">

    <header class="bg-white border-b border-slate-200 px-6 py-3 flex justify-between items-center shrink-0 z-40 shadow-2xs">
        <div class="flex items-center space-x-3">
            <span class="material-icons-round text-2xl text-[#841B44]">hub</span>
            <div>
                <h1 class="text-base font-black tracking-wider leading-none text-[#841B44]">SUIE</h1>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Panel de Administración Central</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-bold text-slate-900">Mtro. Leo Lopez</p>
                <p class="text-[10px] text-rose-700 font-semibold uppercase font-mono">{{ auth()->user()->username ?? 'ADMIN' }}</p>
            </div>
            <div class="w-8 h-8 bg-rose-50 text-[#841B44] rounded-xl flex items-center justify-center font-black border border-rose-200 text-xs">
                LL
            </div>
        </div>
    </header>

    <div class="flex-1 flex overflow-hidden relative">
        
        <aside class="w-64 bg-slate-900 text-slate-400 flex flex-col h-full shrink-0 border-r border-slate-800 hidden md:flex justify-between">
            
            <div class="p-4 space-y-6">
                <div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-3 block mb-2">Control de Accesos</span>
                    <nav class="space-y-1">
                        <a href="{{ route('usuarios.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('usuarios.index') ? 'bg-[#841B44] text-white' : 'hover:bg-slate-800 hover:text-slate-200' }}">
                            <span class="material-icons-round text-sm">supervised_user_circle</span> Catálogo de Usuarios
                        </a>
                    </nav>
                </div>

                <div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-3 block mb-2">Estructura Escolar</span>
                    <nav class="space-y-1"> 
                        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold hover:bg-slate-800">
                            <span class="material-icons-round text-sm">school</span> Alumnos y Matrícula
                        </a>
                        <a href="{{ route('docentes.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('docentes.index') ? 'bg-[#841B44] text-white' : 'hover:bg-slate-800 hover:text-slate-200' }}">
                            <span class="material-icons-round text-sm">badge</span> Plantilla Docente
                        </a>
                        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold hover:bg-slate-800">
                            <span class="material-icons-round text-sm">badge</span> Personal Administrativo
                        </a>
                    </nav>
                </div>
            </div>

            <div class="p-4 border-t border-slate-800 bg-slate-950/40">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-xs font-bold text-rose-400 hover:bg-rose-950/30 rounded-xl transition-colors cursor-pointer">
                        <span class="material-icons-round text-sm">logout</span> Cerrar Sistema
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto flex flex-col h-full bg-slate-100">
            @yield('content')
        </main>

    </div>

</body>
</html>
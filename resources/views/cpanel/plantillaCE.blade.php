<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUIE Control Escolar - @yield('title')</title>
    <!-- Tailwind CSS vía CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Iconos de Google -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
</head>
<body class="bg-slate-100 font-sans text-slate-800 h-screen flex flex-col overflow-hidden">

    <!-- BARRA SUPERIOR (TOPBAR) -->
    <header class="bg-white border-b border-slate-200 px-6 py-3 flex justify-between items-center shrink-0 z-40 shadow-2xs">
        <div class="flex items-center space-x-3">
            <span class="material-icons-round text-2xl text-[#841B44]">hub</span>
            <div>
                <h1 class="text-base font-black tracking-wider leading-none text-[#841B44]">SUIE</h1>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Control Escolar y Matrícula</p>
            </div>
        </div>
        
        <!-- Datos de la cuenta de Control Escolar Autenticada -->
        <div class="flex items-center space-x-3">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-bold text-slate-900">Oficina de Registro Técnico</p>
                <p class="text-[10px] text-emerald-700 font-bold uppercase font-mono tracking-wider">
                    {{ auth()->user()->username ?? 'Control Escolar' }}
                </p>
            </div>
            <div class="w-8 h-8 bg-[#841B44]/10 text-[#841B44] rounded-xl flex items-center justify-center font-black border border-[#841B44]/20 text-xs">
                CE
            </div>
        </div>
    </header>

    <!-- CONTENEDOR INFERIOR -->
    <div class="flex-1 flex overflow-hidden relative">
        
        <!-- BARRA LATERAL (SIDEBAR) -->
        <aside class="w-64 bg-slate-900 text-slate-400 flex flex-col h-full shrink-0 border-r border-slate-800 justify-between">
            
            <!-- Menú Operativo -->
            <div class="p-4 space-y-6">
                <!-- Sección de Servicios Principales -->
                <div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-3 block mb-2">Administración Matricular</span>
                    <nav class="space-y-1">
                        <a href="{{ route('alumnos.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors hover:bg-slate-800 hover:text-slate-200">
                            <span class="material-icons-round text-sm">groups</span> Alumnos Inscritos
                        </a>
                        <a href="{{ route('grupos.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors hover:bg-slate-800 hover:text-slate-200">
                            <span class="material-icons-round text-sm">auto_stories</span> Grupos y Secciones
                        </a>
                        <a href="{{ route('materias.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors hover:bg-slate-800 hover:text-slate-200">
                            <span class="material-icons-round text-sm">auto_stories</span> Materias
                        </a>
                        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors hover:bg-slate-800 hover:text-slate-200">
                            <span class="material-icons-round text-sm">auto_stories</span> Carga Académica
                        </a>
                    </nav>
                </div>

                <!-- Sección de Trámites y Certificaciones -->
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

            <!-- Botón de Cierre de Sesión Seguro -->
            <div class="p-4 border-t border-slate-800 bg-slate-950/40">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-xs font-bold text-rose-400 hover:bg-rose-950/30 rounded-xl transition-colors cursor-pointer">
                        <span class="material-icons-round text-sm">logout</span> Salir del Sistema
                    </button>
                </form>
            </div>
        </aside>

        <!-- CUERPO CENTRAL DE TRABAJO (Scroll Independiente para listados) -->
        <main class="flex-1 overflow-y-auto flex flex-col h-full bg-slate-50">
            @yield('content')
        </main>

    </div>

</body>
</html>

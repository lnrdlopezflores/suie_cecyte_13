<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Estudiantil SUIE - @yield('title')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link class="rounded-xs" rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
</head>
<body class="bg-slate-100 font-sans text-slate-800 h-screen flex flex-col overflow-hidden">

    <header class="bg-white border-b border-slate-200 px-4 md:px-6 h-16 flex justify-between items-center shrink-0 z-50 relative shadow-2xs">
        <div class="flex items-center space-x-3">
            <button id="btn-toggle-sidebar" class="md:hidden text-slate-600 hover:text-[#841B44] hover:bg-slate-100 p-2 rounded-xl focus:outline-hidden inline-flex items-center cursor-pointer transition-colors">
                <span class="material-icons-round text-2xl">menu</span>
            </button>
            
            <div class="flex items-center space-x-2">
                <span class="material-icons-round text-2xl text-[#841B44]">school</span>
                <div>
                    <h1 class="text-base font-black tracking-wider leading-none text-[#841B44]">SUIE</h1>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5 xs:block">Estudiantes</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-bold text-slate-900">{{ auth()->user()->username ?? 'Matrícula Alumno' }}</p>
                <p class="text-[10px] text-rose-700 font-bold uppercase tracking-wider mt-0.5">
                    @yield('grupo_badge', 'Estudiante Activo')
                </p>
            </div>
            <div class="w-8 h-8 bg-rose-50 text-[#841B44] rounded-xl flex items-center justify-center font-black border border-rose-200 text-xs shrink-0 select-none">
                ST
            </div>
        </div>
    </header>

    <div class="flex-1 flex overflow-hidden relative">
        
        <div id="sidebar-overlay" class="fixed inset-0 bg-slate-950/40 z-30 transition-opacity duration-300 opacity-0 pointer-events-none md:hidden"></div>
        
        <aside id="sidebar-menu" class="fixed md:static top-16 bottom-0 left-0 w-64 bg-slate-900 text-slate-400 flex flex-col h-[calc(100vh-4rem)] md:h-full shrink-0 border-r border-slate-800 z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out justify-between">
            
            <div class="p-4 space-y-6 overflow-y-auto flex-1">
                
                <div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-3 block mb-2 tracking-wider">General</span>
                    <nav class="space-y-1">
                        <a href="{{ route('indexalumnos.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('indexalumnos.index') ? 'bg-[#841B44] text-white shadow-xs' : 'hover:bg-slate-800 hover:text-slate-200' }}">
                            <span class="material-icons-round text-sm">dashboard</span> Panel de Inicio
                        </a>
                    </nav>
                </div>

                <div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-3 block mb-2 tracking-wider">Servicios Escolares</span>
                    <nav class="space-y-1">
                        <a href="{{ route('indexmaterias.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('indexmaterias.index') ? 'bg-[#841B44] text-white shadow-xs' : 'hover:bg-slate-800 hover:text-slate-200' }}">
                            <span class="material-icons-round text-sm">auto_stories</span> Mis Materias
                        </a>
                        <a href="{{ route('alumnoPagos.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('alumnoPagos.index') ? 'bg-[#841B44] text-white shadow-xs' : 'hover:bg-slate-800 hover:text-slate-200' }}">
                            <span class="material-icons-round text-sm">payments</span> Control de Pagos
                        </a>

                        @php
                            // Consultamos el semestre y estatus de titulación del alumno autenticado
                            $alumnoGlobal = DB::table('alumnos')
                                ->leftJoin('grupos', 'alumnos.grupo_id', '=', 'grupos.id')
                                ->select('alumnos.id', 'grupos.semestre')
                                ->where('alumnos.usuario_id', auth()->id())
                                ->first();

                            $semestreAlumno = $alumnoGlobal->semestre ?? 0;

                            $proyectoGlobal = null;
                            if ($alumnoGlobal) {
                                $proyectoGlobal = DB::table('proyectos_titulacion')
                                    ->join('proyecto_alumno', 'proyectos_titulacion.id', '=', 'proyecto_alumno.proyecto_id')
                                    ->select('proyectos_titulacion.estatus')
                                    ->where('proyecto_alumno.alumno_id', $alumnoGlobal->id)
                                    ->first();
                            }

                            $proyectoAprobado = ($proyectoGlobal && $proyectoGlobal->estatus === 'Aprobado');
                        @endphp

                        <!-- 1. MÓDULO: PROYECTOS DE TITULACIÓN (Solo 6to Semestre) -->
                        @if($semestreAlumno >= 6)
                            <a href="{{ route('titulacion.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('titulacion.*') ? 'bg-[#841B44] text-white shadow-xs' : 'hover:bg-slate-800 hover:text-slate-200' }}">
                                <span class="material-icons-round text-sm">history_edu</span> Proyectos de Titulación
                            </a>
                        @else
                            <button type="button" onclick="mostrarAlertaBloqueo('ModuloBloqueadoSemestre')" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-800/50 hover:text-slate-400 transition-colors cursor-pointer">
                                <div class="flex items-center gap-3">
                                    <span class="material-icons-round text-sm">history_edu</span> Proyectos de Titulación
                                </div>
                                <span class="material-icons-round text-xs text-amber-500">lock</span>
                            </button>
                        @endif

                        <!-- 2. MÓDULO: PROCESO DE TITULACIÓN (Solo si el Proyecto está Aprobado) -->
                        @if($semestreAlumno >= 6 && $proyectoAprobado)
                            <a href="{{ route('proceso.titulacion.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors {{ request()->routeIs('proceso.titulacion.*') ? 'bg-[#841B44] text-white shadow-xs' : 'hover:bg-slate-800 hover:text-slate-200' }}">
                                <span class="material-icons-round text-sm">assignment_turned_in</span> Proceso de Titulación
                            </a>
                        @else
                            <button type="button" onclick="mostrarAlertaBloqueo('ModuloBloqueadoProyecto')" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-800/50 hover:text-slate-400 transition-colors cursor-pointer">
                                <div class="flex items-center gap-3">
                                    <span class="material-icons-round text-sm">assignment_turned_in</span> Proceso de Titulación
                                </div>
                                <span class="material-icons-round text-xs text-amber-500">lock</span>
                            </button>
                        @endif

                    </nav>
                </div>
            </div>

            <div class="p-4 border-t border-slate-800 bg-slate-950/40 shrink-0">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-xs font-bold text-rose-400 hover:bg-rose-950/30 rounded-xl transition-all cursor-pointer">
                        <span class="material-icons-round text-sm">logout</span> Cerrar Sesión Portal
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto bg-slate-50 w-full">
            @yield('content')
        </main>

    </div>

    <!-- MODAL DE ALERTA DE MÓDULO BLOQUEADO -->
    <div id="modalBloqueo" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white w-full max-w-sm rounded-2xl shadow-xl overflow-hidden p-6 text-center space-y-4">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mx-auto border border-amber-100">
                <span class="material-icons-round text-2xl">lock</span>
            </div>

            <div class="space-y-1">
                <h3 id="tituloBloqueo" class="text-sm font-extrabold text-slate-900">Acceso Restringido</h3>
                <p id="mensajeBloqueo" class="text-xs text-slate-500 leading-relaxed"></p>
            </div>

            <button onclick="cerrarAlertaBloqueo()" class="w-full py-2.5 bg-[#841B44] hover:bg-[#681535] text-white font-bold rounded-xl transition-colors cursor-pointer text-xs">
                Entendido
            </button>
        </div>
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

        function mostrarAlertaBloqueo(tipo) {
            const modal = document.getElementById('modalBloqueo');
            const titulo = document.getElementById('tituloBloqueo');
            const mensaje = document.getElementById('mensajeBloqueo');

            if (tipo === 'ModuloBloqueadoSemestre') {
                titulo.innerText = 'Trámite no Disponible';
                mensaje.innerText = 'El módulo de Proyectos de Titulación se habilita únicamente para estudiantes inscritos en el 6° semestre.';
            } else if (tipo === 'ModuloBloqueadoProyecto') {
                titulo.innerText = 'Proyecto en Revisión o No Registrado';
                mensaje.innerText = 'El Proceso de Titulación (carga de documentos oficiales) estará disponible una vez que tu proyecto de titulación haya sido aprobado por el comité evaluador.';
            }

            modal.classList.remove('hidden');
        }

        function cerrarAlertaBloqueo() {
            document.getElementById('modalBloqueo').classList.add('hidden');
        }
    </script>

</body>
</html>
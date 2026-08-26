<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUIE - CECyTE 13 Tepetitla</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
</head>
<body class="bg-slate-50 font-sans text-slate-800 text-base md:text-lg flex flex-col min-h-screen overflow-x-hidden scroll-smooth">

    <!-- HEADER / NAVEGACIÓN -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 px-6 lg:px-10 py-4 shadow-sm">
        <div class="max-w-7xl w-full mx-auto flex justify-between items-center">
            
            <div class="flex items-center space-x-5">
                <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center p-1.5 border border-slate-200 overflow-hidden shrink-0 shadow-xs">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo Institución" class="w-full h-full object-contain">
                </div>
                <div class="border-l-2 border-slate-200 pl-5">
                    <h1 class="text-2xl lg:text-3xl font-black tracking-wider leading-none text-[#841B44]">SUIE</h1>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mt-1">CECyTE 13 Tepetitla</p>
                </div>
            </div>

            <div class="flex items-center space-x-3 md:space-x-6">
                <a href="#oferta" class="hidden sm:block text-sm lg:text-base font-bold text-slate-600 hover:text-[#841B44] transition-colors px-3 py-2">Especialidades</a>
                <a href="#comunidad" class="hidden sm:block text-sm lg:text-base font-bold text-slate-600 hover:text-[#841B44] transition-colors px-3 py-2">Comunidad</a>
                <a href="#areas" class="hidden md:block text-sm lg:text-base font-bold text-slate-600 hover:text-[#841B44] transition-colors px-3 py-2">Módulos</a>
                
                @if(auth()->check())
                    <a href="{{ route('login') }}" class="px-6 py-3.5 bg-[#841B44] hover:bg-[#6b1536] text-white text-sm lg:text-base font-bold rounded-2xl shadow-md transition-all flex items-center gap-2.5 cursor-pointer">
                        <span class="material-icons-round text-lg">dashboard</span> Mi Panel
                    </a>
                @else
                    <button onclick="toggleLoginModal()" class="px-6 py-3.5 bg-[#841B44] hover:bg-[#6b1536] text-white text-sm lg:text-base font-bold rounded-2xl shadow-md transition-all flex items-center gap-2.5 cursor-pointer">
                        <span class="material-icons-round text-lg">lock</span> Iniciar Sesión
                    </button>
                @endif
            </div>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="relative bg-white border-b border-slate-200 overflow-hidden">
        <div class="max-w-7xl w-full mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center px-6 lg:px-10 py-16 md:py-24">
            
            <div class="lg:col-span-6 space-y-8 text-center lg:text-left z-10">
                <span class="inline-flex items-center px-4 py-1.5 bg-rose-50 border border-rose-200 text-[#841B44] text-sm font-extrabold rounded-full">
                    <span class="material-icons-round text-sm mr-1.5">school</span>#OrgullosamenteCECyTE
                </span>
                <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black text-slate-900 tracking-tight leading-tight">
                    Construyendo el futuro de nuestra <span class="text-[#841B44]">juventud</span>.
                </h2>
                <p class="text-slate-600 text-base sm:text-lg md:text-xl max-w-2xl mx-auto lg:mx-0 leading-relaxed font-normal">
                    Bienvenidos al portal oficial del SUIE para el CECyTE 13. Una ventana abierta a nuestra excelencia académica, tecnológica y administrativa diseñada para impulsar la comunicación de toda la comunidad escolar.
                </p>
                <div class="pt-4 flex flex-wrap justify-center lg:justify-start gap-4">
                    @if(auth()->check())
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-[#841B44] hover:bg-[#6b1536] text-white font-bold text-sm sm:text-base rounded-2xl shadow-lg transition-all cursor-pointer">
                            Ir a mi Escritorio Activo
                        </a>
                    @else
                        <button onclick="toggleLoginModal()" class="px-8 py-4 bg-[#841B44] hover:bg-[#6b1536] text-white font-bold text-sm sm:text-base rounded-2xl shadow-lg transition-all cursor-pointer">
                            Ingresar al Portal
                        </button>
                    @endif
                    <a href="#oferta" class="px-8 py-4 border-2 border-slate-300 text-slate-700 font-bold text-sm sm:text-base rounded-2xl hover:bg-slate-50 transition-all flex items-center justify-center">
                        Conoce Nuestra Oferta
                    </a>
                </div>
            </div>

            <!-- CARRUSEL -->
            <div class="lg:col-span-6 w-full relative max-w-2xl mx-auto">
                <div class="absolute -inset-4 bg-slate-100 rounded-4xl -rotate-1 scale-95 opacity-70"></div>
                
                <div class="relative bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-2xl aspect-4/3 w-full group">
                    <div id="carousel-slides" class="w-full h-full relative">
                        <div class="carousel-item absolute inset-0 opacity-100 transition-opacity duration-700 ease-in-out">
                            <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=800" alt="Instalaciones Plantel" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent p-8 flex flex-col justify-end">
                                <span class="text-xs sm:text-sm text-rose-300 font-extrabold uppercase tracking-wider">Infraestructura de Vanguardia</span>
                                <h4 class="text-white text-lg sm:text-xl font-extrabold mt-1 leading-snug">Laboratorios y aulas preparadas para el aprendizaje del mañana.</h4>
                            </div>
                        </div>
                        <div class="carousel-item absolute inset-0 opacity-0 transition-opacity duration-700 ease-in-out">
                            <img src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?q=80&w=800" alt="Estudiantes en Biblioteca" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent p-8 flex flex-col justify-end">
                                <span class="text-xs sm:text-sm text-rose-300 font-extrabold uppercase tracking-wider">Formación Tecnológica</span>
                                <h4 class="text-white text-lg sm:text-xl font-extrabold mt-1 leading-snug">Bachillerato Tecnológico con cédula y título profesional.</h4>
                            </div>
                        </div>
                        <div class="carousel-item absolute inset-0 opacity-0 transition-opacity duration-700 ease-in-out">
                            <img src="https://images.unsplash.com/photo-1544535830-9dff9e0d4bec?q=80&w=800" alt="Cuerpo Docente Académico" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent p-8 flex flex-col justify-end">
                                <span class="text-xs sm:text-sm text-rose-300 font-extrabold uppercase tracking-wider">Cuerpo Docente Calificado</span>
                                <h4 class="text-white text-lg sm:text-xl font-extrabold mt-1 leading-snug">Profesores enfocados en el desarrollo integral de los jóvenes.</h4>
                            </div>
                        </div>
                    </div>

                    <button onclick="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/90 hover:bg-white text-slate-800 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-md cursor-pointer">
                        <span class="material-icons-round text-xl">chevron_left</span>
                    </button>
                    <button onclick="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/90 hover:bg-white text-slate-800 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-md cursor-pointer">
                        <span class="material-icons-round text-xl">chevron_right</span>
                    </button>

                    <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex space-x-2.5 z-20" id="carousel-indicators">
                        <span class="w-3 h-3 rounded-full bg-white transition-all cursor-pointer opacity-100" onclick="goToSlide(0)"></span>
                        <span class="w-3 h-3 rounded-full bg-white transition-all cursor-pointer opacity-40" onclick="goToSlide(1)"></span>
                        <span class="w-3 h-3 rounded-full bg-white transition-all cursor-pointer opacity-40" onclick="goToSlide(2)"></span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ESTADÍSTICAS -->
    <section id="stats-section" class="bg-slate-900 text-white py-16 px-6 lg:px-10">
        <div class="max-w-7xl w-full mx-auto grid grid-cols-2 md:grid-cols-4 gap-10 text-center">
            <div class="space-y-2">
                <p class="text-4xl md:text-5xl lg:text-6xl font-black text-rose-400"><span class="counter" data-target="100">0</span>%</p>
                <p class="text-xs sm:text-sm uppercase font-extrabold tracking-wider text-slate-300">Bachillerato Bivalente</p>
            </div>
            <div class="space-y-2">
                <p class="text-4xl md:text-5xl lg:text-6xl font-black text-rose-400">+<span class="counter" data-target="200">0</span></p>
                <p class="text-xs sm:text-sm uppercase font-extrabold tracking-wider text-slate-300">Alumnos Activos</p>
            </div>
            <div class="space-y-2">
                <p class="text-4xl md:text-5xl lg:text-6xl font-black text-rose-400"><span class="counter" data-target="2">0</span></p>
                <p class="text-xs sm:text-sm uppercase font-extrabold tracking-wider text-slate-300">Carreras Técnicas</p>
            </div>
            <div class="space-y-2">
                <p class="text-4xl md:text-5xl lg:text-6xl font-black text-rose-400">+<span class="counter" data-target="10">0</span></p>
                <p class="text-xs sm:text-sm uppercase font-extrabold tracking-wider text-slate-300">Docentes Especializados</p>
            </div>
        </div>
    </section>

    <!-- ESPECIALIDADES -->
    <section id="oferta" class="py-20 md:py-24 px-6 lg:px-10 max-w-6xl w-full mx-auto space-y-16">
        <div class="text-center max-w-3xl mx-auto space-y-4">
            <span class="text-xs sm:text-sm font-black text-[#841B44] uppercase tracking-widest bg-rose-50 px-4 py-1.5 rounded-full border border-rose-100">
                Formación Profesional
            </span>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                Nuestras Especialidades Técnicas
            </h2>
            <div class="h-1.5 w-16 bg-[#841B44] mx-auto rounded-full mt-2"></div>
            <p class="text-slate-600 text-base md:text-lg leading-relaxed pt-2">
                Estudia tu bachillerato general y egresa simultáneamente con una carrera técnica profesional respaldada por título y cédula oficial.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 max-w-5xl mx-auto">
            
            <div class="bg-white p-10 rounded-3xl border border-slate-200/80 shadow-md hover:shadow-xl hover:border-rose-300 hover:-translate-y-1.5 transition-all duration-300 space-y-6 flex flex-col justify-between group">
                <div class="space-y-5">
                    <div class="w-16 h-16 bg-rose-50 text-[#841B44] rounded-2xl flex items-center justify-center border border-rose-100 group-hover:bg-[#841B44] group-hover:text-white transition-colors duration-300 shadow-xs">
                        <span class="material-icons-round text-3xl">animation</span>
                    </div>
                    <h4 class="font-black text-slate-900 text-xl md:text-2xl tracking-tight">
                        Técnico en Animación Digital
                    </h4>
                    <p class="text-sm md:text-base text-slate-600 leading-relaxed font-normal">
                        Explora el mundo de la creación visual. Domina las técnicas de edición de contenido digital, modelado tridimensional, efectos especiales y procesos de animación utilizando software estándar de la industria creativa.
                    </p>
                </div>
            </div>

            <div class="bg-white p-10 rounded-3xl border border-slate-200/80 shadow-md hover:shadow-xl hover:border-indigo-300 hover:-translate-y-1.5 transition-all duration-300 space-y-6 flex flex-col justify-between group">
                <div class="space-y-5">
                    <div class="w-16 h-16 bg-indigo-50 text-indigo-700 rounded-2xl flex items-center justify-center border border-indigo-100 group-hover:bg-indigo-700 group-hover:text-white transition-colors duration-300 shadow-xs">
                        <span class="material-icons-round text-3xl">science</span>
                    </div>
                    <h4 class="font-black text-slate-900 text-xl md:text-2xl tracking-tight">
                        Técnico en Química Industrial
                    </h4>
                    <p class="text-sm md:text-base text-slate-600 leading-relaxed font-normal">
                        Desarrolla competencias operativas clave en el sector productivo. Capacítate en la ejecución y control de procesos químicos, análisis instrumentales de laboratorio, control de calidad y normas de bioseguridad industrial.
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- COMUNIDAD ESCOLAR -->
    <section id="comunidad" class="bg-slate-100 py-20 md:py-24 px-6 lg:px-10 border-y border-slate-200">
        <div class="max-w-7xl w-full mx-auto space-y-16">
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <h3 class="text-xs sm:text-sm font-extrabold text-[#841B44] uppercase tracking-widest">Canales Institucionales</h3>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900">Servicios para la Comunidad Escolar</h2>
                <p class="text-slate-600 text-base md:text-lg">Acceso directo y herramientas personalizadas según tu perfil dentro del bachillerato.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-10">
                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm hover:shadow-md transition-all space-y-6 flex flex-col justify-between">
                    <div class="space-y-5">
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-700 border border-blue-100 flex items-center justify-center">
                            <span class="material-icons-round text-3xl">face</span>
                        </div>
                        <h4 class="font-black text-lg md:text-xl text-slate-900">Alumnos y Estudiantes</h4>
                        <p class="text-sm md:text-base text-slate-600 leading-relaxed">
                            Consulta tu carga académica asignada, revisa tus horarios oficiales y reporta tus fichas de depósito de forma cifrada y segura para agilizar la validación de tus pagos.
                        </p>
                    </div>
                    <button onclick="toggleLoginModal()" class="w-full mt-3 py-3.5 bg-slate-50 hover:bg-slate-100 border border-slate-300 text-slate-800 text-sm md:text-base font-bold rounded-2xl transition-colors cursor-pointer">
                        Ingresar a mi Portal Estudiantil
                    </button>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm hover:shadow-md transition-all space-y-6 flex flex-col justify-between">
                    <div class="space-y-5">
                        <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-700 border border-purple-100 flex items-center justify-center">
                            <span class="material-icons-round text-3xl">co_present</span>
                        </div>
                        <h4 class="font-black text-lg md:text-xl text-slate-900">Personal Docente</h4>
                        <p class="text-sm md:text-base text-slate-600 leading-relaxed">
                            Organiza tus asignaturas, audita las listas de asistencia de tus grupos asignados y gestiona las planeaciones curriculares alineadas con los indicadores terminales vigentes.
                        </p>
                    </div>
                    <button onclick="toggleLoginModal()" class="w-full mt-3 py-3.5 bg-slate-50 hover:bg-slate-100 border border-slate-300 text-slate-800 text-sm md:text-base font-bold rounded-2xl transition-colors cursor-pointer">
                        Acceder como Profesor
                    </button>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm hover:shadow-md transition-all space-y-6 flex flex-col justify-between">
                    <div class="space-y-5">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center">
                            <span class="material-icons-round text-3xl">admin_panel_settings</span>
                        </div>
                        <h4 class="font-black text-lg md:text-xl text-slate-900">Administración</h4>
                        <p class="text-sm md:text-base text-slate-600 leading-relaxed">
                            Módulos técnicos para Control Escolar, Orientación Educativa y control de pagos. Administra matrículas y gestiona expedientes de titulación.
                        </p>
                    </div>
                    <button onclick="toggleLoginModal()" class="w-full mt-3 py-3.5 bg-[#841B44] hover:bg-[#6b1536] text-white text-sm md:text-base font-bold rounded-2xl transition-colors cursor-pointer shadow-sm">
                        Acceso de Personal Autorizado
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- MÓDULOS ADMINISTRATIVOS -->
    <section id="areas" class="py-20 md:py-24 px-6 lg:px-10 max-w-7xl w-full mx-auto space-y-16">
        <div class="text-center max-w-3xl mx-auto space-y-3">
            <h3 class="text-xs sm:text-sm font-extrabold text-[#841B44] uppercase tracking-widest">Matriz de Trabajo</h3>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900">Módulos Administrativos Centrales</h2>
            <p class="text-slate-600 text-base md:text-lg">Administración y seguimiento coordinado para la mejora de indicadores del plantel.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm hover:shadow-md transition-all space-y-5">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-[#E66711] border border-amber-100 flex items-center justify-center">
                    <span class="material-icons-round text-3xl">gavel</span>
                </div>
                <h4 class="font-black text-lg md:text-xl text-slate-900">Coordinación Institucional</h4>
                <p class="text-sm md:text-base text-slate-600 leading-relaxed">
                    Supervisión global de la carga docente, planeaciones académicas, asignación de aulas e inspección de indicadores de eficiencia terminal del ciclo activo.
                </p>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm hover:shadow-md transition-all space-y-5">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-[#0F7F41] border border-emerald-100 flex items-center justify-center">
                    <span class="material-icons-round text-3xl">assignment_ind</span>
                </div>
                <h4 class="font-black text-lg md:text-xl text-slate-900">Orientación Educativa</h4>
                <p class="text-sm md:text-base text-slate-600 leading-relaxed">
                    Monitoreo analítico de inasistencias en tiempo real, gestión de bitácoras de conducta y envío de alertas automatizadas a tutores para mitigar la deserción.
                </p>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm hover:shadow-md transition-all space-y-5">
                <div class="w-14 h-14 rounded-2xl bg-[#841B44]/10 text-[#841B44] flex items-center justify-center">
                    <span class="material-icons-round text-3xl">analytics</span>
                </div>
                <h4 class="font-black text-lg md:text-xl text-slate-900">Control Escolar</h4>
                <p class="text-sm md:text-base text-slate-600 leading-relaxed">
                    Validación digital de expedientes de titulación, emisión de constancias y administración matricular integral.
                </p>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-slate-900 text-slate-300 text-sm md:text-base py-12 px-6 lg:px-10 mt-auto border-t border-slate-800">
        <div class="max-w-7xl w-full mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="font-medium">© 2026 SUIE. Sistema Unificado de Integración Educativa. Todos los derechos reservados.</p>
            <p class="text-xs md:text-sm text-slate-400">Módulos de Gestión Técnica y Administrativa de Media Superior.</p>
        </div>
    </footer>

    <!-- MODAL DE LOGIN -->
    <div id="loginModal" 
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 sm:p-6 transition-all duration-300 {{ $errors->has('username') ? 'opacity-100' : 'opacity-0 pointer-events-none' }}">
        
        <div class="bg-white w-full max-w-lg rounded-3xl p-8 shadow-2xl space-y-6 transform transition-all duration-300 ease-out {{ $errors->has('username') ? 'scale-100' : 'scale-95' }}" id="loginBox">
            
            <div class="flex justify-between items-center border-b border-slate-100 pb-5">
                <div>
                    <h3 class="font-black text-2xl text-slate-900">Iniciar Sesión</h3>
                    <p class="text-sm md:text-base text-slate-500 mt-0.5">Introduce tus credenciales de acceso institucional</p>
                </div>
                <button onclick="toggleLoginModal()" class="p-2.5 text-slate-400 hover:text-slate-600 rounded-xl transition-colors cursor-pointer focus:outline-hidden">
                    <span class="material-icons-round text-2xl">close</span>
                </button>
            </div>

            @error('username')
                <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl text-sm flex items-start gap-3">
                    <span class="material-icons-round text-lg mt-0.5 shrink-0">error</span>
                    <p class="font-semibold">{{ $message }}</p>
                </div>
            @enderror

            <form class="space-y-5 text-sm md:text-base" action="{{ route('login') }}" method="POST">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-2">Usuario / Matrícula</label>
                    <input type="text" name="username" value="{{ old('username') }}" required 
                           class="w-full bg-slate-50 border @error('username') border-rose-400 focus:ring-rose-500 @else border-slate-300 focus:ring-[#841B44] @enderror rounded-2xl p-4 font-medium focus:outline-hidden" 
                           placeholder="Ej: ADM-2405 o 22240105">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-2">Contraseña</label>
                    <input type="password" name="password" required 
                           class="w-full bg-slate-50 border @error('username') border-rose-400 focus:ring-rose-500 @else border-slate-300 focus:ring-[#841B44] @enderror rounded-2xl p-4 font-medium focus:outline-hidden" 
                           placeholder="••••••••">
                </div>
                <div class="pt-3">
                    <button type="submit" class="w-full py-4 bg-[#841B44] hover:bg-[#6b1536] text-white font-extrabold text-base rounded-2xl shadow-md transition-colors cursor-pointer">
                        Ingresar de Forma Segura
                    </button>
                </div>
            </form>

            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-xs sm:text-sm text-slate-500 leading-relaxed flex items-start gap-3">
                <span class="material-icons-round text-lg text-[#0F7F41] shrink-0 mt-0.5">verified_user</span>
                <p><strong>Aviso de seguridad:</strong> El acceso está reservado exclusivamente para la comunidad del plantel. Los intentos no autorizados quedarán asentados en la bitácora del sistema.</p>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        // Lógica del Carrusel
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-item');
        const indicators = document.getElementById('carousel-indicators').children;
        const totalSlides = slides.length;
        let carouselInterval = setInterval(nextSlide, 5000);

        function updateCarouselVisuals() {
            slides.forEach((slide, index) => {
                if (index === currentSlide) {
                    slide.classList.remove('opacity-0');
                    slide.classList.add('opacity-100');
                    indicators[index].classList.remove('opacity-40');
                    indicators[index].classList.add('opacity-100');
                } else {
                    slide.classList.remove('opacity-100');
                    slide.classList.add('opacity-0');
                    indicators[index].classList.remove('opacity-100');
                    indicators[index].classList.add('opacity-40');
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateCarouselVisuals();
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateCarouselVisuals();
        }

        function goToSlide(index) {
            currentSlide = index;
            updateCarouselVisuals();
            resetInterval();
        }

        function resetInterval() {
            clearInterval(carouselInterval);
            carouselInterval = setInterval(nextSlide, 5000);
        }

        // Lógica del Modal
        function toggleLoginModal() {
            const modal = document.getElementById('loginModal');
            const box = document.getElementById('loginBox');
            
            if (modal.classList.contains('pointer-events-none') || modal.classList.contains('opacity-0')) {
                modal.classList.remove('pointer-events-none', 'opacity-0');
                modal.classList.add('opacity-100');
                box.classList.remove('scale-95');
                box.classList.add('scale-100');
            } else {
                modal.classList.remove('opacity-100');
                modal.classList.add('pointer-events-none', 'opacity-0');
                box.classList.remove('scale-100');
                box.classList.add('scale-95');
            }
        }

        // Lógica de los Contadores Animados
        const startCounters = () => {
            const counters = document.querySelectorAll('.counter');
            const speed = 60;

            counters.forEach(counter => {
                const animate = () => {
                    const target = +counter.getAttribute('data-target');
                    const current = +counter.innerText;
                    const increment = Math.ceil(target / speed);

                    if (current < target) {
                        counter.innerText = Math.min(current + increment, target);
                        setTimeout(animate, 25);
                    } else {
                        counter.innerText = target;
                    }
                };
                animate();
            });
        };

        const statsSection = document.getElementById('stats-section');
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    startCounters();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });

        observer.observe(statsSection);
    </script>

</body>
</html>
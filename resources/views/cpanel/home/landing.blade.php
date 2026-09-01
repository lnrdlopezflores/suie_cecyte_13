<!DOCTYPE html>
<html lang="es" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUIE - CECyTE 13 Tepetitla</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <style type="text/tailwindcss">
        @custom-variant dark (&:where(.dark, .dark *));
    </style>

    <style>
        :root {
            --color-primary: {{ $colorPrimario ?? '#841B44' }};
            --color-primary-hover: {{ $colorHover ?? '#681535' }};
            --color-primary-light: {{ $colorLight ?? '#fdf2f4' }};
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Clases utilitarias dinámicas */
        .bg-custom-primary { background-color: var(--color-primary) !important; color: #ffffff !important; }
        .hover\:bg-custom-primary-hover:hover { background-color: var(--color-primary-hover) !important; color: #ffffff !important; }
        .text-custom-primary { color: var(--color-primary) !important; }
        .border-custom-primary { border-color: var(--color-primary) !important; }
        .bg-custom-light { background-color: var(--color-primary-light) !important; }
        .focus\:ring-custom-primary:focus { --tw-ring-color: var(--color-primary) !important; }

        /* Sobrescritura forzada de clases estáticas heredadas */
        [class*="bg-[#841B44]"],
        [class*="bg-\[\#841B44\]"] {
            background-color: var(--color-primary) !important;
            color: #ffffff !important;
        }

        [class*="text-[#841B44]"],
        [class*="text-\[\#841B44\]"] {
            color: var(--color-primary) !important;
        }

        [class*="border-[#841B44]"],
        [class*="border-\[\#841B44\]"] {
            border-color: var(--color-primary) !important;
        }

        [class*="hover:bg-[#6b1536]"]:hover,
        [class*="hover:bg-[#681535]"]:hover,
        [class*="hover:bg-\[\#6b1536\]"]:hover,
        [class*="hover:bg-\[\#681535\]"]:hover {
            background-color: var(--color-primary-hover) !important;
            color: #ffffff !important;
        }

        [class*="hover:text-[#841B44]"]:hover,
        [class*="hover:text-\[\#841B44\]"]:hover {
            color: var(--color-primary) !important;
        }

        [class*="bg-rose-50"] {
            background-color: var(--color-primary-light) !important;
        }

        /* Fondos decorativos sutiles */
        .bg-grid-pattern {
            background-size: 32px 32px;
            background-image: 
                linear-gradient(to right, rgba(226, 232, 240, 0.4) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(226, 232, 240, 0.4) 1px, transparent 1px);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 text-sm sm:text-base flex flex-col min-h-screen overflow-x-hidden selection:bg-custom-primary selection:text-white antialiased">

    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-slate-200/80 transition-all">
        <div class="max-w-7xl w-full mx-auto px-5 sm:px-8 py-3.5 flex justify-between items-center">
            
            <a href="#" class="flex items-center space-x-4 group">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center p-1.5 border border-slate-200/80 shadow-xs group-hover:scale-105 transition-transform">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo Institución" class="w-full h-full object-contain">
                </div>
                <div class="border-l border-slate-200 pl-4">
                    <h1 class="text-xl sm:text-2xl font-black tracking-wider leading-none text-custom-primary">SUIE</h1>
                    <p class="text-[10px] sm:text-[11px] font-extrabold uppercase tracking-widest text-slate-400 mt-0.5">CECyTE 13 Tepetitla</p>
                </div>
            </a>

            <div class="flex items-center space-x-2 sm:space-x-6">
                <nav class="hidden md:flex items-center space-x-1 font-semibold text-xs sm:text-sm text-slate-600">
                    <a href="#oferta" class="px-3.5 py-2 rounded-xl hover:text-custom-primary hover:bg-slate-100/60 transition-all">Especialidades</a>
                    <a href="#comunidad" class="px-3.5 py-2 rounded-xl hover:text-custom-primary hover:bg-slate-100/60 transition-all">Comunidad</a>
                    <a href="#areas" class="px-3.5 py-2 rounded-xl hover:text-custom-primary hover:bg-slate-100/60 transition-all">Módulos</a>
                </nav>
                
                @if(auth()->check())
                    <a href="{{ route('login') }}" class="px-5 py-2.5 sm:px-6 sm:py-3 bg-custom-primary hover:bg-custom-primary-hover text-white text-xs sm:text-sm font-bold rounded-xl sm:rounded-2xl shadow-sm hover:shadow-md transition-all flex items-center gap-2 cursor-pointer">
                        <span class="material-icons-round text-base">dashboard</span>
                        <span>Mi Panel</span>
                    </a>
                @else
                    <button type="button" onclick="toggleLoginModal()" class="px-5 py-2.5 sm:px-6 sm:py-3 bg-custom-primary hover:bg-custom-primary-hover text-white text-xs sm:text-sm font-bold rounded-xl sm:rounded-2xl shadow-sm hover:shadow-md transition-all flex items-center gap-2 cursor-pointer">
                        <span class="material-icons-round text-base">lock</span>
                        <span>Iniciar Sesión</span>
                    </button>
                @endif
            </div>
        </div>
    </header>

    <section class="relative bg-white border-b border-slate-200 overflow-hidden py-16 sm:py-24 bg-grid-pattern">
        <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-custom-primary opacity-5 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-indigo-500 opacity-5 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl w-full mx-auto px-5 sm:px-8 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-14 items-center relative z-10">
            
            <div class="lg:col-span-6 space-y-6 sm:space-y-8 text-center lg:text-left">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-custom-light border border-custom-primary/30 text-custom-primary text-xs sm:text-sm font-bold rounded-full shadow-2xs">
                    <span class="material-icons-round text-sm">school</span>
                    <span>#OrgullosamenteCECyTE</span>
                </span>
                
                <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-tight sm:leading-none">
                    Construyendo el futuro de nuestra <span class="text-custom-primary underline decoration-custom-primary/30 decoration-wavy decoration-2">juventud</span>.
                </h2>
                
                <p class="text-slate-600 text-sm sm:text-base lg:text-lg max-w-xl mx-auto lg:mx-0 leading-relaxed font-normal">
                    Bienvenidos al portal oficial del <strong class="text-slate-800">SUIE</strong> para el plantel CECyTE 13. Una plataforma integral para la gestión académica, seguimiento escolar y acreditación técnica profesional.
                </p>
                
                <div class="pt-2 flex flex-wrap justify-center lg:justify-start gap-3 sm:gap-4">
                    @if(auth()->check())
                        <a href="{{ route('login') }}" class="px-7 py-3.5 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold text-xs sm:text-sm rounded-2xl shadow-md hover:shadow-lg transition-all cursor-pointer flex items-center gap-2">
                            <span>Ir a mi Escritorio</span>
                            <span class="material-icons-round text-base">arrow_forward</span>
                        </a>
                    @else
                        <button type="button" onclick="toggleLoginModal()" class="px-7 py-3.5 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold text-xs sm:text-sm rounded-2xl shadow-md hover:shadow-lg transition-all cursor-pointer flex items-center gap-2">
                            <span>Ingresar al Portal</span>
                            <span class="material-icons-round text-base">login</span>
                        </button>
                    @endif
                    <a href="#oferta" class="px-7 py-3.5 border border-slate-300 bg-white/80 backdrop-blur-xs text-slate-700 font-bold text-xs sm:text-sm rounded-2xl hover:bg-slate-50 transition-all flex items-center justify-center">
                        Conoce la Oferta
                    </a>
                </div>
            </div>

            <div class="lg:col-span-6 w-full max-w-xl mx-auto">
                <div class="relative bg-slate-900 border border-slate-200/80 rounded-3xl overflow-hidden shadow-2xl aspect-4/3 w-full group">
                    
                    <div id="carousel-slides" class="w-full h-full relative">
                        <div class="carousel-item absolute inset-0 opacity-100 transition-opacity duration-700 ease-in-out">
                            <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=800" alt="Instalaciones Plantel" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent p-6 sm:p-8 flex flex-col justify-end">
                                <span class="text-[11px] text-custom-light font-extrabold uppercase tracking-wider">Infraestructura de Vanguardia</span>
                                <h4 class="text-white text-base sm:text-lg font-bold mt-1 leading-snug">Laboratorios equipados y aulas preparadas para el desarrollo tecnológico.</h4>
                            </div>
                        </div>
                        <div class="carousel-item absolute inset-0 opacity-0 transition-opacity duration-700 ease-in-out">
                            <img src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?q=80&w=800" alt="Estudiantes en Biblioteca" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent p-6 sm:p-8 flex flex-col justify-end">
                                <span class="text-[11px] text-custom-light font-extrabold uppercase tracking-wider">Formación Dual y Tecnológica</span>
                                <h4 class="text-white text-base sm:text-lg font-bold mt-1 leading-snug">Bachillerato Tecnológico con título y cédula profesional oficial.</h4>
                            </div>
                        </div>
                        <div class="carousel-item absolute inset-0 opacity-0 transition-opacity duration-700 ease-in-out">
                            <img src="https://images.unsplash.com/photo-1544535830-9dff9e0d4bec?q=80&w=800" alt="Cuerpo Docente" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent p-6 sm:p-8 flex flex-col justify-end">
                                <span class="text-[11px] text-custom-light font-extrabold uppercase tracking-wider">Docentes Calificados</span>
                                <h4 class="text-white text-base sm:text-lg font-bold mt-1 leading-snug">Cuerpo colegiado comprometido con el acompañamiento académico integral.</h4>
                            </div>
                        </div>
                    </div>

                    <button onclick="prevSlide()" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/80 hover:bg-white text-slate-800 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-md cursor-pointer">
                        <span class="material-icons-round text-xl">chevron_left</span>
                    </button>
                    <button onclick="nextSlide()" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/80 hover:bg-white text-slate-800 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-md cursor-pointer">
                        <span class="material-icons-round text-xl">chevron_right</span>
                    </button>

                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2 z-20" id="carousel-indicators">
                        <span class="w-2.5 h-2.5 rounded-full bg-white transition-all cursor-pointer opacity-100" onclick="goToSlide(0)"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-white transition-all cursor-pointer opacity-40" onclick="goToSlide(1)"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-white transition-all cursor-pointer opacity-40" onclick="goToSlide(2)"></span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section id="stats-section" class="bg-slate-900 text-white py-12 sm:py-16 px-5 sm:px-8 border-y border-slate-800">
        <div class="max-w-7xl w-full mx-auto grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-slate-800/80">
            <div class="pt-4 md:pt-0 space-y-1">
                <p class="text-3xl sm:text-5xl font-black text-custom-light"><span class="counter" data-target="100">0</span>%</p>
                <p class="text-xs uppercase font-extrabold tracking-wider text-slate-400">Bachillerato Bivalente</p>
            </div>
            <div class="pt-4 md:pt-0 space-y-1">
                <p class="text-3xl sm:text-5xl font-black text-custom-light">+<span class="counter" data-target="200">0</span></p>
                <p class="text-xs uppercase font-extrabold tracking-wider text-slate-400">Alumnos Matriculados</p>
            </div>
            <div class="pt-4 md:pt-0 space-y-1">
                <p class="text-3xl sm:text-5xl font-black text-custom-light"><span class="counter" data-target="2">0</span></p>
                <p class="text-xs uppercase font-extrabold tracking-wider text-slate-400">Especialidades Técnicas</p>
            </div>
            <div class="pt-4 md:pt-0 space-y-1">
                <p class="text-3xl sm:text-5xl font-black text-custom-light">+<span class="counter" data-target="10">0</span></p>
                <p class="text-xs uppercase font-extrabold tracking-wider text-slate-400">Docentes Titulares</p>
            </div>
        </div>
    </section>

    <section id="oferta" class="py-20 px-5 sm:px-8 max-w-7xl w-full mx-auto space-y-14">
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <span class="text-xs font-bold text-custom-primary uppercase tracking-widest bg-custom-light px-3.5 py-1 rounded-full border border-custom-primary/30">
                Oferta Educativa
            </span>
            <h2 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight">
                Especialidades con Título y Cédula
            </h2>
            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                Formación profesional técnica que te permite ingresar al sector productivo o continuar tus estudios universitarios.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            
            <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-custom-primary/60 transition-all duration-300 space-y-5 flex flex-col justify-between group">
                <div class="space-y-4">
                    <div class="w-14 h-14 bg-custom-light text-custom-primary rounded-2xl flex items-center justify-center border border-custom-primary/20 group-hover:bg-custom-primary group-hover:text-white transition-colors duration-300 shadow-2xs">
                        <span class="material-icons-round text-3xl">animation</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-widest block mb-1">Área Creativa & Tecnológica</span>
                        <h4 class="font-extrabold text-slate-900 text-lg sm:text-xl tracking-tight">
                            Técnico en Animación Digital
                        </h4>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                        Domina el modelado tridimensional, la creación de efectos visuales, ilustración digital y procesos de postproducción audiovisual utilizando software especializado de la industria.
                    </p>
                </div>
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-500">
                    <span>6 Semestres</span>
                    <span class="text-custom-primary group-hover:translate-x-1 transition-transform flex items-center gap-1">Conocer más <span class="material-icons-round text-sm">arrow_forward</span></span>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-indigo-400 transition-all duration-300 space-y-5 flex flex-col justify-between group">
                <div class="space-y-4">
                    <div class="w-14 h-14 bg-indigo-50 text-indigo-700 rounded-2xl flex items-center justify-center border border-indigo-100 group-hover:bg-indigo-700 group-hover:text-white transition-colors duration-300 shadow-2xs">
                        <span class="material-icons-round text-3xl">science</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-widest block mb-1">Área Industrial & Procesos</span>
                        <h4 class="font-extrabold text-slate-900 text-lg sm:text-xl tracking-tight">
                            Técnico en Química Industrial
                        </h4>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                        Capacítate en el control de calidad, análisis instrumentales de laboratorio químico, supervisión de procesos de manufactura y cumplimiento de normas de bioseguridad.
                    </p>
                </div>
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-500">
                    <span>6 Semestres</span>
                    <span class="text-indigo-700 group-hover:translate-x-1 transition-transform flex items-center gap-1">Conocer más <span class="material-icons-round text-sm">arrow_forward</span></span>
                </div>
            </div>

        </div>
    </section>

    <section id="comunidad" class="bg-slate-100/80 py-20 px-5 sm:px-8 border-y border-slate-200">
        <div class="max-w-7xl w-full mx-auto space-y-14">
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <h3 class="text-xs font-bold text-custom-primary uppercase tracking-widest">Portal Único</h3>
                <h2 class="text-2xl sm:text-4xl font-black text-slate-900">Perfiles de la Comunidad Escolar</h2>
                <p class="text-slate-600 text-xs sm:text-sm">Herramientas diseñadas a la medida según tu rol institucional.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                
                <div class="bg-white p-7 rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all space-y-5 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 border border-blue-100 flex items-center justify-center">
                            <span class="material-icons-round text-2xl">school</span>
                        </div>
                        <h4 class="font-extrabold text-base sm:text-lg text-slate-900">Estudiantes</h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Consulta tus materias, porcentaje de asistencias en tiempo real, registro de pagos y seguimiento de tu proyecto de titulación.
                        </p>
                    </div>
                    <button type="button" onclick="toggleLoginModal()" class="w-full py-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-800 text-xs sm:text-sm font-bold rounded-xl transition-colors cursor-pointer">
                        Ingresar a Alumnos
                    </button>
                </div>

                <div class="bg-white p-7 rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all space-y-5 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-700 border border-purple-100 flex items-center justify-center">
                            <span class="material-icons-round text-2xl">co_present</span>
                        </div>
                        <h4 class="font-extrabold text-base sm:text-lg text-slate-900">Plantilla Docente</h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Control de asistencias por parcial, gestión de cargas académicas y dictamen de proyectos de residencia como asesor y jurado.
                        </p>
                    </div>
                    <button type="button" onclick="toggleLoginModal()" class="w-full py-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-800 text-xs sm:text-sm font-bold rounded-xl transition-colors cursor-pointer">
                        Ingresar como Docente
                    </button>
                </div>

                <div class="bg-white p-7 rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all space-y-5 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center">
                            <span class="material-icons-round text-2xl">admin_panel_settings</span>
                        </div>
                        <h4 class="font-extrabold text-base sm:text-lg text-slate-900">Coordinación & Control</h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Distribución de cargas docentes, asignación de los 3 sínodos por carrera, validación de pagos y control de accesos.
                        </p>
                    </div>
                    <button type="button" onclick="toggleLoginModal()" class="w-full py-3 bg-custom-primary hover:bg-custom-primary-hover text-white text-xs sm:text-sm font-bold rounded-xl transition-colors cursor-pointer shadow-2xs">
                        Ingreso Administrativo
                    </button>
                </div>

            </div>
        </div>
    </section>

    <section id="areas" class="py-20 px-5 sm:px-8 max-w-7xl w-full mx-auto space-y-14">
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <h3 class="text-xs font-bold text-custom-primary uppercase tracking-widest">Organización</h3>
            <h2 class="text-2xl sm:text-4xl font-black text-slate-900">Módulos Administrativos Centrales</h2>
            <p class="text-slate-600 text-xs sm:text-sm">Gestión integral para mantener la excelencia académica del plantel.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-7 rounded-3xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center">
                    <span class="material-icons-round text-2xl">gavel</span>
                </div>
                <h4 class="font-bold text-base text-slate-900">Coordinación Académica</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Supervisión de horarios, asignación equitativa de sínodos examinadores y seguimiento de planes de estudio.
                </p>
            </div>

            <div class="bg-white p-7 rounded-3xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center">
                    <span class="material-icons-round text-2xl">assignment_ind</span>
                </div>
                <h4 class="font-bold text-base text-slate-900">Orientación Educativa</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Monitoreo oportuno de inasistencias acumuladas y acompañamiento psicopedagógico del estudiante.
                </p>
            </div>

            <div class="bg-white p-7 rounded-3xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-custom-light text-custom-primary flex items-center justify-center">
                    <span class="material-icons-round text-2xl">analytics</span>
                </div>
                <h4 class="font-bold text-base text-slate-900">Control Escolar</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Emisión de documentos oficiales, validación de pagos escolares y gestión de actas de titulación.
                </p>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 text-slate-300 text-xs sm:text-sm py-10 px-5 sm:px-8 mt-auto border-t border-slate-800">
        <div class="max-w-7xl w-full mx-auto flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
            <p class="font-medium">© 2026 SUIE — Sistema Unificado de Integración Educativa. CECyTE 13 Tepetitla.</p>
            <p class="text-slate-500">Módulos de Gestión Técnica y Administrativa de Media Superior.</p>
        </div>
    </footer>

    <div id="loginModal" 
         class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 transition-all duration-300 {{ $errors->has('username') ? 'opacity-100' : 'opacity-0 pointer-events-none' }}">
        
        <div class="bg-white w-full max-w-md rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6 transform transition-all duration-300 ease-out border border-slate-100 {{ $errors->has('username') ? 'scale-100' : 'scale-95' }}" id="loginBox">
            
            <div class="flex justify-between items-start border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-black text-xl sm:text-2xl text-slate-900">Iniciar Sesión</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Ingresa con tu usuario o matrícula institucional</p>
                </div>
                <button type="button" onclick="toggleLoginModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl transition-colors cursor-pointer focus:outline-hidden">
                    <span class="material-icons-round text-xl">close</span>
                </button>
            </div>

            @if(!empty($mostrarAvisoVeda))
                <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200/80 text-amber-900 text-xs flex items-start gap-2.5 shadow-2xs">
                    <span class="material-icons-round text-base text-amber-600 shrink-0 mt-0.5">gavel</span>
                    <p class="leading-relaxed font-medium">
                        Para poder cumplir con la normativa de la ley estatal y federal en materia electoral, este sistema modificará sus contenidos temporalmente.
                    </p>
                </div>
            @endif

            @error('username')
                <div class="bg-rose-50 border border-rose-200 text-rose-800 p-3.5 rounded-2xl text-xs flex items-start gap-2.5">
                    <span class="material-icons-round text-base mt-0.5 shrink-0 text-rose-600">error</span>
                    <p class="font-semibold">{{ $message }}</p>
                </div>
            @enderror

            <form class="space-y-4 text-xs sm:text-sm" action="{{ route('login') }}" method="POST">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Usuario / Matrícula</label>
                    <input type="text" name="username" value="{{ old('username') }}" required 
                           class="w-full bg-slate-50 text-slate-900 border @error('username') border-rose-400 focus:ring-rose-500 @else border-slate-300 focus:ring-custom-primary @enderror rounded-2xl p-3.5 font-medium focus:outline-hidden transition-all text-xs sm:text-sm" 
                           placeholder="Ej: 22240105 o DOC-102">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Contraseña</label>
                    <input type="password" name="password" required 
                           class="w-full bg-slate-50 text-slate-900 border @error('username') border-rose-400 focus:ring-rose-500 @else border-slate-300 focus:ring-custom-primary @enderror rounded-2xl p-3.5 font-medium focus:outline-hidden transition-all text-xs sm:text-sm" 
                           placeholder="••••••••">
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold text-xs sm:text-sm rounded-2xl shadow-md transition-all cursor-pointer flex items-center justify-center gap-2">
                        <span class="material-icons-round text-base">login</span>
                        <span>Ingresar al Sistema</span>
                    </button>
                </div>
            </form>

            <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200 text-[11px] text-slate-500 leading-relaxed flex items-start gap-2.5">
                <span class="material-icons-round text-base text-emerald-600 shrink-0 mt-0.5">verified_user</span>
                <p>Acceso cifrado y seguro para la comunidad educativa.</p>
            </div>
        </div>
    </div>

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

        // Animación de Contadores al hacer scroll
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

        if (statsSection) {
            observer.observe(statsSection);
        }
    </script>
</body>
</html>

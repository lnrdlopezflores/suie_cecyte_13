<!DOCTYPE html>
<html lang="es" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUIE - CECyTE 13 Tepetitla</title>
    
    <!-- Tailwind CSS v4 Browser -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <style type="text/tailwindcss">
        @custom-variant dark (&:where(.dark, .dark *));
    </style>

    <style>
        :root {
            --color-primary: {{ $colorPrimario ?? '#841B44' }};
            --color-primary-hover: {{ $colorHover ?? '#6b1536' }};
            --color-primary-light: {{ $colorLight ?? '#fdf2f8' }};
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Clases utilitarias dinámicas */
        .bg-custom-primary { background-color: var(--color-primary) !important; color: #ffffff !important; }
        .hover\:bg-custom-primary-hover:hover { background-color: var(--color-primary-hover) !important; color: #ffffff !important; }
        .text-custom-primary { color: var(--color-primary) !important; }
        .border-custom-primary { border-color: var(--color-primary) !important; }
        .bg-custom-light { background-color: var(--color-primary-light) !important; }
        .focus\:ring-custom-primary:focus { --tw-ring-color: var(--color-primary) !important; }

        /* Sobrescritura forzada de clases heredadas */
        [class*="bg-[#841B44]"], [class*="bg-\[\#841B44\]"] { background-color: var(--color-primary) !important; color: #ffffff !important; }
        [class*="text-[#841B44]"], [class*="text-\[\#841B44\]"] { color: var(--color-primary) !important; }
        [class*="border-[#841B44]"], [class*="border-\[\#841B44\]"] { border-color: var(--color-primary) !important; }

        [class*="hover:bg-[#6b1536]"]:hover, [class*="hover:bg-[#681535]"]:hover,
        [class*="hover:bg-\[\#6b1536\]"]:hover, [class*="hover:bg-\[\#681535\]"]:hover {
            background-color: var(--color-primary-hover) !important;
            color: #ffffff !important;
        }

        [class*="hover:text-[#841B44]"]:hover, [class*="hover:text-\[\#841B44\]"]:hover {
            color: var(--color-primary) !important;
        }

        [class*="bg-rose-50"] { background-color: var(--color-primary-light) !important; }

        /* MODO NEUTRAL (VEDA ELECTORAL) */
        @if(!empty($mostrarAvisoVeda))
            img, picture, svg, .carousel-item img { filter: grayscale(100%) contrast(92%) !important; }
            .ambient-glow { display: none !important; }
            .bg-blue-50, .bg-purple-50, .bg-emerald-50, .bg-indigo-50, .bg-amber-50 { background-color: #f1f5f9 !important; }
            .text-blue-700, .text-purple-700, .text-emerald-700, .text-indigo-700, .text-amber-600, .text-emerald-600 { color: #334155 !important; }
            .counter-stat { color: #1e293b !important; }
        @else
            .counter-stat { color: #0f172a !important; }
        @endif
    </style>
</head>
<body class="bg-slate-50 text-slate-800 text-base md:text-lg flex flex-col min-h-screen overflow-x-hidden antialiased selection:bg-custom-primary selection:text-white">

    <!-- HEADER FLOTANTE Y REDONDEADO -->
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-slate-200/80 transition-all shadow-xs">
        <div class="max-w-7xl w-full mx-auto px-5 sm:px-8 py-3.5 flex justify-between items-center">
            
            <!-- Logo -->
            <a href="#" class="flex items-center space-x-3.5 group" aria-label="Inicio SUIE CECyTE 13">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center p-1.5 border border-slate-200 shadow-xs group-hover:scale-105 group-hover:rotate-2 transition-all duration-300">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo Institución CECyTE 13" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="text-2xl font-black tracking-tight leading-none text-custom-primary">SUIE</h1>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-1">CECyTE 13 Tepetitla</p>
                </div>
            </a>

            <!-- Navegación Escritorio -->
            <div class="flex items-center space-x-4 sm:space-x-6">
                <nav class="hidden md:flex items-center space-x-2 font-bold text-sm text-slate-700">
                    <a href="#oferta" class="px-4 py-2.5 rounded-xl hover:text-custom-primary hover:bg-slate-100 transition-all">Carreras</a>
                    <a href="#comunidad" class="px-4 py-2.5 rounded-xl hover:text-custom-primary hover:bg-slate-100 transition-all">Acceso Rápido</a>
                    <a href="#areas" class="px-4 py-2.5 rounded-xl hover:text-custom-primary hover:bg-slate-100 transition-all">Servicios</a>
                </nav>
                
                @if(auth()->check())
                    <a href="{{ route('login') }}" class="px-6 py-3 bg-custom-primary hover:bg-custom-primary-hover text-white text-sm font-extrabold rounded-2xl shadow-sm hover:shadow-md transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                        <span class="material-icons-round text-lg">dashboard</span>
                        <span>Mi Panel</span>
                    </a>
                @else
                    <button type="button" onclick="toggleLoginModal()" class="px-6 py-3 bg-custom-primary hover:bg-custom-primary-hover text-white text-sm font-extrabold rounded-2xl shadow-sm hover:shadow-md hover:scale-105 transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                        <span class="material-icons-round text-lg">lock</span>
                        <span>Ingresar</span>
                    </button>
                @endif

                <!-- Botón Menú Móvil -->
                <button type="button" onclick="toggleMobileMenu()" class="md:hidden p-2.5 rounded-xl text-slate-700 hover:bg-slate-100 transition-colors" aria-label="Abrir menú de navegación">
                    <span class="material-icons-round text-2xl">menu</span>
                </button>
            </div>
        </div>

        <!-- Menú Móvil Desplegable -->
        <div id="mobileMenu" class="hidden md:hidden border-t border-slate-200 bg-white px-6 py-4 space-y-2 font-bold text-base text-slate-700">
            <a href="#oferta" onclick="toggleMobileMenu()" class="block px-4 py-3 rounded-xl hover:bg-slate-100 hover:text-custom-primary">Carreras</a>
            <a href="#comunidad" onclick="toggleMobileMenu()" class="block px-4 py-3 rounded-xl hover:bg-slate-100 hover:text-custom-primary">Acceso Rápido</a>
            <a href="#areas" onclick="toggleMobileMenu()" class="block px-4 py-3 rounded-xl hover:bg-slate-100 hover:text-custom-primary">Servicios</a>
        </div>
    </header>

    <!-- BANNER DE VEDA ELECTORAL -->
    @if(!empty($mostrarAvisoVeda))
        <aside class="bg-slate-800 text-slate-100 border-b border-slate-700 py-3 px-5 text-sm sm:text-base">
            <div class="max-w-7xl mx-auto flex items-center justify-center gap-2 text-center font-semibold">
                <span class="material-icons-round text-xl text-amber-400 shrink-0">gavel</span>
                <p><strong>Aviso:</strong> En cumplimiento a las normas oficiales en materia electoral, el sistema modificará su contenido temporalmente.</p>
            </div>
        </aside>
    @endif

    <!-- HERO SECTION ILUSTRADA Y ANIMADA -->
    <section class="relative bg-white border-b border-slate-200/80 overflow-hidden py-16 sm:py-24">
        <div class="ambient-glow absolute -top-20 left-1/4 w-96 h-96 rounded-full bg-custom-primary/10 blur-3xl pointer-events-none animate-pulse"></div>
        <div class="ambient-glow absolute -bottom-20 right-1/4 w-96 h-96 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl w-full mx-auto px-5 sm:px-8 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center relative z-10">
            
            <!-- Mensaje Central -->
            <div class="lg:col-span-6 space-y-6 text-center lg:text-left">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-custom-light text-custom-primary text-xs sm:text-sm font-black rounded-full border border-custom-primary/20 shadow-2xs">
                    <span class="material-icons-round text-base">rocket_launch</span>
                    <span>#OrgullosamenteCECyTE13</span>
                </span>
                
                <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-tight">
                    ¡Todo tu mundo escolar <span class="text-custom-primary underline decoration-wavy decoration-custom-primary/40 underline-offset-8">conectado</span>!
                </h2>
                
                <p class="text-slate-600 text-base sm:text-xl max-w-xl mx-auto lg:mx-0 leading-relaxed font-medium">
                    Revisa tus asistencias, calificaciones y trámites de titulación desde tu computadora o celular sin complicaciones.
                </p>
                
                <div class="pt-3 flex flex-wrap justify-center lg:justify-start gap-4">
                    @if(auth()->check())
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold text-base rounded-2xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all cursor-pointer flex items-center gap-2">
                            <span>Ir a mi Perfil</span>
                            <span class="material-icons-round text-xl">arrow_forward</span>
                        </a>
                    @else
                        <button type="button" onclick="toggleLoginModal()" class="px-8 py-4 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold text-base rounded-2xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all cursor-pointer flex items-center gap-2">
                            <span>Entrar a mi Cuenta</span>
                            <span class="material-icons-round text-xl">login</span>
                        </button>
                    @endif
                    <a href="#oferta" class="px-8 py-4 border-2 border-slate-300 bg-slate-50 text-slate-800 font-extrabold text-base rounded-2xl hover:bg-slate-100 hover:border-slate-400 transition-all flex items-center justify-center gap-2">
                        <span class="material-icons-round text-xl text-slate-500">explore</span>
                        <span>Ver Oferta</span>
                    </a>
                </div>
            </div>

            <!-- Carrusel Dinámico -->
            <div class="lg:col-span-6 w-full max-w-xl mx-auto">
                <div class="relative bg-slate-900 border-4 border-white rounded-3xl overflow-hidden shadow-2xl aspect-4/3 w-full group">
                    <div id="carousel-slides" class="w-full h-full relative">
                        <div class="carousel-item absolute inset-0 opacity-100 transition-opacity duration-700 ease-in-out">
                            <img src="/assets/images/aula.jpeg" alt="Aulas Tecnológicas del CECyTE 13" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent p-6 sm:p-8 flex flex-col justify-end">
                                <span class="bg-custom-primary text-white text-xs font-black uppercase tracking-wider px-3 py-1 rounded-md w-fit mb-2">Plantel Moderno</span>
                                <h3 class="text-white text-lg sm:text-xl font-extrabold">Instalaciones de primer nivel para tus prácticas.</h3>
                            </div>
                        </div>
                        <div class="carousel-item absolute inset-0 opacity-0 transition-opacity duration-700 ease-in-out">
                            <img src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?q=80&w=800" alt="Alumnos en Práctica" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent p-6 sm:p-8 flex flex-col justify-end">
                                <span class="bg-indigo-600 text-white text-xs font-black uppercase tracking-wider px-3 py-1 rounded-md w-fit mb-2">Acreditación Oficial</span>
                                <h3 class="text-white text-lg sm:text-xl font-extrabold">Obtén tu Título y Cédula Profesional al egresar.</h3>
                            </div>
                        </div>
                        <div class="carousel-item absolute inset-0 opacity-0 transition-opacity duration-700 ease-in-out">
                            <img src="https://images.unsplash.com/photo-1544535830-9dff9e0d4bec?q=80&w=800" alt="Acompañamiento Docente" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent p-6 sm:p-8 flex flex-col justify-end">
                                <span class="bg-emerald-600 text-white text-xs font-black uppercase tracking-wider px-3 py-1 rounded-md w-fit mb-2">Comunidad Activa</span>
                                <h3 class="text-white text-lg sm:text-xl font-extrabold">Profesores capacitados para guiar tu proyecto de vida.</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Controles flotantes -->
                    <button onclick="prevSlide()" aria-label="Imagen anterior" class="absolute left-3 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/95 hover:bg-white text-slate-900 flex items-center justify-center opacity-90 sm:opacity-0 group-hover:opacity-100 transition-opacity shadow-lg cursor-pointer">
                        <span class="material-icons-round text-2xl">chevron_left</span>
                    </button>
                    <button onclick="nextSlide()" aria-label="Siguiente imagen" class="absolute right-3 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/95 hover:bg-white text-slate-900 flex items-center justify-center opacity-90 sm:opacity-0 group-hover:opacity-100 transition-opacity shadow-lg cursor-pointer">
                        <span class="material-icons-round text-2xl">chevron_right</span>
                    </button>

                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2 z-20" id="carousel-indicators">
                        <button aria-label="Ir a diapositiva 1" class="w-3 h-3 rounded-full bg-white transition-all cursor-pointer opacity-100" onclick="goToSlide(0)"></button>
                        <button aria-label="Ir a diapositiva 2" class="w-3 h-3 rounded-full bg-white transition-all cursor-pointer opacity-40" onclick="goToSlide(1)"></button>
                        <button aria-label="Ir a diapositiva 3" class="w-3 h-3 rounded-full bg-white transition-all cursor-pointer opacity-40" onclick="goToSlide(2)"></button>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ESTADÍSTICAS RÁPIDAS -->
    <section id="stats-section" class="py-12 px-5 sm:px-8 bg-slate-100/80 border-b border-slate-200">
        <div class="max-w-7xl w-full mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            
            <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center gap-5 hover:-translate-y-1 transition-transform">
                <div class="w-14 h-14 bg-custom-light text-custom-primary rounded-2xl flex items-center justify-center shrink-0 border border-custom-primary/10">
                    <span class="material-icons-round text-3xl">verified</span>
                </div>
                <div>
                    <p class="text-3xl sm:text-4xl font-black counter-stat"><span class="counter" data-target="100">0</span>%</p>
                    <p class="text-xs sm:text-sm font-extrabold text-slate-500 uppercase tracking-wide mt-0.5">Oficial y Validado</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center gap-5 hover:-translate-y-1 transition-transform">
                <div class="w-14 h-14 bg-indigo-50 text-indigo-700 rounded-2xl flex items-center justify-center shrink-0 border border-indigo-100">
                    <span class="material-icons-round text-3xl">groups</span>
                </div>
                <div>
                    <p class="text-3xl sm:text-4xl font-black counter-stat">+<span class="counter" data-target="200">0</span></p>
                    <p class="text-xs sm:text-sm font-extrabold text-slate-500 uppercase tracking-wide mt-0.5">Estudiantes Activos</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center gap-5 hover:-translate-y-1 transition-transform">
                <div class="w-14 h-14 bg-amber-50 text-amber-700 rounded-2xl flex items-center justify-center shrink-0 border border-amber-100">
                    <span class="material-icons-round text-3xl">stars</span>
                </div>
                <div>
                    <p class="text-3xl sm:text-4xl font-black counter-stat"><span class="counter" data-target="2">0</span></p>
                    <p class="text-xs sm:text-sm font-extrabold text-slate-500 uppercase tracking-wide mt-0.5">Carreras Técnicas</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center gap-5 hover:-translate-y-1 transition-transform">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-700 rounded-2xl flex items-center justify-center shrink-0 border border-emerald-100">
                    <span class="material-icons-round text-3xl">school</span>
                </div>
                <div>
                    <p class="text-3xl sm:text-4xl font-black counter-stat">+<span class="counter" data-target="10">0</span></p>
                    <p class="text-xs sm:text-sm font-extrabold text-slate-500 uppercase tracking-wide mt-0.5">Docentes Calificados</p>
                </div>
            </div>

        </div>
    </section>

    <!-- OFERTA EDUCATIVA -->
    <section id="oferta" class="py-20 px-5 sm:px-8 max-w-7xl w-full mx-auto space-y-12">
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <span class="text-xs sm:text-sm font-black text-custom-primary uppercase tracking-widest bg-custom-light px-4 py-1.5 rounded-full border border-custom-primary/20">
                Tu Futuro Profesional
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                Carreras Técnicas Especializadas
            </h2>
            <p class="text-slate-600 text-base sm:text-lg font-medium">
                Estudia tu bachillerato y obtén un título profesional para insertarte en el mercado laboral o continuar tus estudios universitarios.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            
            <!-- Animación Digital -->
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-36 h-36 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-custom-primary/20 transition-colors"></div>
                <div class="space-y-5 relative z-10">
                    <div class="w-16 h-16 bg-indigo-50 text-indigo-700 rounded-2xl flex items-center justify-center border border-indigo-100 group-hover:bg-custom-primary group-hover:text-white transition-colors duration-300 shadow-2xs">
                        <span class="material-icons-round text-4xl">animation</span>
                    </div>
                    <div>
                        <span class="text-xs font-black text-indigo-700 uppercase tracking-wider">Creatividad y Tecnología</span>
                        <h3 class="font-black text-slate-900 text-2xl tracking-tight mt-1">
                            Técnico en Animación Digital
                        </h3>
                    </div>
                    <p class="text-slate-600 text-base leading-relaxed font-medium">
                        Crea gráficos en 3D, ilustraciones digitales, animación interactiva y edición de video avanzado utilizando software de estándar industrial.
                    </p>
                </div>
                <div class="pt-6 mt-6 border-t border-slate-100 flex items-center justify-between text-sm font-bold text-slate-600 relative z-10">
                    <span class="inline-flex items-center gap-1.5"><span class="material-icons-round text-lg text-slate-400">schedule</span> 6 Semestres</span>
                    <span class="text-custom-primary group-hover:translate-x-1 transition-transform flex items-center gap-1 font-black text-base">Descubrir carrera <span class="material-icons-round text-lg">arrow_forward</span></span>
                </div>
            </div>

            <!-- Química Industrial -->
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-36 h-36 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-custom-primary/20 transition-colors"></div>
                <div class="space-y-5 relative z-10">
                    <div class="w-16 h-16 bg-emerald-50 text-emerald-700 rounded-2xl flex items-center justify-center border border-emerald-100 group-hover:bg-custom-primary group-hover:text-white transition-colors duration-300 shadow-2xs">
                        <span class="material-icons-round text-4xl">science</span>
                    </div>
                    <div>
                        <span class="text-xs font-black text-emerald-700 uppercase tracking-wider">Ciencia y Procesos</span>
                        <h3 class="font-black text-slate-900 text-2xl tracking-tight mt-1">
                            Técnico en Química Industrial
                        </h3>
                    </div>
                    <p class="text-slate-600 text-base leading-relaxed font-medium">
                        Especialízate en análisis laboratoriales, control de calidad en líneas de producción, normatividad ambiental e innovación química.
                    </p>
                </div>
                <div class="pt-6 mt-6 border-t border-slate-100 flex items-center justify-between text-sm font-bold text-slate-600 relative z-10">
                    <span class="inline-flex items-center gap-1.5"><span class="material-icons-round text-lg text-slate-400">schedule</span> 6 Semestres</span>
                    <span class="text-custom-primary group-hover:translate-x-1 transition-transform flex items-center gap-1 font-black text-base">Descubrir carrera <span class="material-icons-round text-lg">arrow_forward</span></span>
                </div>
            </div>

        </div>
    </section>

    <!-- ACCESOS POR PERFIL -->
    <section id="comunidad" class="bg-slate-100/90 py-20 px-5 sm:px-8 border-y border-slate-200">
        <div class="max-w-7xl w-full mx-auto space-y-12">
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs sm:text-sm font-black text-custom-primary uppercase tracking-widest">Plataforma Digital</span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900">¿Cómo deseas ingresar hoy?</h2>
                <p class="text-slate-600 text-base sm:text-lg font-medium">Selecciona tu perfil institucional para acceder a tus herramientas específicas.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Estudiantes -->
                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-2xs hover:shadow-xl transition-all space-y-6 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-700 border border-indigo-100 flex items-center justify-center">
                            <span class="material-icons-round text-3xl">face</span>
                        </div>
                        <h3 class="font-black text-xl sm:text-2xl text-slate-900">Alumnos</h3>
                        <p class="text-sm sm:text-base text-slate-600 leading-relaxed font-medium">
                            Consulta tus materias, horarios, porcentajes de asistencia y el estatus de tus trámites académicos.
                        </p>
                    </div>
                    <button type="button" onclick="toggleLoginModal()" class="w-full py-4 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-sm font-extrabold rounded-2xl transition-colors cursor-pointer flex items-center justify-center gap-2">
                        <span class="material-icons-round text-xl">login</span>
                        <span>Ingresar como Alumno</span>
                    </button>
                </div>

                <!-- Docentes -->
                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-2xs hover:shadow-xl transition-all space-y-6 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center">
                            <span class="material-icons-round text-3xl">co_present</span>
                        </div>
                        <h3 class="font-black text-xl sm:text-2xl text-slate-900">Docentes</h3>
                        <p class="text-sm sm:text-base text-slate-600 leading-relaxed font-medium">
                            Pase de lista digital, registro de evaluaciones parciales y seguimiento a proyectos de residencia.
                        </p>
                    </div>
                    <button type="button" onclick="toggleLoginModal()" class="w-full py-4 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-sm font-extrabold rounded-2xl transition-colors cursor-pointer flex items-center justify-center gap-2">
                        <span class="material-icons-round text-xl">login</span>
                        <span>Ingresar como Docente</span>
                    </button>
                </div>

                <!-- Administración -->
                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-2xs hover:shadow-xl transition-all space-y-6 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-custom-light text-custom-primary border border-custom-primary/20 flex items-center justify-center">
                            <span class="material-icons-round text-3xl">admin_panel_settings</span>
                        </div>
                        <h3 class="font-black text-xl sm:text-2xl text-slate-900">Coordinación</h3>
                        <p class="text-sm sm:text-base text-slate-600 leading-relaxed font-medium">
                            Gestión de matrículas, cargas docentes, emisión de documentos oficiales y control escolar global.
                        </p>
                    </div>
                    <button type="button" onclick="toggleLoginModal()" class="w-full py-4 bg-custom-primary hover:bg-custom-primary-hover text-white text-sm font-extrabold rounded-2xl transition-colors cursor-pointer shadow-sm flex items-center justify-center gap-2">
                        <span class="material-icons-round text-xl">security</span>
                        <span>Ingreso Administrativo</span>
                    </button>
                </div>

            </div>
        </div>
    </section>

    <!-- MÓDULOS DE SERVICIO -->
    <section id="areas" class="py-20 px-5 sm:px-8 max-w-7xl w-full mx-auto space-y-12">
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <span class="text-xs sm:text-sm font-black text-custom-primary uppercase tracking-widest">Atención Escolar</span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900">Módulos de Apoyo Institucional</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-7 rounded-2xl border border-slate-200 shadow-2xs flex items-start gap-5">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 border border-amber-100">
                    <span class="material-icons-round text-2xl">gavel</span>
                </div>
                <div>
                    <h3 class="font-extrabold text-base sm:text-lg text-slate-900">Academia y Sínodos</h3>
                    <p class="text-sm text-slate-600 mt-1 font-medium leading-relaxed">Asignación de asesores y comités revisores para el proceso de titulación profesional.</p>
                </div>
            </div>

            <div class="bg-white p-7 rounded-2xl border border-slate-200 shadow-2xs flex items-start gap-5">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-700 flex items-center justify-center shrink-0 border border-rose-100">
                    <span class="material-icons-round text-2xl">assignment_ind</span>
                </div>
                <div>
                    <h3 class="font-extrabold text-base sm:text-lg text-slate-900">Orientación Educativa</h3>
                    <p class="text-sm text-slate-600 mt-1 font-medium leading-relaxed">Acompañamiento psicopedagógico, tutorías y monitoreo continuo de asistencia.</p>
                </div>
            </div>

            <div class="bg-white p-7 rounded-2xl border border-slate-200 shadow-2xs flex items-start gap-5">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 border border-blue-100">
                    <span class="material-icons-round text-2xl">analytics</span>
                </div>
                <div>
                    <h3 class="font-extrabold text-base sm:text-lg text-slate-900">Control Escolar</h3>
                    <p class="text-sm text-slate-600 mt-1 font-medium leading-relaxed">Emisión de credenciales, kardex, kárdex analítico y validación de pagos institucionales.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-slate-900 text-slate-300 text-sm py-10 px-5 sm:px-8 mt-auto border-t border-slate-800">
        <div class="max-w-7xl w-full mx-auto flex flex-col md:flex-row justify-between items-center gap-6 text-center md:text-left">
            <div class="flex items-center gap-3">
                <span class="font-black text-white text-lg tracking-wider">SUIE</span>
                <span class="text-slate-400">— CECyTE Plantel 13 Tepetitla</span>
            </div>
            <p class="text-slate-400">© 2026 Todos los derechos reservados. Sistema Unificado de Integración Educativa.</p>
        </div>
    </footer>

    <!-- MODAL DE LOGIN -->
    <div id="loginModal" 
         class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 transition-all duration-300 {{ $errors->has('username') ? 'opacity-100' : 'opacity-0 pointer-events-none' }}"
         aria-hidden="true" role="dialog">
        
        <div class="bg-white w-full max-w-lg rounded-3xl p-6 sm:p-10 shadow-2xl space-y-6 transform transition-all duration-300 ease-out border border-slate-100 {{ $errors->has('username') ? 'scale-100' : 'scale-95' }}" id="loginBox">
            
            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-black text-2xl text-slate-900">¡Bienvenido de nuevo! 👋</h3>
                    <p class="text-sm text-slate-600 mt-1">Ingresa tu credencial institucional para continuar</p>
                </div>
                <button type="button" onclick="toggleLoginModal()" aria-label="Cerrar ventana emergente" class="p-2 text-slate-400 hover:text-slate-700 rounded-xl transition-colors cursor-pointer">
                    <span class="material-icons-round text-2xl">close</span>
                </button>
            </div>

            @if(!empty($mostrarAvisoVeda))
                <div class="p-4 rounded-2xl bg-slate-100 border border-slate-300 text-slate-800 text-sm flex items-start gap-3">
                    <span class="material-icons-round text-lg text-slate-600 shrink-0">gavel</span>
                    <p class="leading-relaxed font-medium">Contenidos ajustados temporalmente para el cumplimiento del marco legal electoral.</p>
                </div>
            @endif

            @error('username')
                <div class="bg-rose-50 border border-rose-200 text-rose-900 p-4 rounded-2xl text-sm flex items-center gap-3 font-semibold">
                    <span class="material-icons-round text-xl shrink-0 text-rose-700">error</span>
                    <p>{{ $message }}</p>
                </div>
            @enderror

            <form class="space-y-5 text-sm sm:text-base" action="{{ route('login') }}" method="POST">
                @csrf
                <div>
                    <label for="username_input" class="block font-extrabold text-slate-800 mb-2">Usuario / Matrícula</label>
                    <input type="text" id="username_input" name="username" value="{{ old('username') }}" required 
                           class="w-full bg-slate-50 text-slate-900 border-2 border-slate-300 focus:ring-4 focus:ring-custom-primary/20 focus:border-custom-primary rounded-2xl p-4 font-medium transition-all placeholder:text-slate-400" 
                           placeholder="Ej: 22240105 o DOC-102">
                </div>
                <div>
                    <label for="password_input" class="block font-extrabold text-slate-800 mb-2">Contraseña</label>
                    <input type="password" id="password_input" name="password" required 
                           class="w-full bg-slate-50 text-slate-900 border-2 border-slate-300 focus:ring-4 focus:ring-custom-primary/20 focus:border-custom-primary rounded-2xl p-4 font-medium transition-all placeholder:text-slate-400" 
                           placeholder="••••••••">
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full py-4 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold text-base rounded-2xl shadow-md transition-all cursor-pointer flex items-center justify-center gap-2 active:scale-98">
                        <span class="material-icons-round text-xl">login</span>
                        <span>Ingresar al Sistema</span>
                    </button>
                </div>
            </form>

            <div class="relative flex py-2 items-center">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="shrink mx-4 text-xs font-bold text-slate-400 uppercase tracking-wider">o accesos integrados</span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>
            
            <a href="{{ route('auth.google') }}" 
               class="w-full py-4 px-4 bg-white hover:bg-slate-50 text-slate-800 font-extrabold text-sm sm:text-base rounded-2xl border-2 border-slate-300 shadow-2xs hover:shadow-xs transition-all flex items-center justify-center gap-3 cursor-pointer">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17z"/>
                    <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.34 24 12 24z"/>
                    <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.99 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                    <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.34 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                </svg>
                <span>Continuar con Google</span>
            </a>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
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

        function toggleLoginModal() {
            const modal = document.getElementById('loginModal');
            const box = document.getElementById('loginBox');
            
            if (modal.classList.contains('pointer-events-none') || modal.classList.contains('opacity-0')) {
                modal.classList.remove('pointer-events-none', 'opacity-0');
                modal.classList.add('opacity-100');
                box.classList.remove('scale-95');
                box.classList.add('scale-100');
                modal.setAttribute('aria-hidden', 'false');
            } else {
                modal.classList.remove('opacity-100');
                modal.classList.add('pointer-events-none', 'opacity-0');
                box.classList.remove('scale-100');
                box.classList.add('scale-95');
                modal.setAttribute('aria-hidden', 'true');
            }
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

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
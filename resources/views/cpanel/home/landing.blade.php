<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUIE - Plataforma de Gestión Administrativa</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
</head>
<body class="bg-slate-50 font-sans text-slate-800 flex flex-col min-h-screen overflow-x-hidden">

    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 px-6 py-3 shadow-xs">
        <div class="max-w-7xl w-full mx-auto flex justify-between items-center">
            
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center p-1 border border-slate-200 overflow-hidden shrink-0">
                    <img src="/assets/images/logo.png" alt="Logo Institución" class="w-full h-full object-contain">
                </div>
                <div class="border-l border-slate-300 pl-4">
                    <h1 class="text-xl font-black tracking-wider leading-none text-[#841B44]">SUIE</h1>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">cecyte 13 tepetitla</p>
                </div>
            </div>

            <div>
                <button onclick="toggleLoginModal()" class="px-5 py-2.5 bg-[#841B44] hover:bg-[#6b1536] text-white text-xs font-bold rounded-xl shadow-xs transition-all flex items-center gap-2 cursor-pointer">
                    <span class="material-icons-round text-sm">lock</span> Acceso Administrativo
                </button>
            </div>
        </div>
    </header>

    <section class="relative bg-white py-16 md:py-24 px-6 border-b border-slate-200 overflow-hidden">
        <div class="max-w-6xl w-full mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <span class="inline-flex items-center px-3 py-1 bg-emerald-50 border border-emerald-200 text-[#0F7F41] text-xs font-bold rounded-full">
                    <span class="material-icons-round text-xs mr-1">security</span> Entorno Operativo Seguro
                </span>
                <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    Optimización y control operativo de nuestro <span class="text-[#841B44]">plantel escolar</span>.
                </h2>
                <p class="text-slate-500 text-sm md:text-base max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    SUIE conecta los procesos operativos clave del cuerpo directivo, la inspección matricular de control escolar y las métricas preventivas de orientación educativa en una matriz digital unificada.
                </p>
                <div class="pt-2 flex flex-wrap justify-center lg:justify-start gap-3">
                    <button onclick="toggleLoginModal()" class="px-6 py-3 bg-[#841B44] hover:bg-[#6b1536] text-white font-bold text-xs rounded-xl shadow-md transition-all cursor-pointer">
                        Ingresar al Sistema
                    </button>
                    <a href="#areas" class="px-6 py-3 border border-slate-300 text-slate-600 font-semibold text-xs rounded-xl hover:bg-slate-50 transition-all flex items-center justify-center">
                        Ver Módulos de Operación
                    </a>
                </div>
            </div>

            <div class="lg:col-span-5 flex justify-center relative">
                <div class="absolute -inset-4 bg-slate-100 rounded-3xl -rotate-2 scale-95 opacity-50"></div>
                <div class="relative bg-slate-50 border border-slate-200 rounded-3xl p-6 shadow-xl w-full max-w-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                        <div class="flex items-center space-x-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
                        </div>
                        <span class="text-[9px] bg-slate-200 px-2 py-0.5 rounded-xs font-mono text-slate-400">suie.admin.mx</span>
                    </div>
                    <div class="h-4 bg-slate-200 rounded-md w-1/2"></div>
                    <div class="space-y-2">
                        <div class="h-8 bg-emerald-50 border-l-4 border-[#0F7F41] rounded-r-md flex items-center px-2">
                            <div class="h-2 bg-[#0F7F41]/30 rounded-sm w-3/4"></div>
                        </div>
                        <div class="h-8 bg-amber-50 border-l-4 border-[#E66711] rounded-r-md flex items-center px-2">
                            <div class="h-2 bg-[#E66711]/30 rounded-sm w-2/3"></div>
                        </div>
                        <div class="h-8 bg-red-50 border-l-4 border-[#841B44] rounded-r-md flex items-center px-2">
                            <div class="h-2 bg-[#841B44]/30 rounded-sm w-1/2"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section id="areas" class="py-16 px-6 max-w-6xl w-full mx-auto space-y-12">
        <div class="text-center max-w-2xl mx-auto space-y-2">
            <h3 class="text-xs font-bold text-[#841B44] uppercase tracking-widest">Matriz de Trabajo</h3>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900">Módulos Administrativos Centrales</h2>
            <p class="text-slate-500 text-xs">Administración y seguimiento coordinado para la mejora de indicadores del plantel.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-3xs space-y-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-[#E66711] border border-amber-100 flex items-center justify-center">
                    <span class="material-icons-round">gavel</span>
                </div>
                <h4 class="font-bold text-sm text-slate-900">Coordinación Institucional</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Supervisión global de la carga docente, planeaciones académicas, asignación de aulas e inspección de indicadores de eficiencia terminal del ciclo activo.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-3xs space-y-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#0F7F41] border border-emerald-100 flex items-center justify-center">
                    <span class="material-icons-round">assignment_ind</span>
                </div>
                <h4 class="font-bold text-sm text-slate-900">Orientación Educativa</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Monitoreo analítico de inasistencias en tiempo real, gestión de bitácoras de conducta y envío de alertas automatizadas a tutores para mitigar la deserción.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-3xs space-y-4">
                <div class="w-10 h-10 rounded-xl bg-[#841B44]/10 text-[#841B44] flex items-center justify-center">
                    <span class="material-icons-round">analytics</span>
                </div>
                <h4 class="font-bold text-sm text-slate-900">Control Escolar</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Validación física y digital de expedientes de titulación, emisión de actas oficiales de calificaciones, constancias y administración matricular integral.
                </p>
            </div>

        </div>
    </section>

    <footer class="bg-slate-900 text-slate-400 text-xs py-8 px-6 mt-auto border-t border-slate-800">
        <div class="max-w-6xl w-full mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
            <p>© 2026 SUIE. Sistema Unificado de Integración Educativa. Todos los derechos reservados.</p>
            <p class="text-[11px] text-slate-500">Módulos de Gestión Técnica y Administrativa de Media Superior.</p>
        </div>
    </footer>

    <div id="loginModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 transition-opacity opacity-0 pointer-events-none duration-300">
        <div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-2xl space-y-6 transform scale-95 transition-transform duration-300 ease-out" id="loginBox">
            
            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-black text-lg text-slate-900">Ingreso Administrativo</h3>
                    <p class="text-xs text-slate-400">Introduce tus credenciales del personal autorizado</p>
                </div>
                <button onclick="toggleLoginModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg transition-colors cursor-pointer">
                    <span class="material-icons-round">close</span>
                </button>
            </div>

            <!-- Formulario Modificado (Sin el select de Departamento / Rol) -->
            <form class="space-y-4 text-xs" action="/login" method="POST">
                <!-- Token de seguridad obligatorio en Laravel -->
                <input type="hidden" name="_token" value="VALUE_DEL_TOKEN_CSRF"> 

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Clave de Empleado o Usuario</label>
                    <input type="text" name="username" required class="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden" placeholder="Ej: ADM-2405">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Contraseña</label>
                    <input type="password" name="password" required class="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden" placeholder="••••••••">
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 bg-[#841B44] hover:bg-[#6b1536] text-white font-bold text-xs rounded-xl shadow-xs transition-colors cursor-pointer">
                        Ingresar de Forma Segura
                    </button>
                </div>
            </form>

            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-[10px] text-slate-400 leading-normal flex items-start gap-2">
                <span class="material-icons-round text-sm text-[#0F7F41]">verified_user</span>
                <p><strong>Aviso de seguridad:</strong> El acceso está restringido a personal del plantel. Los intentos fallidos o no autorizados serán registrados en la bitácora de auditoría del servidor.</p>
            </div>

        </div>
    </div>

    <script>
        function toggleLoginModal() {
            const modal = document.getElementById('loginModal');
            const box = document.getElementById('loginBox');
            
            if (modal.classList.contains('pointer-events-none')) {
                // Mostrar modal
                modal.classList.remove('pointer-events-none', 'opacity-0');
                box.classList.remove('scale-95');
                box.classList.add('scale-100');
            } else {
                // Ocultar modal
                modal.classList.add('pointer-events-none', 'opacity-0');
                box.classList.remove('scale-100');
                box.classList.add('scale-95');
            }
        }
    </script>

</body>
</html>
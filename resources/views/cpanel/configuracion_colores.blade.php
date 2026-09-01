@extends('cpanel/plantillaadmin')
@section('title', 'Configuración de Colores y Veda Electoral')

@section('content')
<main class="flex-1 max-w-6xl w-full mx-auto p-6 md:p-8 space-y-8 text-sm md:text-base">

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 p-4 md:p-5 rounded-2xl flex items-center justify-between shadow-xs font-semibold">
            <div class="flex items-center gap-3">
                <span class="material-icons-round text-2xl text-emerald-600 dark:text-emerald-400">check_circle</span>
                <p>{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 cursor-pointer">
                <span class="material-icons-round text-lg">close</span>
            </button>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-slate-100 flex items-center gap-3">
                <span class="material-icons-round text-2xl md:text-3xl text-custom-primary">palette</span>
                Colores del Sistema y Veda Electoral
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm mt-1">
                Ajusta la paleta global o activa la modalidad neutral con aviso legal electoral en los accesos del sistema.
            </p>
        </div>

        <!-- ATAJO RÁPIDO VEDA ELECTORAL -->
        <button type="button" onclick="activarModoVedaElectoral()"
                class="px-5 py-3.5 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-extrabold text-xs md:text-sm rounded-2xl shadow-sm transition-all cursor-pointer flex items-center gap-2 shrink-0">
            <span class="material-icons-round text-base text-amber-400">how_to_vote</span>
            Atajo: Modo Neutro / Veda Electoral
        </button>
    </div>

    <form action="{{ route('admin.colores.store') }}" method="POST" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Paletas Rápidas -->
                <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                    <h3 class="text-base md:text-lg font-black text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        <span class="material-icons-round text-lg text-custom-primary">color_lens</span> Paletas Prediseñadas
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Guinda Institucional -->
                        <button type="button" onclick="cargarPaleta('#841B44', '#681535', '#fdf2f4', false)"
                                class="p-4 rounded-2xl border-2 border-slate-200 dark:border-slate-700 hover:border-[#841B44] text-left space-y-3 cursor-pointer bg-slate-50/50 dark:bg-slate-800/30 transition-all">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-800 dark:text-slate-200 text-xs md:text-sm">Guinda Institucional</span>
                                <span class="w-4 h-4 rounded-full bg-[#841B44]"></span>
                            </div>
                            <div class="flex gap-2">
                                <div class="h-6 flex-1 rounded-lg bg-[#841B44]"></div>
                                <div class="h-6 flex-1 rounded-lg bg-[#681535]"></div>
                            </div>
                        </button>

                        <!-- Neutral / Veda Electoral -->
                        <button type="button" onclick="cargarPaleta('#475569', '#334155', '#f1f5f9', true)"
                                class="p-4 rounded-2xl border-2 border-amber-300 dark:border-amber-700 hover:border-slate-600 text-left space-y-3 cursor-pointer bg-amber-50/40 dark:bg-slate-800/60 transition-all">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-800 dark:text-slate-200 text-xs md:text-sm">Neutro (Veda Electoral)</span>
                                <span class="w-4 h-4 rounded-full bg-[#475569]"></span>
                            </div>
                            <div class="flex gap-2">
                                <div class="h-6 flex-1 rounded-lg bg-[#475569]"></div>
                                <div class="h-6 flex-1 rounded-lg bg-[#334155]"></div>
                            </div>
                        </button>

                        <!-- Azul Marino CECyTE -->
                        <button type="button" onclick="cargarPaleta('#1E3A8A', '#172554', '#eff6ff', false)"
                                class="p-4 rounded-2xl border-2 border-slate-200 dark:border-slate-700 hover:border-blue-900 text-left space-y-3 cursor-pointer bg-slate-50/50 dark:bg-slate-800/30 transition-all">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-800 dark:text-slate-200 text-xs md:text-sm">Azul Marino Institucional</span>
                                <span class="w-4 h-4 rounded-full bg-[#1E3A8A]"></span>
                            </div>
                            <div class="flex gap-2">
                                <div class="h-6 flex-1 rounded-lg bg-[#1E3A8A]"></div>
                                <div class="h-6 flex-1 rounded-lg bg-[#172554]"></div>
                            </div>
                        </button>

                        <!-- Verde Tecnológico -->
                        <button type="button" onclick="cargarPaleta('#065F46', '#064E3B', '#ecfdf5', false)"
                                class="p-4 rounded-2xl border-2 border-slate-200 dark:border-slate-700 hover:border-emerald-800 text-left space-y-3 cursor-pointer bg-slate-50/50 dark:bg-slate-800/30 transition-all">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-800 dark:text-slate-200 text-xs md:text-sm">Verde Tecnológico</span>
                                <span class="w-4 h-4 rounded-full bg-[#065F46]"></span>
                            </div>
                            <div class="flex gap-2">
                                <div class="h-6 flex-1 rounded-lg bg-[#065F46]"></div>
                                <div class="h-6 flex-1 rounded-lg bg-[#064E3B]"></div>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Selección Manual & Control de Aviso Legal -->
                <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                    <h3 class="text-base md:text-lg font-black text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        <span class="material-icons-round text-lg text-custom-primary">tune</span> Ajuste Manual y Normativa Electoral
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-2 text-xs md:text-sm">Color Primario *</label>
                            <div class="flex items-center gap-3">
                                <input type="color" id="pickerPrimario" value="{{ $colorPrimario }}" class="w-12 h-12 rounded-xl border border-slate-300 dark:border-slate-700 cursor-pointer bg-transparent p-1">
                                <input type="text" name="color_primario" id="hexPrimario" value="{{ $colorPrimario }}" required
                                       class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3 font-mono font-bold text-slate-800 dark:text-slate-100 text-xs md:text-sm uppercase focus:ring-2 focus:ring-custom-primary">
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-2 text-xs md:text-sm">Color Hover (Oscuro) *</label>
                            <div class="flex items-center gap-3">
                                <input type="color" id="pickerHover" value="{{ $colorHover }}" class="w-12 h-12 rounded-xl border border-slate-300 dark:border-slate-700 cursor-pointer bg-transparent p-1">
                                <input type="text" name="color_hover" id="hexHover" value="{{ $colorHover }}" required
                                       class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3 font-mono font-bold text-slate-800 dark:text-slate-100 text-xs md:text-sm uppercase focus:ring-2 focus:ring-custom-primary">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="color_light" id="hexLight" value="{{ $colorLight }}">

                    <!-- Switch Leyenda de Veda Electoral -->
                    <div class="p-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-800/40 space-y-2">
                        <label class="flex items-center justify-between cursor-pointer">
                            <div class="space-y-0.5">
                                <span class="font-extrabold text-slate-900 dark:text-slate-100 text-xs md:text-sm flex items-center gap-2">
                                    <span class="material-icons-round text-base text-amber-500">gavel</span>
                                    Mostrar Aviso de Cumplimiento Electoral en Login
                                </span>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    Muestra el banner de ley federal y estatal en la pantalla de inicio de sesión.
                                </p>
                            </div>
                            <input type="checkbox" name="mostrar_aviso_veda" id="chkAvisoVeda" value="1" {{ ($mostrarAvisoVeda ?? false) ? 'checked' : '' }}
                                   class="w-5 h-5 text-custom-primary rounded-lg border-slate-300 dark:border-slate-700 focus:ring-custom-primary cursor-pointer">
                        </label>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit"
                                class="px-8 py-4 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold text-sm rounded-2xl shadow-md transition-all cursor-pointer flex items-center gap-2">
                            <span class="material-icons-round text-lg">save</span> Guardar y Aplicar Globalmente
                        </button>
                    </div>
                </div>

            </div>

            <!-- Vista Previa en Vivo -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6 sticky top-24">
                    <h3 class="text-base md:text-lg font-black text-slate-900 dark:text-slate-100 flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-4">
                        <span class="material-icons-round text-lg text-custom-primary">visibility</span> Vista Previa de Componentes
                    </h3>

                    <div class="space-y-4">
                        <button type="button" class="w-full py-3.5 bg-custom-primary text-white font-extrabold rounded-2xl text-sm shadow-sm flex items-center justify-center gap-2 cursor-default">
                            <span class="material-icons-round text-base">how_to_reg</span> Botón de Acción Principal
                        </button>

                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-200/80 dark:border-slate-700/80">
                            <span class="font-bold text-custom-primary text-sm flex items-center gap-1.5">
                                <span class="material-icons-round text-base">hub</span> Módulo Activo
                            </span>
                            <span class="px-3 py-1 bg-custom-primary text-white text-xs font-black rounded-full">
                                6° Semestre
                            </span>
                        </div>

                        <!-- Previsualización del Aviso Electoral -->
                        <div id="previewAvisoVeda" class="{{ ($mostrarAvisoVeda ?? false) ? '' : 'hidden' }} p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-900 dark:text-amber-200 text-xs space-y-1">
                            <div class="flex items-center gap-1.5 font-bold">
                                <span class="material-icons-round text-sm text-amber-600 dark:text-amber-400">info</span>
                                Aviso Legal de Veda Electoral
                            </div>
                            <p class="leading-relaxed">
                                Para poder cumplir con la normativa de la ley estatal y federal en materia electoral, este sistema modificará sus contenidos temporalmente.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</main>

<script>
    function cargarPaleta(primario, hover, light, activarAviso) {
        document.getElementById('pickerPrimario').value = primario;
        document.getElementById('hexPrimario').value = primario.toUpperCase();

        document.getElementById('pickerHover').value = hover;
        document.getElementById('hexHover').value = hover.toUpperCase();

        document.getElementById('hexLight').value = light;

        const chk = document.getElementById('chkAvisoVeda');
        const preview = document.getElementById('previewAvisoVeda');
        chk.checked = activarAviso;
        if (activarAviso) {
            preview.classList.remove('hidden');
        } else {
            preview.classList.add('hidden');
        }

        document.documentElement.style.setProperty('--color-primary', primario);
        document.documentElement.style.setProperty('--color-primary-hover', hover);
        document.documentElement.style.setProperty('--color-primary-light', light);
    }

    function activarModoVedaElectoral() {
        cargarPaleta('#475569', '#334155', '#f1f5f9', true);
    }

    document.getElementById('chkAvisoVeda').addEventListener('change', function(e) {
        const preview = document.getElementById('previewAvisoVeda');
        if (e.target.checked) {
            preview.classList.remove('hidden');
        } else {
            preview.classList.add('hidden');
        }
    });

    document.getElementById('pickerPrimario').addEventListener('input', function(e) {
        document.getElementById('hexPrimario').value = e.target.value.toUpperCase();
        document.documentElement.style.setProperty('--color-primary', e.target.value);
    });

    document.getElementById('pickerHover').addEventListener('input', function(e) {
        document.getElementById('hexHover').value = e.target.value.toUpperCase();
        document.documentElement.style.setProperty('--color-primary-hover', e.target.value);
    });
</script>
@endsection
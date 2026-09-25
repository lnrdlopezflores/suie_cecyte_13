@extends('cpanel.plantillafinazas')
@section('title', 'Registrar Cobro Directo')

@section('content')
<main class="flex-1 max-w-4xl w-full mx-auto p-4 md:p-8 space-y-6 text-xs transition-colors duration-200">

    <!-- CABECERA -->
    <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-3xs flex items-center justify-between transition-colors">
        <div>
            <div class="flex items-center gap-1.5 text-custom-primary text-[10px] font-black uppercase tracking-widest mb-0.5">
                <span class="material-icons-round text-base">point_of_sale</span>
                <span>Caja y Recaudación Escolar</span>
            </div>
            <h2 class="text-xl font-black text-slate-900 dark:text-slate-100">
                Recepción y Cobro Directo en Ventanilla
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-xs">
                Captura de aranceles institucionales, validación inmediata y emisión de doble talón sellado.
            </p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-custom-light text-custom-primary border border-custom-primary/30 flex items-center justify-center shrink-0">
            <span class="material-icons-round text-2xl">receipt</span>
        </div>
    </div>

    <!-- FORMULARIO DE COBRO -->
    <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs transition-colors">
        <form action="{{ route('contador.cobro-directo.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- 1. BÚSQUEDA Y SELECCIÓN DEL ALUMNO -->
            <div class="space-y-2 relative">
                <label class="block font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 text-[11px]">
                    Buscar Estudiante (Nombre o Matrícula) *
                </label>
                <div class="relative">
                    <input type="text" id="buscadorAlumno" autocomplete="off"
                           class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 pl-11 text-xs font-semibold focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all placeholder:text-slate-400"
                           placeholder="Escribe el apellido, nombre o matrícula del alumno...">
                    <span class="material-icons-round text-slate-400 text-lg absolute left-3.5 top-3.5">search</span>
                </div>

                <!-- Menú desplegable con sugerencias dinámicas -->
                <div id="listaResultados" class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-xl max-h-56 overflow-y-auto z-50 divide-y divide-slate-100 dark:divide-slate-700"></div>

                <!-- Input oculto para guardar el ID del alumno -->
                <input type="hidden" name="alumno_id" id="alumnoId" required>

                <!-- Tarjeta de Alumno Seleccionado -->
                <div id="cardAlumnoSeleccionado" class="hidden p-4 bg-emerald-50/80 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/80 rounded-2xl flex items-center justify-between mt-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white font-black flex items-center justify-center text-sm shadow-3xs">
                            <span class="material-icons-round text-lg">school</span>
                        </div>
                        <div>
                            <p class="font-black text-slate-900 dark:text-slate-100 text-xs" id="textNombreAlumno"></p>
                            <p class="text-[11px] text-emerald-700 dark:text-emerald-400 font-mono mt-0.5" id="textDetalleAlumno"></p>
                        </div>
                    </div>
                    <button type="button" onclick="deseleccionarAlumno()" class="p-1.5 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 text-emerald-800 dark:text-emerald-200 rounded-xl transition-colors cursor-pointer">
                        <span class="material-icons-round text-base">close</span>
                    </button>
                </div>
            </div>

            <!-- 2. DETALLES DEL COBRO -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <!-- Concepto -->
                <div class="md:col-span-2 space-y-1.5">
                    <label class="block font-bold text-slate-700 dark:text-slate-300">Concepto de Cobro *</label>
                    <input type="text" name="concepto" required list="conceptosSugeridos"
                           class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-3 font-semibold focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all"
                           placeholder="Ej: Reinscripción Semestral, Trámite de Constancia, Titulación">
                    <datalist id="conceptosSugeridos">
                        <option value="Reinscripción Semestral">
                        <option value="Constancia de Estudios con Calificaciones">
                        <option value="Examen Extraordinario de Regularización">
                        <option value="Trámite y Emisión de Título Profesional">
                        <option value="Reposición de Credencial Escolar">
                    </datalist>
                </div>

                <!-- Monto -->
                <div class="space-y-1.5">
                    <label class="block font-bold text-slate-700 dark:text-slate-300">Monto Recaudado ($ MXN) *</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-3 text-slate-400 font-black text-sm">$</span>
                        <input type="number" step="0.01" min="1" name="monto" required
                               class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-3 pl-8 font-mono font-black text-base focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all"
                               placeholder="0.00">
                    </div>
                </div>

                <!-- Método de Pago -->
                <div class="space-y-1.5">
                    <label class="block font-bold text-slate-700 dark:text-slate-300">Método de Liquidación *</label>
                    <select name="metodo_pago" required
                            class="w-full bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 rounded-xl p-3 font-semibold focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all cursor-pointer">
                        <option value="Efectivo" selected>Efectivo en Ventanilla</option>
                        <option value="Terminal Bancaria">Terminal Bancaria (TDC / TDD)</option>
                        <option value="Transferencia Inmediata">Transferencia Inmediata / SPEI</option>
                    </select>
                </div>

                <!-- Referencia o Folio Externo -->
                <div class="md:col-span-2 space-y-1.5">
                    <label class="block font-bold text-slate-700 dark:text-slate-300">Referencia de Transacción o Recibo (Opcional)</label>
                    <input type="text" name="referencia_operacion"
                           class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-3 font-mono text-xs focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all"
                           placeholder="Dejar en blanco para generar un folio automático">
                </div>

                <!-- Observaciones -->
                <div class="md:col-span-2 space-y-1.5">
                    <label class="block font-bold text-slate-700 dark:text-slate-300">Notas / Observaciones del Cajero (Opcional)</label>
                    <textarea name="observaciones" rows="2"
                              class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-3 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all"
                              placeholder="Ej: Pago realizado por el padre de familia en una sola exhibición."></textarea>
                </div>

            </div>

            <!-- BOTÓN DE CONFIRMACIÓN -->
            <button type="submit" id="btnSubmit" disabled
                    class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-black text-xs md:text-sm rounded-2xl shadow-sm transition-all cursor-pointer flex items-center justify-center gap-2">
                <span class="material-icons-round text-lg">check_circle</span>
                <span>Procesar Pago y Generar Comprobante Doble</span>
            </button>
        </form>
    </div>

</main>

<script>
    const inputBuscador = document.getElementById('buscadorAlumno');
    const listaResultados = document.getElementById('listaResultados');
    const inputAlumnoId = document.getElementById('alumnoId');
    const cardSeleccionado = document.getElementById('cardAlumnoSeleccionado');
    const textNombre = document.getElementById('textNombreAlumno');
    const textDetalle = document.getElementById('textDetalleAlumno');
    const btnSubmit = document.getElementById('btnSubmit');

    let debounceTimer;

    inputBuscador.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const query = this.value.trim();

        if (query.length < 2) {
            listaResultados.classList.add('hidden');
            listaResultados.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`{{ route('contador.cobro-directo.buscar') }}?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    listaResultados.innerHTML = '';
                    if (data.length === 0) {
                        listaResultados.innerHTML = `<div class="p-3 text-slate-400 text-center">No se encontraron estudiantes coincidentes.</div>`;
                    } else {
                        data.forEach(item => {
                            const opt = document.createElement('div');
                            opt.className = 'p-3 hover:bg-slate-50 dark:hover:bg-slate-700/60 cursor-pointer transition-colors';
                            opt.innerHTML = `
                                <p class="font-bold text-slate-800 dark:text-slate-100">${item.nombre_completo}</p>
                                <p class="text-[10px] text-slate-400 font-mono">Matrícula: ${item.matricula} • ${item.grupo_desc}</p>
                            `;
                            opt.addEventListener('click', () => seleccionarAlumno(item));
                            listaResultados.appendChild(opt);
                        });
                    }
                    listaResultados.classList.remove('hidden');
                });
        }, 250);
    });

    function seleccionarAlumno(item) {
        inputAlumnoId.value = item.id;
        textNombre.innerText = item.nombre_completo;
        textDetalle.innerText = `Matrícula: ${item.matricula} • ${item.grupo_desc}`;

        inputBuscador.value = '';
        listaResultados.classList.add('hidden');
        inputBuscador.parentElement.classList.add('hidden');
        cardSeleccionado.classList.remove('hidden');
        btnSubmit.removeAttribute('disabled');
    }

    function deseleccionarAlumno() {
        inputAlumnoId.value = '';
        cardSeleccionado.classList.add('hidden');
        inputBuscador.parentElement.classList.remove('hidden');
        inputBuscador.focus();
        btnSubmit.setAttribute('disabled', 'disabled');
    }

    document.addEventListener('click', (e) => {
        if (!listaResultados.contains(e.target) && e.target !== inputBuscador) {
            listaResultados.classList.add('hidden');
        }
    });
</script>
@endsection
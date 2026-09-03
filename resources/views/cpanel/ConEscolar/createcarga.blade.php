@extends('cpanel/plantillaCE')
@section('title', 'Asignar Carga Académica')

@section('content')
<main class="flex-1 max-w-5xl w-full mx-auto p-4 md:p-8 space-y-8 text-sm md:text-base transition-colors duration-200">
    
    <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl shadow-xs border border-slate-200 dark:border-slate-800 space-y-8 transition-colors">
        
        <!-- ENCABEZADO -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 dark:border-slate-800 pb-5">
            <div>
                <div class="flex items-center gap-2 text-custom-primary text-xs font-black uppercase tracking-widest mb-1">
                    <span class="material-icons-round text-base">playlist_add</span>
                    <span>Planeación Escolar</span>
                </div>
                <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-slate-100">
                    Distribución de Carga Académica
                </h2>
                <p class="text-slate-500 dark:text-slate-400 text-xs mt-0.5">Asigna docente, asignatura, grupo y define los días y bloques horarios de impartición.</p>
            </div>
            <a href="{{ route('cargas.index') }}" 
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-custom-primary dark:hover:text-custom-primary bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 transition-colors shadow-3xs w-fit">
                <span class="material-icons-round text-sm">arrow_back</span> Regresar al Listado
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900 text-rose-800 dark:text-rose-200 p-4 rounded-2xl text-xs space-y-1.5 shadow-3xs">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center gap-1.5 font-medium"><span class="material-icons-round text-sm text-rose-600 dark:text-rose-400">error</span> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('cargas.store') }}" method="POST" class="space-y-6 text-xs" id="formAsignacion">
            @csrf

            <!-- 1. SELECTORES DE ESTRUCTURA ACADÉMICA -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                
                <!-- Docente -->
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">1. Profesor Asignado *</label>
                    <select name="docente_id" required 
                            class="w-full bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 rounded-2xl p-3 font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all cursor-pointer">
                        <option value="" disabled selected>-- Elige un docente --</option>
                        @foreach($docentes as $docente)
                            @php
                                $nomDocente = $docente->nombre;
                                $patDocente = $docente->apellido_paterno;
                                try {
                                    if (is_string($nomDocente) && (str_starts_with($nomDocente, 'ey') || strlen($nomDocente) > 50)) $nomDocente = decrypt($nomDocente);
                                    if (is_string($patDocente) && (str_starts_with($patDocente, 'ey') || strlen($patDocente) > 50)) $patDocente = decrypt($patDocente);
                                } catch(\Throwable $e) {}
                            @endphp
                            <option value="{{ $docente->id }}" {{ old('docente_id') == $docente->id ? 'selected' : '' }}>
                                {{ $patDocente }} {{ $nomDocente }} ({{ $docente->username }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Materia -->
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">2. Asignatura / Materia *</label>
                    <select name="materia_id" required 
                            class="w-full bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 rounded-2xl p-3 font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all cursor-pointer">
                        <option value="" disabled selected>-- Elige la materia --</option>
                        @foreach($materias as $materia)
                            <option value="{{ $materia->id }}" {{ old('materia_id') == $materia->id ? 'selected' : '' }}>
                                {{ $materia->nombre }} [{{ $materia->clave }}] — {{ $materia->horas_semanales ?? 0 }}h
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Grupo -->
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">3. Grupo Destino *</label>
                    <select name="grupo_id" required 
                            class="w-full bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 rounded-2xl p-3 font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all cursor-pointer">
                        <option value="" disabled selected>-- Elige el grupo --</option>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->id }}" {{ old('grupo_id') == $grupo->id ? 'selected' : '' }}>
                                {{ $grupo->semestre }}° "{{ $grupo->grupo }}" ({{ $grupo->especialidad }}) — {{ $grupo->turno }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- 2. ESPACIO FÍSICO / AULA -->
            <div class="max-w-md">
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Aula / Espacio Físico *</label>
                <div class="relative">
                    <input type="text" name="aula" value="{{ old('aula') }}" required 
                           class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-2xl pl-10 pr-4 py-3 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden placeholder:text-slate-400 dark:placeholder:text-slate-500 transition-all" 
                           placeholder="Ej: Laboratorio de Cómputo B o Aula F-3">
                    <span class="material-icons-round text-slate-400 dark:text-slate-500 text-lg absolute left-3.5 top-3">meeting_room</span>
                </div>
            </div>

            <!-- 3. PLANIFICADOR SEMANAL INTERACTIVO -->
            <div class="space-y-4 pt-2">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 dark:border-slate-800 pb-3">
                    <div>
                        <span class="text-xs font-black text-slate-900 dark:text-slate-100 uppercase tracking-wider block">
                            Calendario Semanal de Clases
                        </span>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Activa los días en los que se imparte la materia y define sus bloques de horario.</p>
                    </div>
                    
                    <!-- Resumen del string generado -->
                    <div class="bg-slate-50 dark:bg-slate-800 px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 text-right">
                        <span class="text-[9px] text-slate-400 uppercase font-black tracking-wider block">Formato resultante</span>
                        <span id="previewHorario" class="font-mono font-bold text-custom-primary text-xs">Sin días seleccionados</span>
                    </div>
                </div>

                <!-- Input oculto donde viaja el string consolidado al backend -->
                <input type="hidden" name="horario" id="inputHorarioConsolidado" value="{{ old('horario') }}">

                <!-- Matriz de Días (Lunes a Viernes) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5" id="matrizDias">
                    @php
                        $diasConfig = [
                            'Lunes'     => ['tag' => 'LUN', 'accent' => 'text-indigo-600 dark:text-indigo-400'],
                            'Martes'    => ['tag' => 'MAR', 'accent' => 'text-blue-600 dark:text-blue-400'],
                            'Miércoles' => ['tag' => 'MIÉ', 'accent' => 'text-emerald-600 dark:text-emerald-400'],
                            'Jueves'    => ['tag' => 'JUE', 'accent' => 'text-amber-600 dark:text-amber-400'],
                            'Viernes'   => ['tag' => 'VIE', 'accent' => 'text-rose-600 dark:text-rose-400'],
                        ];
                    @endphp

                    @foreach($diasConfig as $dia => $data)
                        <div class="dia-card bg-slate-50/70 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800 rounded-2xl p-3.5 space-y-3 transition-all" data-dia="{{ $dia }}">
                            
                            <!-- Checkbox Detonador del Día -->
                            <label class="flex items-center justify-between cursor-pointer select-none">
                                <span class="font-black text-xs uppercase tracking-wider {{ $data['accent'] }}">{{ $dia }}</span>
                                <input type="checkbox" class="dia-check w-4 h-4 rounded text-custom-primary focus:ring-custom-primary border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 cursor-pointer">
                            </label>

                            <!-- Controles de Hora (Deshabilitados hasta marcar checkbox) -->
                            <div class="dia-inputs space-y-2 opacity-40 pointer-events-none transition-opacity">
                                <div>
                                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">Inicio</label>
                                    <input type="time" class="hora-inicio w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl p-2 font-mono text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-custom-primary focus:outline-hidden" value="07:00">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">Fin</label>
                                    <input type="time" class="hora-fin w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl p-2 font-mono text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-custom-primary focus:outline-hidden" value="09:00">
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ALERTA DE REGLA INSTITUCIONAL -->
            <div class="flex items-start space-x-3 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/60 p-4 rounded-2xl text-xs text-amber-800 dark:text-amber-200 leading-relaxed shadow-3xs">
                <span class="material-icons-round text-lg text-amber-600 dark:text-amber-400 shrink-0 mt-0.5">verified_user</span>
                <p><strong>Unicidad y Protección de Conflicto:</strong> El sistema previene automáticamente colisiones de aula o sobrecupo de materias sobre un mismo grupo para garantizar la fiabilidad en el registro de asistencias.</p>
            </div>

            <!-- ACCIONES -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                <a href="{{ route('cargas.index') }}" 
                   class="px-5 py-3 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-7 py-3 bg-custom-primary hover:bg-custom-primary-hover text-white font-black rounded-2xl shadow-xs hover:shadow-md transition-all cursor-pointer flex items-center gap-2">
                    <span class="material-icons-round text-base">save</span>
                    <span>Guardar Carga Académica</span>
                </button>
            </div>
        </form>

    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tarjetas = document.querySelectorAll('.dia-card');
        const inputConsolidado = document.getElementById('inputHorarioConsolidado');
        const preview = document.getElementById('previewHorario');

        function actualizarHorario() {
            let bloques = [];

            tarjetas.forEach(card => {
                const dia = card.getAttribute('data-dia');
                const check = card.querySelector('.dia-check');
                const inputsContainer = card.querySelector('.dia-inputs');
                const hInicio = card.querySelector('.hora-inicio').value;
                const hFin = card.querySelector('.hora-fin').value;

                if (check.checked) {
                    inputsContainer.classList.remove('opacity-40', 'pointer-events-none');
                    card.classList.add('border-custom-primary', 'shadow-2xs');
                    card.classList.remove('border-slate-200', 'dark:border-slate-800');
                    if (hInicio && hFin) {
                        bloques.push(`${dia} (${hInicio} - ${hFin})`);
                    }
                } else {
                    inputsContainer.classList.add('opacity-40', 'pointer-events-none');
                    card.classList.remove('border-custom-primary', 'shadow-2xs');
                    card.classList.add('border-slate-200', 'dark:border-slate-800');
                }
            });

            const resultado = bloques.join(', ');
            inputConsolidado.value = resultado;
            preview.innerText = resultado || 'Sin días seleccionados';
        }

        // Listeners para checkboxes y cambios de hora
        tarjetas.forEach(card => {
            const check = card.querySelector('.dia-check');
            const hInicio = card.querySelector('.hora-inicio');
            const hFin = card.querySelector('.hora-fin');

            check.addEventListener('change', actualizarHorario);
            hInicio.addEventListener('change', actualizarHorario);
            hFin.addEventListener('change', actualizarHorario);
        });

        // Parsear valor previo si viene de old('horario')
        const valorPrevio = inputConsolidado.value;
        if (valorPrevio) {
            tarjetas.forEach(card => {
                const dia = card.getAttribute('data-dia');
                if (valorPrevio.includes(dia)) {
                    const check = card.querySelector('.dia-check');
                    check.checked = true;

                    // Expresión regular para extraer horas si existen: "Lunes (07:00 - 09:00)"
                    const regex = new RegExp(`${dia}\\s*\\((\\d{2}:\\d{2})\\s*-\\s*(\\d{2}:\\d{2})\\)`);
                    const match = valorPrevio.match(regex);
                    if (match) {
                        card.querySelector('.hora-inicio').value = match[1];
                        card.querySelector('.hora-fin').value = match[2];
                    }
                }
            });
            actualizarHorario();
        }

        // Validación final antes de enviar
        document.getElementById('formAsignacion').addEventListener('submit', function(e) {
            actualizarHorario();
            if (!inputConsolidado.value.trim()) {
                e.preventDefault();
                alert('Debes seleccionar al menos un día con su respectivo rango de horas para la asignatura.');
            }
        });
    });
</script>
@endsection
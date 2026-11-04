@extends('cpanel/plantilladocente')
@section('title', 'Registro de Asistencia')

@section('content')
@php
    $mesesEs = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
        7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
    ];
    $anioActual = (int) date('Y');
    $mesActual  = (int) date('m');
@endphp

{{-- Formulario secundario para el reporte en PDF (GET) --}}
<form id="formReporte" action="{{ route('asistencia.reporte', ['carga_id' => $carga->carga_id]) }}" method="GET" target="_blank"></form>

<form action="{{ route('asistencia.guardar', $carga->carga_id) }}" method="POST">
    @csrf
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-8 space-y-6 text-xs md:text-sm transition-colors duration-200">

        {{-- ALERTAS DE ERROR --}}
        @if($errors->any())
            <div class="bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900 text-rose-800 dark:text-rose-300 p-4 rounded-2xl text-xs md:text-sm space-y-1 shadow-xs animate-fade-in">
                <div class="flex items-center gap-2 font-black">
                    <span class="material-icons-round text-base text-rose-600 dark:text-rose-400">error</span>
                    <span>Se encontraron observaciones:</span>
                </div>
                <ul class="list-disc pl-7 font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900 text-rose-800 dark:text-rose-300 p-4 rounded-2xl text-xs md:text-sm font-bold flex items-center gap-2 shadow-xs">
                <span class="material-icons-round text-base text-rose-600 dark:text-rose-400">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- PANEL DE CONTROL SUPERIOR --}}
        <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl shadow-2xs border border-slate-200 dark:border-slate-800 flex flex-col lg:flex-row lg:items-center justify-between gap-6 transition-colors duration-200">
            
            {{-- INFORMACIÓN DE LA MATERIA Y FECHA --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 flex-1">
                <div>
                    <label class="block font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest text-[10px] mb-1.5">Materia / Grupo</label>
                    <div class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-2xl p-3 font-extrabold text-slate-900 dark:text-white truncate flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                            <span class="material-icons-round text-lg">class</span>
                        </div>
                        <span class="truncate">{{ $carga->nombre }} ({{ $carga->semestre }}° "{{ $carga->grupo }}")</span>
                    </div>
                </div>

                <div>
                    <label class="block font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest text-[10px] mb-1.5">Fecha de Registro</label>
                    <div class="relative">
                        <input type="date" name="fecha" value="{{ date('Y-m-d') }}" 
                               class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-2xl p-3 font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-600 dark:focus:ring-indigo-500 focus:outline-hidden transition-all text-xs md:text-sm">
                    </div>
                </div>
            </div>

            {{-- BOTONES DE ACCIÓN PRINCIPALES --}}
            <div class="flex items-center gap-2.5 flex-wrap w-full lg:w-auto shrink-0 pt-4 lg:pt-0 border-t lg:border-t-0 border-slate-100 dark:border-slate-800 justify-end">
                
                {{-- REGRESAR --}}
                <a href="{{ route('dashboardDocente.index') }}" 
                   class="px-4 py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 font-bold rounded-2xl transition-all flex items-center gap-1.5 text-xs active:scale-98">
                    <span class="material-icons-round text-base">arrow_back</span>
                    <span>Volver</span>
                </a>

                {{-- REPORTE MENSUAL (CON SELECTORES DE MES/AÑO) --}}
                <div class="flex items-center gap-1.5 bg-slate-100 dark:bg-slate-800/80 p-1.5 rounded-2xl border border-slate-200/60 dark:border-slate-700/60">
                    <select name="mes" form="formReporte"
                            class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-2.5 py-2 font-bold text-slate-800 dark:text-slate-200 text-xs focus:ring-2 focus:ring-indigo-600 focus:outline-hidden">
                        @foreach($mesesEs as $num => $nomMes)
                            <option value="{{ $num }}" {{ $num === $mesActual ? 'selected' : '' }}>{{ $nomMes }}</option>
                        @endforeach
                    </select>

                    <select name="anio" form="formReporte"
                            class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-2.5 py-2 font-bold text-slate-800 dark:text-slate-200 text-xs focus:ring-2 focus:ring-indigo-600 focus:outline-hidden">
                        @for($y = $anioActual; $y >= $anioActual - 2; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>

                    <button type="submit" form="formReporte"
                            class="px-3.5 py-2 bg-slate-900 hover:bg-black dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-extrabold rounded-xl transition-all shadow-xs flex items-center gap-1.5 text-xs active:scale-98 cursor-pointer">
                        <span class="material-icons-round text-base">print</span>
                        <span class="hidden sm:inline">Reporte PDF</span>
                    </button>
                </div>

                {{-- GUARDAR --}}
                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white font-extrabold rounded-2xl shadow-md transition-all active:scale-98 cursor-pointer flex items-center gap-2 text-xs md:text-sm">
                    <span class="material-icons-round text-lg">save</span>
                    <span>Guardar Asistencia</span>
                </button>
            </div>
        </div>

        {{-- BARRA DE HERRAMIENTAS RÁPIDAS Y MARCADORES GLOABLES --}}
        <div class="bg-white dark:bg-slate-900 p-4 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xs flex flex-col xl:flex-row items-center justify-between gap-4">
            
            {{-- BUSCADOR EN TIEMPO REAL --}}
            <div class="relative w-full xl:w-80">
                <span class="material-icons-round absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                <input type="text" id="inputBuscarAlumno" placeholder="Buscar alumno por nombre o matrícula..."
                       class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-2xl pl-10 pr-4 py-2.5 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:ring-2 focus:ring-indigo-600 focus:outline-hidden transition-all">
            </div>

            {{-- MARCADORES RÁPIDOS MULTIESTATUS --}}
            <div class="flex items-center justify-between xl:justify-end gap-3 w-full xl:w-auto flex-wrap">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">
                    Total: <strong class="text-slate-900 dark:text-white font-black">{{ count($alumnos) }}</strong> alumnos
                </span>
                <div class="h-4 w-px bg-slate-200 dark:bg-slate-800 hidden sm:block"></div>
                
                <div class="flex items-center gap-1.5 flex-wrap">
                    <button type="button" onclick="marcarEstadoGlobal('Asistencia')" 
                            class="px-2.5 py-1.5 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100 border border-emerald-200 dark:border-emerald-800 rounded-xl font-bold text-[11px] transition-colors cursor-pointer flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Todos Asistencia (•)
                    </button>
                    <button type="button" onclick="marcarEstadoGlobal('Falta')" 
                            class="px-2.5 py-1.5 bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 hover:bg-rose-100 border border-rose-200 dark:border-rose-800 rounded-xl font-bold text-[11px] transition-colors cursor-pointer flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span> Todos Falta (/)
                    </button>
                    <button type="button" onclick="marcarEstadoGlobal('Justificado')" 
                            class="px-2.5 py-1.5 bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 hover:bg-amber-100 border border-amber-200 dark:border-amber-800 rounded-xl font-bold text-[11px] transition-colors cursor-pointer flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span> Todos Justificado (J)
                    </button>
                    <button type="button" onclick="marcarEstadoGlobal('Retardo')" 
                            class="px-2.5 py-1.5 bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 hover:bg-blue-100 border border-blue-200 dark:border-blue-800 rounded-xl font-bold text-[11px] transition-colors cursor-pointer flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span> Todos Retardo (R)
                    </button>
                </div>
            </div>
        </div>

        {{-- TABLA DE LISTA DE ASISTENCIA --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xs border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors duration-200">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="tablaAsistencia">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-700/80 text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-widest">
                            <th class="p-4 w-12 text-center">No.</th>
                            <th class="p-4">Alumno / Expediente</th>
                            <th class="p-4 text-center min-w-[320px]">Estatus de Clase (Hoy)</th>
                            <th class="p-4 text-center w-24">Clases</th>
                            <th class="p-4 text-center w-24">Faltas</th>
                            <th class="p-4 text-center w-48 bg-slate-100/50 dark:bg-slate-800/30">Asistencia Global</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs md:text-sm font-medium">

                        @forelse($alumnos as $index => $alumno)
                            @php
                                $nombre = $alumno->nombre;
                                $paterno = $alumno->apellido_paterno;
                                $materno = $alumno->apellido_materno;

                                try {
                                    if (is_string($nombre) && (str_starts_with($nombre, 'ey') || strlen($nombre) > 50)) {
                                        $nombre = decrypt($nombre);
                                    }
                                    if (is_string($paterno) && (str_starts_with($paterno, 'ey') || strlen($paterno) > 50)) {
                                        $paterno = decrypt($paterno);
                                    }
                                    if (is_string($materno) && (str_starts_with($materno, 'ey') || strlen($materno) > 50)) {
                                        $materno = decrypt($materno);
                                    }
                                } catch (\Throwable $e) {
                                    $nombre = str_replace(' (Plain)', '', $nombre);
                                    $paterno = str_replace(' (Plain)', '', $paterno);
                                    $materno = str_replace(' (Plain)', '', $materno);
                                }

                                $porcentaje = $alumno->clases_totales > 0
                                    ? round((($alumno->clases_totales - $alumno->faltas_acumuladas) / $alumno->clases_totales) * 100, 1)
                                    : 100;
                                
                                $idAl = $alumno->alumno_id;
                            @endphp

                            <tr class="fila-alumno hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors {{ $porcentaje < 80 ? 'bg-rose-50/40 dark:bg-rose-950/20' : '' }}">
                                
                                {{-- NÚMERO CONSECUTIVO --}}
                                <td class="p-4 font-mono text-slate-400 dark:text-slate-500 text-center font-bold text-xs">
                                    {{ \Illuminate\Support\Str::padLeft($index + 1, 2, '0') }}
                                </td>

                                {{-- DETALLES DEL ALUMNO --}}
                                <td class="p-4 celda-nombre">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-black text-xs flex items-center justify-center shrink-0 border border-slate-200 dark:border-slate-700">
                                            {{ substr($paterno, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-extrabold text-slate-900 dark:text-white text-xs md:text-sm">
                                                {{ $paterno }} {{ $materno }} {{ $nombre }}
                                            </div>
                                            <div class="text-[11px] text-slate-400 dark:text-slate-500 font-mono mt-0.5">
                                                Matrícula: {{ $alumno->username }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- MARCADOR DE ASISTENCIA DIVERSIFICADO (RADIO BUTTONS ESTILIZADOS) --}}
                                <td class="p-4 text-center">
                                    <div class="inline-flex items-center bg-slate-100 dark:bg-slate-800/90 p-1.5 rounded-2xl border border-slate-200 dark:border-slate-700 gap-1">
                                        
                                        {{-- ASISTENCIA --}}
                                        <label class="cursor-pointer">
                                            <input type="radio" name="asistencias[{{ $idAl }}]" value="Asistencia" checked 
                                                   class="peer hidden radio-asistencia input-asistencia">
                                            <span class="px-3 py-1.5 rounded-xl font-bold text-xs text-slate-600 dark:text-slate-400 peer-checked:bg-emerald-500 peer-checked:text-white transition-all flex items-center gap-1 shadow-2xs">
                                                <span class="font-black text-sm leading-none">•</span> Asistencia
                                            </span>
                                        </label>

                                        {{-- FALTA --}}
                                        <label class="cursor-pointer">
                                            <input type="radio" name="asistencias[{{ $idAl }}]" value="Falta" 
                                                   class="peer hidden radio-asistencia input-falta">
                                            <span class="px-3 py-1.5 rounded-xl font-bold text-xs text-slate-600 dark:text-slate-400 peer-checked:bg-rose-500 peer-checked:text-white transition-all flex items-center gap-1 shadow-2xs">
                                                <span class="font-black text-xs leading-none">/</span> Falta
                                            </span>
                                        </label>

                                        {{-- JUSTIFICACIÓN --}}
                                        <label class="cursor-pointer">
                                            <input type="radio" name="asistencias[{{ $idAl }}]" value="Justificado" 
                                                   class="peer hidden radio-asistencia input-justificado">
                                            <span class="px-3 py-1.5 rounded-xl font-bold text-xs text-slate-600 dark:text-slate-400 peer-checked:bg-amber-500 peer-checked:text-white transition-all flex items-center gap-1 shadow-2xs">
                                                <span class="font-black text-xs leading-none">J</span> Justificado
                                            </span>
                                        </label>

                                        {{-- RETARDO --}}
                                        <label class="cursor-pointer">
                                            <input type="radio" name="asistencias[{{ $idAl }}]" value="Retardo" 
                                                   class="peer hidden radio-asistencia input-retardo">
                                            <span class="px-3 py-1.5 rounded-xl font-bold text-xs text-slate-600 dark:text-slate-400 peer-checked:bg-blue-500 peer-checked:text-white transition-all flex items-center gap-1 shadow-2xs">
                                                <span class="font-black text-xs leading-none">R</span> Retardo
                                            </span>
                                        </label>
                                    </div>
                                </td>

                                {{-- TOTAL CLASES --}}
                                <td class="p-4 text-center font-bold text-slate-700 dark:text-slate-300">
                                    {{ $alumno->clases_totales }}
                                </td>

                                {{-- FALTAS ACUMULADAS --}}
                                <td class="p-4 text-center font-black {{ $alumno->faltas_acumuladas > 4 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-700 dark:text-slate-300' }}">
                                    {{ $alumno->faltas_acumuladas }}
                                </td>

                                {{-- BARRA VISUAL Y PORCENTAJE GLOBAL --}}
                                <td class="p-4 text-center bg-slate-50/50 dark:bg-slate-800/20">
                                    <div class="flex items-center gap-3 justify-center">
                                        <div class="flex-1 max-w-[90px] bg-slate-200 dark:bg-slate-700 h-2 rounded-full overflow-hidden hidden sm:block">
                                            <div class="h-full rounded-full transition-all duration-500 {{ $porcentaje < 80 ? 'bg-rose-600' : 'bg-emerald-500' }}" style="width: {{ $porcentaje }}%"></div>
                                        </div>
                                        <span class="font-black text-xs px-2.5 py-1 rounded-xl border {{ $porcentaje < 80 ? 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-900/60' : 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-900/60' }}">
                                            {{ $porcentaje }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-xs md:text-sm font-medium text-slate-400 dark:text-slate-500">
                                    <span class="material-icons-round text-4xl block mb-2 text-slate-300 dark:text-slate-700">group_off</span>
                                    No hay alumnos inscritos en este grupo.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        {{-- AVISO REGLAMENTARIO --}}
        <div class="flex items-start gap-3 bg-blue-50/80 dark:bg-blue-950/40 border border-blue-200/80 dark:border-blue-900/60 p-5 rounded-3xl text-xs text-blue-900 dark:text-blue-200 leading-relaxed shadow-2xs">
            <span class="material-icons-round text-xl text-blue-600 dark:text-blue-400 shrink-0 mt-0.5">info</span>
            <p><strong>Reglamento Institucional:</strong> Los estudiantes con un porcentaje de asistencia acumulado inferior al <strong>80%</strong> pierden automáticamente el derecho a evaluación ordinaria. Las inasistencias críticas son notificadas al departamento de Orientación Educativa.</p>
        </div>

    </main>
</form>

{{-- SCRIPT PARA FILTRO Y MARCADOR GLOBAL --}}
<script>
    function marcarEstadoGlobal(tipo) {
        let selector = '';
        if (tipo === 'Asistencia') selector = '.input-asistencia';
        else if (tipo === 'Falta') selector = '.input-falta';
        else if (tipo === 'Justificacion') selector = '.input-justificacion';
        else if (tipo === 'Retardo') selector = '.input-retardo';

        if (selector) {
            document.querySelectorAll(selector).forEach(radio => {
                radio.checked = true;
            });
        }
    }

    document.getElementById('inputBuscarAlumno')?.addEventListener('keyup', function() {
        const query = this.value.toLowerCase();
        const filas = document.querySelectorAll('#tablaAsistencia tbody .fila-alumno');

        filas.forEach(fila => {
            const texto = fila.querySelector('.celda-nombre')?.textContent.toLowerCase() || '';
            fila.style.display = texto.includes(query) ? '' : 'none';
        });
    });
</script>
@endsection
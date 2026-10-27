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

{{-- Formulario vacío del reporte (GET). Los controles se asocian con form="formReporte" para no anidar formularios --}}
<form id="formReporte" action="{{ route('asistencia.reporte', ['carga_id' => $carga->carga_id]) }}" method="GET" target="_blank"></form>

<form action="{{ route('asistencia.guardar', $carga->carga_id) }}" method="POST">
    @csrf
    <main class="flex-1 max-w-6xl w-full mx-auto p-6 md:p-8 space-y-8 text-sm md:text-base">

        <!-- ALERTAS DE ERROR (validación o errores del controlador) -->
        @if($errors->any())
            <div class="bg-red-50 dark:bg-rose-950/40 border border-red-200 dark:border-rose-900 text-red-700 dark:text-rose-300 p-4 rounded-2xl text-sm">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 dark:bg-rose-950/40 border border-red-200 dark:border-rose-900 text-red-700 dark:text-rose-300 p-4 rounded-2xl text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- PANEL DE CONTROL SUPERIOR -->
        <div class="bg-white dark:bg-slate-900 p-6 md:p-7 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-800 flex flex-col xl:flex-row xl:items-center justify-between gap-6 transition-colors duration-200">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 flex-1 text-xs md:text-sm">

                <!-- Materia y Grupo -->
                <div>
                    <label class="block font-black text-slate-400 dark:text-slate-400 uppercase tracking-wider text-[11px] mb-2">Materia / Grupo</label>
                    <div class="bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-bold text-slate-800 dark:text-slate-100 truncate shadow-3xs">
                        {{ $carga->nombre }} ({{ $carga->semestre }}° "{{ $carga->grupo }}")
                    </div>
                </div>

                <!-- Periodo Evaluatorio -->
                <div>
                    <label class="block font-black text-slate-400 dark:text-slate-400 uppercase tracking-wider text-[11px] mb-2">Periodo Evaluatorio</label>
                    <select name="periodo" class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-600 focus:outline-hidden transition-all">
                        <option value="Parcial 1">Parcial 1</option>
                        <option value="Parcial 2">Parcial 2</option>
                        <option value="Parcial 3">Parcial 3 / Final</option>
                    </select>
                </div>

                <!-- Fecha de Registro -->
                <div>
                    <label class="block font-black text-slate-400 dark:text-slate-400 uppercase tracking-wider text-[11px] mb-2">Fecha de Registro</label>
                    <input type="date" name="fecha" value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-600 focus:outline-hidden transition-all">
                </div>
            </div>

            <!-- BOTONES DE ACCIÓN -->
            <div class="flex items-center gap-2.5 flex-wrap w-full xl:w-auto xl:shrink-0 pt-4 xl:pt-0 border-t xl:border-t-0 border-slate-100 dark:border-slate-800">

                <!-- VOLVER AL DASHBOARD DOCENTE -->
                <a href="{{ route('dashboardDocente.index') }}" class="px-4 py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-2xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors flex items-center gap-1.5 text-xs md:text-sm">
                    <span class="material-icons-round text-base">arrow_back</span> Volver
                </a>

                <!-- REPORTE MENSUAL CON SELECTOR DE MES Y AÑO -->
                <div class="flex items-center gap-2 bg-slate-100 dark:bg-slate-800 rounded-2xl p-1.5">
                    <select name="mes" form="formReporte"
                            class="bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-xs md:text-sm font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-600 focus:outline-hidden">
                        @foreach($mesesEs as $num => $nomMes)
                            <option value="{{ $num }}" {{ $num === $mesActual ? 'selected' : '' }}>{{ $nomMes }}</option>
                        @endforeach
                    </select>

                    <select name="anio" form="formReporte"
                            class="bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-xs md:text-sm font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-600 focus:outline-hidden">
                        @for($y = $anioActual; $y >= $anioActual - 2; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>

                    <button type="submit" form="formReporte"
                            class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-extrabold rounded-xl transition-all shadow-md flex items-center gap-1.5 text-xs md:text-sm cursor-pointer">
                        <span class="material-icons-round text-base">print</span> Imprimir Reporte
                    </button>
                </div>

                <button type="submit" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-2xl transition-all shadow-md flex items-center gap-2 cursor-pointer text-xs md:text-sm">
                    <span class="material-icons-round text-lg">save</span> Guardar Asistencia
                </button>
            </div>
        </div>

        <!-- TABLA DE LISTA DE ASISTENCIA -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors duration-200">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-700/80 text-slate-400 dark:text-slate-400 text-xs font-black uppercase tracking-wider">
                            <th class="p-4 md:p-5 w-16 text-center">No.</th>
                            <th class="p-4 md:p-5">Alumno (Expediente)</th>
                            <th class="p-4 md:p-5 text-center w-44">Asistencia (Hoy)</th>
                            <th class="p-4 md:p-5 text-center w-36">Sesiones</th>
                            <th class="p-4 md:p-5 text-center w-36">Faltas</th>
                            <th class="p-4 md:p-5 text-center w-52 bg-indigo-50/60 dark:bg-indigo-950/40 text-indigo-900 dark:text-indigo-200">Porcentaje Global</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-xs md:text-sm">

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
                            @endphp

                            <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors {{ $porcentaje < 80 ? 'bg-red-50/30 dark:bg-rose-950/20' : '' }}">
                                <td class="p-4 md:p-5 font-mono text-slate-400 dark:text-slate-500 text-center font-bold">
                                    {{ \Illuminate\Support\Str::padLeft($index + 1, 2, '0') }}
                                </td>

                                <td class="p-4 md:p-5">
                                    <div class="font-extrabold text-slate-900 dark:text-slate-100 text-sm md:text-base">
                                        {{ $paterno }} {{ $materno }} {{ $nombre }}
                                    </div>
                                    <div class="text-xs text-slate-400 dark:text-slate-500 font-mono mt-0.5">Matrícula: {{ $alumno->username }}</div>
                                </td>

                                <td class="p-4 md:p-5 text-center">
                                    <label class="inline-flex items-center justify-center p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer transition-colors">
                                        <input type="checkbox"
                                               name="asistencias[{{ $alumno->alumno_id }}]"
                                               value="Asistencia"
                                               checked
                                               class="w-5 h-5 text-indigo-600 dark:text-indigo-500 border-slate-300 dark:border-slate-700 rounded-lg focus:ring-indigo-500 dark:bg-slate-800 cursor-pointer">
                                    </label>
                                </td>

                                <td class="p-4 md:p-5 text-center font-bold text-slate-700 dark:text-slate-300">{{ $alumno->clases_totales }}</td>

                                <td class="p-4 md:p-5 text-center font-black {{ $alumno->faltas_acumuladas > 4 ? 'text-red-600 dark:text-rose-400' : 'text-slate-700 dark:text-slate-300' }}">
                                    {{ $alumno->faltas_acumuladas }}
                                </td>

                                @if($porcentaje < 80)
                                    <td class="p-4 md:p-5 text-center font-black text-white bg-red-600 dark:bg-rose-700">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <span class="material-icons-round text-base">error</span> {{ $porcentaje }}%
                                        </div>
                                    </td>
                                @else
                                    <td class="p-4 md:p-5 text-center font-black text-emerald-600 dark:text-emerald-400 bg-indigo-50/20 dark:bg-indigo-950/20">
                                        {{ $porcentaje }}%
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-base font-medium text-slate-400 dark:text-slate-500">
                                    <span class="material-icons-round text-4xl block mb-2 text-slate-300 dark:text-slate-700">group_off</span>
                                    No hay alumnos inscritos en este grupo.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        <!-- AVISO DE REGLAMENTO -->
        <div class="flex items-start gap-3 bg-blue-50 dark:bg-blue-950/50 border border-blue-200 dark:border-blue-900/60 p-5 rounded-2xl text-xs md:text-sm text-blue-900 dark:text-blue-200 leading-relaxed shadow-3xs">
            <span class="material-icons-round text-xl text-blue-600 dark:text-blue-400 shrink-0 mt-0.5">info</span>
            <p><strong>Reglamento Institucional:</strong> Los estudiantes marcados con un porcentaje de asistencia inferior al <strong>80%</strong> pierden automáticamente el derecho a la evaluación ordinaria del parcial. El sistema emite alertas automáticas al departamento de Orientación Educativa.</p>
        </div>

    </main>
</form>
@endsection
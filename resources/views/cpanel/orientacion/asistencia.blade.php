@extends('cpanel/plantilladocente')
@section('title', 'Registro de Asistencia')
@section('content')
<form action="{{ route('asistencia.guardar', $carga->id) }}" method="POST">
    @csrf
    <main class="flex-1 max-w-6xl w-full mx-auto p-4 md:p-6 space-y-6">
        
        <div class="bg-white p-4 rounded-xl shadow-xs border border-slate-200 flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-500 uppercase mb-1">Materia / Grupo</label>
                    <div class="bg-slate-100 border border-slate-300 rounded-lg p-2 font-semibold text-slate-700">
                        {{ $carga->nombre }} - Semestre {{ $carga->semestre }}°{{ $carga->grupo }}
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-500 uppercase mb-1">Periodo Evaluatorio</label>
                    <select name="periodo" class="bg-slate-50 border border-slate-300 rounded-lg p-2 font-medium focus:ring-1 focus:ring-indigo-600 focus:outline-hidden">
                        <option value="Parcial 2">Parcial 2</option>
                        <option value="Parcial 1">Parcial 1</option>
                        <option value="Parcial 3">Parcial 3 / Final</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-500 uppercase mb-1">Fecha de Registro</label>
                    <input type="date" name="fecha" value="{{ date('Y-m-d') }}" class="bg-slate-50 border border-slate-300 rounded-lg p-1.5 font-medium focus:ring-1 focus:ring-indigo-600 focus:outline-hidden">
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ url('docente/index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg hover:bg-slate-200 transition-colors flex items-center">
                    Volver al Panel
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition-colors shadow-2xs flex items-center cursor-pointer">
                    <span class="material-icons-round text-sm mr-1">save</span> Guardar Asistencia
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xs border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[11px] font-bold uppercase tracking-wider">
                            <th class="p-4 w-16">No.</th>
                            <th class="p-4">Alumno (Matrícula)</th>
                            <th class="p-4 text-center w-40">Asistencia (Hoy)</th>
                            <th class="p-4 text-center w-40">Clases Totales</th>
                            <th class="p-4 text-center w-40">Faltas Acumuladas</th>
                            <th class="p-4 text-center w-48 bg-indigo-50/50 text-indigo-900">Porcentaje de Asistencia</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-xs">
                        
                        @forelse($alumnos as $index => $alumno)
                            @php
                                // Cálculo dinámico en tiempo real del porcentaje para la alerta visual
                                $porcentaje = $alumno->clases_totales > 0 
                                    ? round((($alumno->clases_totales - $alumno->faltas_acumuladas) / $alumno->clases_totales) * 100, 1) 
                                    : 100;
                            @endphp
                            
                            <tr class="hover:bg-slate-50/50 transition-colors {{ $porcentaje < 80 ? 'bg-red-50/20' : '' }}">
                                {{-- Línea con el error --}}
                                    <td class="p-4 font-mono text-slate-400">{{ \Illuminate\Support\Str::padLeft($index + 1, 2, '0') }}</td>
                                <td class="p-4">
                                    <div class="font-bold text-slate-900">
                                        {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }} {{ $alumno->nombre }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $alumno->username }}</div>
                                </td>
                                <td class="p-4 text-center">
                                    <input type="checkbox" 
                                           name="asistencias[{{ $alumno->alumno_id }}]" 
                                           value="Asistencia" 
                                           checked 
                                           class="w-4 h-4 text-indigo-600 border-slate-300 rounded-sm focus:ring-indigo-500 cursor-pointer">
                                </td>
                                <td class="p-4 text-center font-medium text-slate-600">{{ $alumno->clases_totales }}</td>
                                <td class="p-4 text-center font-bold {{ $alumno->faltas_acumuladas > 4 ? 'text-red-600' : 'text-slate-600' }}">
                                    {{ $alumno->faltas_acumuladas }}
                                </td>
                                
                                @if($porcentaje < 80)
                                    <td class="p-4 text-center font-bold text-white bg-red-600">
                                        <div class="flex items-center justify-center gap-1">
                                            <span class="material-icons-round text-sm">error</span> {{ $porcentaje }}%
                                        </div>
                                    </td>
                                @else
                                    <td class="p-4 text-center font-bold text-emerald-600 bg-indigo-50/10">
                                        {{ $porcentaje }}%
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-sm font-medium text-slate-500">
                                    No hay alumnos inscritos en este grupo.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex items-start space-x-2 bg-blue-50 border border-blue-200 p-3 rounded-xl text-[11px] text-blue-800 leading-relaxed">
            <span class="material-icons-round text-sm mt-0.5">info</span>
            <p><strong>Reglamento Institucional:</strong> Los alumnos marcados con un porcentaje inferior al 80% pierden de forma automática el derecho a la evaluación ordinaria del parcial. Los casos críticos son notificados a Orientación Educativa.</p>
        </div>

    </main>
</form>
@endsection
@extends('cpanel/plantillaorientacion')
@section('title', 'Alerta Inasistencia')
@section('content')
<main class="flex-1 max-w-6xl w-full mx-auto p-4 md:p-6 space-y-6">
    
    <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1">
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-red-600 {{ $alumnos->count() > 0 ? 'animate-pulse' : '' }}"></span>
                <span class="text-xs font-bold text-red-700 uppercase tracking-wider">Reporte Crítico de Asistencias</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900">Alumnos con Menos del 80% de Asistencia</h2>
            <p class="text-xs text-slate-500">Listado consolidado de estudiantes que se encuentran en riesgo de perder derecho a examen en sus respectivas asignaturas.</p>
        </div>
        
        <form action="{{ route('asistencias.criticas') }}" method="GET" class="flex gap-2">
            <select name="grupo_id" onchange="this.form.submit()"
                    class="bg-slate-50 border border-slate-300 rounded-xl p-2.5 text-xs font-semibold focus:ring-1 focus:ring-[#841B44] focus:outline-hidden text-slate-700">
                <option value="">Todos los Grupos Críticos</option>
                @foreach($gruposActivos as $g)
                    <option value="{{ $g->id }}" {{ request('grupo_id') == $g->id ? 'selected' : '' }}>
                        {{ $g->semestre }}° Semestre - Grupo "{{ $g->grupo }}" ({{ $g->especialidad }})
                    </option>
                @endforeach
            </select>
            @if(request('grupo_id'))
                <a href="{{ route('asistencias.criticas') }}" class="p-2.5 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 flex items-center" title="Limpiar filtro">
                    <span class="material-icons-round text-sm">clear</span>
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-xs border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[11px] font-bold uppercase tracking-wider">
                        <th class="p-4">Alumno</th>
                        <th class="p-4">Grupo / Especialidad</th>
                        <th class="p-4">Asignatura Afectada</th>
                        <th class="p-4 text-center">Faltas Acum.</th>
                        <th class="p-4 text-center">Porcentaje</th>
                        <th class="p-4">Tutor Registrado</th>
                        <th class="p-4 text-right">Acción Preventiva</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs">
                    @forelse($alumnos as $alumno)
                        @php
                            // Determinación de colores del semáforo matricular en base a tu lógica
                            $esCritico = $alumno->porcentaje_asistencia < 80.0;
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors {{ $esCritico ? 'bg-red-50/10' : '' }}">
                            <td class="p-4">
                                <div class="font-bold text-slate-900">{{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }} {{ $alumno->nombre }}</div>
                                <div class="text-[10px] text-slate-400 font-mono mt-0.5">Matrícula: {{ $alumno->username }}</div>
                            </td>
                            
                            <td class="p-4 text-slate-600 font-medium">
                                {{ $alumno->semestre }}° Semestre - Grupo "{{ $alumno->grupo }}"
                                <div class="text-[10px] text-slate-400">{{ $alumno->especialidad }}</div>
                            </td>
                            
                            <td class="p-4 font-semibold text-slate-800 leading-tight">
                                {{ $alumno->materia_nombre }}
                                <div class="text-[10px] font-mono text-[#841B44] mt-0.5">{{ $alumno->clave }}</div>
                            </td>
                            
                            <td class="p-4 text-center font-bold {{ $esCritico ? 'text-red-600' : 'text-amber-600' }}">
                                {{ $alumno->total_faltas }} Faltas
                            </td>
                            
                            <td class="p-4 text-center">
                                @if($esCritico)
                                    <span class="inline-flex items-center gap-1 bg-red-600 text-white font-black px-2 py-1 rounded-md text-[11px] shadow-3xs">
                                        <span class="material-icons-round text-xs">warning</span> {{ number_format($alumno->porcentaje_asistencia, 1) }}%
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-amber-500 text-white font-bold px-2 py-1 rounded-md text-[11px] shadow-3xs">
                                        <span class="material-icons-round text-xs">error_outline</span> {{ number_format($alumno->porcentaje_asistencia, 1) }}%
                                    </span>
                                @endif
                            </td>
                            
                            <td class="p-4">
                                <div class="font-semibold text-slate-800">{{ $alumno->nombre_tutor }}</div>
                                <div class="text-[10px] text-slate-400 flex items-center mt-0.5">
                                    <span class="material-icons-round text-xs mr-1 text-slate-400">phone_iphone</span> Tel: {{ $alumno->telefono_tutor }}
                                </div>
                            </td>
                            
                            <td class="p-4 text-right">
                                @if($esCritico)
                                    <form action="{{ route('asistencias.alerta-tutor') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="alumno_id" value="{{ $alumno->alumno_id }}">
                                        <input type="hidden" name="carga_id" value="{{ $alumno->carga_id }}">
                                        <button type="submit" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-[11px] font-bold rounded-xl shadow-2xs transition-all flex items-center gap-1.5 ml-auto cursor-pointer">
                                            <span class="material-icons-round text-sm">emergency_share</span> Enviar Alerta al Tutor
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="px-3 py-2 border border-amber-500 hover:bg-amber-50 text-amber-700 text-[11px] font-bold rounded-xl shadow-3xs transition-all flex items-center gap-1.5 ml-auto cursor-pointer">
                                        <span class="material-icons-round text-sm">sms</span> Notificación Preventiva
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400 font-medium">
                                <span class="material-icons-round text-2xl block mb-1">check_circle_outline</span>
                                Excelente: No se registran alumnos con porcentaje de asistencia menor al 80% en los parámetros elegidos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($alumnos->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                {{ $alumnos->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl text-xs text-blue-800 leading-relaxed flex items-start space-x-3">
        <span class="material-icons-round text-base text-blue-600 mt-0.5">bolt</span>
        <div>
            <strong>Automatización de Alertas SUIE:</strong> Al presionar el botón <code class="bg-blue-100 px-1 rounded font-mono font-bold text-blue-900">Enviar Alerta al Tutor</code>, el motor automatizado genera un reporte pormenorizado con la bitácora de faltas ordinarias acumuladas en el ciclo y lo despacha al tutor de emergencia.
        </div>
    </div>

</main>
@endsection
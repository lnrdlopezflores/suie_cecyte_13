@extends('cpanel/plantillaCE')
@section('title', 'Asignación de Alumnos')
@section('content')
<main class="flex-1 max-w-5xl w-full mx-auto p-4 md:p-6 space-y-6">

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-3xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                <span class="material-icons-round text-[#841B44]">group_add</span> Asignación Eficiente de Grupos
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Asigna múltiples estudiantes sin grupo a un salón de clases en un solo movimiento técnico.</p>
        </div>
        <a href="{{ route('alumnos.index') }}" class="text-xs font-bold text-slate-600 hover:text-[#841B44] flex items-center gap-1">
            <span class="material-icons-round text-sm">arrow_back</span> Ver Matrícula General
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-xs flex items-center gap-2 shadow-3xs">
            <span class="material-icons-round text-base">check_circle</span> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('alumnos.guardar-asignacion-masiva') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-3">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                <span class="material-icons-round text-sm text-[#841B44]">school</span> 1. Seleccionar Grupo Destino
            </h3>
            <div class="w-full md:w-1/2">
                <select name="grupo_id" required 
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden text-xs">
                    <option value="" disabled selected>-- Elige el grupo receptor de los alumnos --</option>
                    @foreach($grupos as $gp)
                        <option value="{{ $gp->id }}">{{ $gp->semestre }}° "{{ $gp->grupo }}" - {{ $gp->especialidad }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1">
                        <span class="material-icons-round text-sm text-[#0F7F41]">person_search</span> 2. Alumnos Pendientes por Asignar
                    </h3>
                    <p class="text-[11px] text-slate-500">Mostrando estudiantes registrados en el sistema que no tienen ningún grupo asignado.</p>
                </div>
                
                <div class="text-xs">
                    <button type="button" onclick="toggleMarcarTodos()" class="px-3 py-1.5 bg-slate-200 text-slate-700 font-bold rounded-lg hover:bg-slate-300 transition-colors cursor-pointer">
                        Seleccionar Todos
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                            <th class="p-4 w-16 text-center">Incluir</th>
                            <th class="p-4">Matrícula base</th>
                            <th class="p-4">Nombre Completo</th>
                            <th class="p-4">Tutor Legal</th>
                            <th class="p-4">Fecha de Alta</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-xs" id="listaAlumnos">
                        @forelse($alumnosSinGrupo as $alumno)
                            <tr class="hover:bg-slate-50/60 transition-colors cursor-pointer" onclick="toggleRowCheckbox(this, event)">
                                <td class="p-4 text-center">
                                    <input type="checkbox" name="alumno_ids[]" value="{{ $alumno->alumno_id }}" 
                                           class="alumno-check w-4 h-4 text-indigo-600 border-slate-300 rounded-sm focus:ring-indigo-500 cursor-pointer"
                                           onclick="event.stopPropagation()">
                                </td>
                                <td class="p-4 font-mono font-bold text-slate-900">{{ $alumno->username }}</td>
                                <td class="p-4 font-semibold text-slate-700">
                                    {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }} {{ $alumno->nombre }}
                                </td>
                                <td class="p-4 text-slate-500">{{ $alumno->nombre_tutor }}</td>
                                <td class="p-4 font-mono text-slate-400 text-[11px]">{{ \Carbon\Carbon::parse($alumno->created_at)->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-slate-400 font-medium">
                                    <span class="material-icons-round text-3xl block text-emerald-600 mb-1">done_all</span>
                                    Excelente. No hay alumnos pendientes de grupo en la plataforma SUIE.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <button type="submit" class="px-6 py-3 bg-[#841B44] hover:bg-[#6b1536] text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-1.5 cursor-pointer">
                <span class="material-icons-round text-sm">save</span> Procesar Inscripción Masiva
            </button>
        </div>
    </form>
</main>

<script>
    // Permitir seleccionar la fila completa al hacer clic en cualquier parte de ella
    function toggleRowCheckbox(row, event) {
        const checkbox = row.querySelector('.alumno-check');
        if (event.target !== checkbox) {
            checkbox.checked = !checkbox.checked;
        }
    }

    // Botón para activar/desactivar todos los checks de golpe
    let todosMarcados = false;
    function toggleMarcarTodos() {
        const checkboxes = document.querySelectorAll('.alumno-check');
        todosMarcados = !todosMarcados;
        checkboxes.forEach(cb => cb.checked = todosMarcados);
    }
</script>
@endsection
@extends('cpanel/plantillaadmin')
@section('title', 'Control de Matrícula - Alumnos')
@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-6 space-y-6">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-3xs flex items-center gap-4">
            <div class="w-10 h-10 bg-rose-50 text-[#841B44] rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round">school</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Matrícula Total</p>
                <p class="text-lg font-black text-slate-800">{{ $alumnos->total() }} Estudiantes</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-xs border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
        
        <form action="{{ route('AdAlumnos.index') }}" method="GET" class="flex flex-wrap items-center gap-3 text-xs w-full md:w-auto">
            
            <div class="relative w-full sm:w-80">
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-9 pr-4 py-2 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden placeholder:text-slate-400" 
                       placeholder="Buscar por matrícula, nombre o apellidos...">
                <span class="material-icons-round text-slate-400 text-sm absolute left-3 top-2.5">search</span>
            </div>

            <div>
                <select name="activo" onchange="this.form.submit()" 
                        class="bg-slate-50 border border-slate-300 rounded-xl p-2 font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                    <option value="">Todos los Estatus</option>
                    <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Solo Vigentes / Activos</option>
                    <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Solo Bajas / Inactivos</option>
                </select>
            </div>

            @if(request('buscar') || request('activo'))
                <a href="{{ route('admin.alumnos.index') }}" class="text-[#841B44] hover:underline font-semibold flex items-center gap-0.5">
                    <span class="material-icons-round text-sm">clear</span> Limpiar filtros
                </a>
            @endif
        </form>

        <div class="shrink-0 w-full md:w-auto text-right">
            <a class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#841B44] text-white text-xs font-bold rounded-xl shadow-2xs" href="{{ route('AdAlumnos.create') }}">
                <span class="material-icons-round text-sm">person_add</span> Alta de Alumno
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xs border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                        <th class="p-4 w-32">Matrícula</th>
                        <th class="p-4">Estudiante</th>
                        <th class="p-4">Grupo / Especialidad</th>
                        <th class="p-4">Tutor y Contacto</th>
                        <th class="p-4 text-center w-32">Estatus</th>
                        <th class="p-4 text-center w-24">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs">
                    @forelse($alumnos as $alumno)
                        <tr class="hover:bg-slate-50/40 transition-colors {{ !$alumno->activo ? 'bg-slate-50 opacity-75' : '' }}">
                            
                            <td class="p-4 font-mono font-bold text-slate-900 tracking-wide">
                                {{ $alumno->username }}
                            </td>
                            
                            <td class="p-4">
                                <div class="font-bold text-slate-900">
                                    {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }} {{ $alumno->nombre }}
                                </div>
                            </td>
                            
                            <td class="p-4 space-y-0.5">
                                @if($alumno->semestre)
                                    <div class="font-bold text-slate-700">
                                        {{ $alumno->semestre }}° Semestre "{{ $alumno->grupo }}"
                                    </div>
                                    <div class="text-[10px] text-slate-400 uppercase font-semibold leading-tight">{{ $alumno->especialidad }}</div>
                                @else
                                    <span class="text-slate-400 italic">Sin grupo asignado</span>
                                @endif
                            </td>
                            
                            <td class="p-4 space-y-0.5">
                                <div class="font-medium text-slate-700">{{ $alumno->nombre_tutor }}</div>
                                <div class="text-slate-500 font-mono text-[11px] flex items-center gap-0.5">
                                    <span class="material-icons-round text-slate-400 text-xs">phone</span>
                                    {{ $alumno->telefono_tutor }}
                                </div>
                            </td>
                            
                            <td class="p-4 text-center">
                                @if($alumno->activo)
                                    <span class="inline-flex items-center text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Vigente
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-rose-600 font-semibold bg-rose-50 px-2 py-0.5 rounded-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>Baja
                                    </span>
                                @endif
                            </td>
                            
                            <td class="p-4 text-center">
                                <form action="{{ route('admin.alumnos.toggle-status', $alumno->usuario_id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    @if($alumno->activo)
                                        <button type="submit" class="p-1.5 hover:bg-rose-50 rounded-lg text-rose-600 transition-colors inline-flex items-center cursor-pointer" title="Suspender Acceso">
                                            <span class="material-icons-round text-sm">block</span>
                                        </button>
                                    @else
                                        <button type="submit" class="p-1.5 hover:bg-emerald-50 rounded-lg text-emerald-600 transition-colors inline-flex items-center cursor-pointer" title="Reactivar Cuenta">
                                            <span class="material-icons-round text-sm">check_circle</span>
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400 font-medium">
                                <span class="material-icons-round text-2xl block mb-1">person_search</span>
                                No se encontraron registros que coincidan con los filtros de auditoría establecidos.
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
</main>
@endsection
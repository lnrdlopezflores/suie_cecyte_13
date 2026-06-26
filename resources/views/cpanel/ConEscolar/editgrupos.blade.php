@extends('cpanel/plantillaCE')
@section('title', 'Editar Grupo')
@section('content')
<main class="flex-1 max-w-2xl w-full mx-auto p-4 md:p-6">
    <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-200 space-y-6">
        
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-icons-round text-[#841B44]">published_with_changes</span> Actualizar Parámetros del Grupo
                </h2>
                <p class="text-slate-500 text-xs mt-0.5">Ajusta el semestre, el ciclo escolar o la condición matricular (egreso) de la sección elegida.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 p-3 rounded-xl text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center"><span class="material-icons-round text-sm mr-1">error</span> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('grupos.update', $grupo->id) }}" method="POST" class="space-y-5 text-xs">
            @csrf
            @method('PUT')

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/60 space-y-3">
                <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                    <span class="material-icons-round text-sm">lock</span> Datos Base Fijos
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-400 uppercase mb-1">Letra / Sección</label>
                        <input type="text" class="w-full bg-slate-100 border border-slate-200 rounded-xl p-2.5 font-bold font-mono text-slate-400 cursor-not-allowed select-none" value='Grupo "{{ $grupo->grupo }}"' disabled>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-400 uppercase mb-1">Turno Asignado</label>
                        <input type="text" class="w-full bg-slate-100 border border-slate-200 rounded-xl p-2.5 font-semibold text-slate-400 cursor-not-allowed select-none" value="{{ $grupo->turno }}" disabled>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-400 uppercase mb-1">Especialidad Técnica Vinculada</label>
                    <input type="text" class="w-full bg-slate-100 border border-slate-200 rounded-xl p-2.5 font-medium text-slate-400 cursor-not-allowed select-none" value="{{ $grupo->especialidad }}" disabled>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1.5 px-1">
                    <span class="material-icons-round text-sm text-[#841B44]">edit</span> Parámetros Modificables
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Semestre / Grado</label>
                        <select name="semestre" required 
                                class="w-full bg-white border border-slate-300 rounded-xl p-2.5 font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                            @foreach(['1', '2', '3', '4', '5', '6'] as $sem)
                                <option value="{{ $sem }}" {{ old('semestre', $grupo->semestre) == $sem ? 'selected' : '' }}>
                                    {{ $sem }}° Semestre
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Ciclo Escolar</label>
                        <input type="text" name="ciclo_escolar" value="{{ old('ciclo_escolar', $grupo->ciclo_escolar) }}" required 
                               class="w-full bg-white border border-slate-300 rounded-xl p-2.5 font-mono font-bold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden uppercase" 
                               placeholder="Ej: 2026-A">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Condición Matricular (Estatus de Egreso)</label>
                    <select name="estatus_egreso" required 
                            class="w-full bg-white border border-slate-300 rounded-xl p-2.5 font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                        <option value="Regular" {{ old('estatus_egreso', $grupo->estatus_egreso) == 'Regular' ? 'selected' : '' }}>
                            Grupo Regular (Activo / Cursando)
                        </option>
                        <option value="Egresado" {{ old('estatus_egreso', $grupo->estatus_egreso) == 'Egresado' ? 'selected' : '' }}>
                            Grupo Egresado / Graduado
                        </option>
                    </select>
                    <p class="text-[10px] text-slate-400 mt-1">Cambia a "Egresado" únicamente cuando la sección concluya el 6° semestre para congelar su historial matricular ordinario.</p>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('grupos.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-[#841B44] hover:bg-[#6b1536] text-white font-bold rounded-xl shadow-2xs transition-colors cursor-pointer">
                    Guardar Cambios
                </button>
            </div>
        </form>

    </div>
</main>
@endsection
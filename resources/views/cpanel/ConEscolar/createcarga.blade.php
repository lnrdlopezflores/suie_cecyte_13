@extends('cpanel/plantillaCE')
@section('title', 'Asignar Nueva Carga')
@section('content')
<main class="flex-1 max-w-3xl w-full mx-auto p-4 md:p-6">
    <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-200 space-y-6">
        
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-icons-round text-[#841B44]">playlist_add</span> Distribución de Carga Académica
                </h2>
                <p class="text-slate-500 text-xs mt-0.5">Asigna una asignatura y un grupo escolar a la plantilla docente disponible.</p>
            </div>
            <a href="{{ route('cargas.index') }}" class="text-xs font-bold text-slate-600 hover:text-[#841B44] flex items-center gap-1">
                <span class="material-icons-round text-sm">arrow_back</span> Regresar
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 p-3 rounded-xl text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center"><span class="material-icons-round text-sm mr-1">error</span> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('cargas.store') }}" method="POST" class="space-y-5 text-xs">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                <div>
                    <label class="block font-bold text-slate-700 mb-1">1. Profesor Asignado</label>
                    <select name="docente_id" required 
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                        <option value="" disabled selected>-- Elige un docente --</option>
                        @foreach($docentes as $docente)
                            <option value="{{ $docente->id }}" {{ old('docente_id') == $docente->id ? 'selected' : '' }}>
                                {{ $docente->apellido_paterno }} {{ $docente->nombre }} ({{ $docente->username }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">2. Asignatura / Materia</label>
                    <select name="materia_id" required 
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                        <option value="" disabled selected>-- Elige la materia --</option>
                        @foreach($materias as $materia)
                            <option value="{{ $materia->id }}" {{ old('materia_id') == $materia->id ? 'selected' : '' }}>
                                {{ $materia->nombre }} [{{ $materia->clave }}]
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">3. Grupo Destino</label>
                    <select name="grupo_id" required 
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                        <option value="" disabled selected>-- Elige el grupo --</option>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->id }}" {{ old('grupo_id') == $grupo->id ? 'selected' : '' }}>
                                {{ $grupo->semestre }}° "{{ $grupo->grupo }}" — {{ $grupo->turno }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Aula / Espacio Físico</label>
                    <input type="text" name="aula" value="{{ old('aula') }}" required 
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden placeholder:text-slate-400" 
                           placeholder="Ej: Laboratorio de Cómputo B o Aula F-3">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Distribución de Horarios semanales</label>
                    <input type="text" name="horario" value="{{ old('horario') }}" required 
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-mono font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden placeholder:text-slate-400" 
                           placeholder="Ej: Lun, Mar, Vie (07:00 - 09:00)">
                </div>

            </div>

            <div class="flex items-start space-x-2 bg-amber-50 border border-amber-200 p-3 rounded-xl text-[11px] text-amber-800 leading-relaxed">
                <span class="material-icons-round text-sm mt-0.5">warning</span>
                <p><strong>Cláusula de Unicidad Matricular:</strong> El sistema SUIE cuenta con medidas de seguridad que impiden registrar de forma duplicada la misma asignatura exacta en un mismo salón con el mismo profesor para blindar el flujo de asistencias.</p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('cargas.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-[#841B44] hover:bg-[#6b1536] text-white font-bold rounded-xl shadow-2xs transition-colors cursor-pointer">
                    Establecer Asignación Académica
                </button>
            </div>
        </form>

    </div>
</main>
@endsection
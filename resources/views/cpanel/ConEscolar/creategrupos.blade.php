@extends('cpanel/plantillaCE')
@section('title', 'Crear Nuevo Grupo')
@section('content')
<main class="flex-1 max-w-3xl w-full mx-auto p-4 md:p-6 transition-colors duration-200">
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-xs border border-slate-200 dark:border-slate-800 space-y-6">
        
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="material-icons-round text-[#841B44] dark:text-rose-400">add_box</span> Apertura de Nuevo Grupo
                </h2>
                <p class="text-slate-500 dark:text-slate-400 text-xs mt-0.5">Configura una nueva sección académica y su especialidad técnica para el ciclo escolar.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900 text-rose-800 dark:text-rose-300 p-3.5 rounded-xl text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center"><span class="material-icons-round text-sm mr-1.5 text-rose-600 dark:text-rose-400">error</span> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('grupos.store') }}" method="POST" class="space-y-5 text-xs">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Semestre / Grado *</label>
                    <select name="semestre" required 
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-semibold text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-[#841B44] dark:focus:ring-rose-500 focus:outline-hidden">
                        <option value="" disabled selected>Selecciona...</option>
                        @foreach(['1', '2', '3', '4', '5', '6'] as $sem)
                            <option value="{{ $sem }}" {{ old('semestre') == $sem ? 'selected' : '' }}>{{ $sem }}° Semestre</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Letra del Grupo *</label>
                    <input type="text" name="grupo" value="{{ old('grupo') }}" maxlength="1" required 
                           class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-mono font-bold text-center text-slate-900 dark:text-white focus:ring-1 focus:ring-[#841B44] dark:focus:ring-rose-500 focus:outline-hidden uppercase placeholder:text-slate-400" 
                           placeholder="A">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Ciclo Escolar *</label>
                    <input type="text" name="ciclo_escolar" value="{{ old('ciclo_escolar', '2026-A') }}" required 
                           class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-mono font-bold text-slate-900 dark:text-white focus:ring-1 focus:ring-[#841B44] dark:focus:ring-rose-500 focus:outline-hidden placeholder:text-slate-400 uppercase" 
                           placeholder="Ej: 2026-A">
                </div>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Turno Asignado *</label>
                    <select name="turno" required 
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-semibold text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-[#841B44] dark:focus:ring-rose-500 focus:outline-hidden">
                        <option value="Matutino" {{ old('turno') == 'Matutino' ? 'selected' : '' }}>Matutino</option>
                        <option value="Vespertino" {{ old('turno') == 'Vespertino' ? 'selected' : '' }}>Vespertino</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Condición Matricular (Estatus) *</label>
                    <select name="estatus_egreso" required 
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-semibold text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-[#841B44] dark:focus:ring-rose-500 focus:outline-hidden">
                        <option value="Regular" {{ old('estatus_egreso') == 'Regular' ? 'selected' : '' }}>Grupo Regular (Activo)</option>
                        <option value="Egresado" {{ old('estatus_egreso') == 'Egresado' ? 'selected' : '' }}>Grupo Egresado / Graduado</option>
                    </select>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Marca "Egresado" para habilitar los módulos de titulación de Control Escolar.</p>
                </div>

            </div>

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Carrera / Especialidad Técnica *</label>
                <input type="text" name="especialidad" value="{{ old('especialidad') }}" required 
                       class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-bold text-slate-900 dark:text-white focus:ring-1 focus:ring-[#841B44] dark:focus:ring-rose-500 focus:outline-hidden placeholder:text-slate-400" 
                       placeholder="Ej: Técnico en Soporte y Mantenimiento de Cómputo">
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                <a href="{{ route('grupos.index') }}" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-xl transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-[#841B44] hover:bg-[#6b1536] dark:bg-rose-700 dark:hover:bg-rose-800 text-white font-bold rounded-xl shadow-2xs transition-colors cursor-pointer">
                    Aperturar Grupo
                </button>
            </div>
        </form>

    </div>
</main>
@endsection
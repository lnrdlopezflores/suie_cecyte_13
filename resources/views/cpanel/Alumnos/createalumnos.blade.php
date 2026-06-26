@extends('cpanel/plantillaadmin')
@section('title', 'Alta de Alumno')
@section('title', 'Alta de Alumno - SUIE')

@section('content')
<main class="flex-1 max-w-2xl w-full mx-auto p-4 md:p-6">
    <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-200 space-y-6">
        
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-icons-round text-[#841B44]">person_add</span> Completar Expediente de Alumno
                </h2>
                <p class="text-slate-500 text-xs mt-0.5">Asocia los datos personales y de tutoría a una cuenta credencial de estudiante vacía.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 p-3 rounded-xl text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center"><span class="material-icons-round text-sm mr-1">error</span> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('AdAlumnos.store') }}" method="POST" class="space-y-5 text-xs">
            @csrf

            <div>
                <label class="block font-bold text-slate-700 mb-1">1. Seleccionar Cuenta de Usuario (Matrícula)</label>
                <select name="usuario_id" required 
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                    <option value="" disabled selected>-- Elige una matrícula sin expediente asignado --</option>
                    @foreach($usuariosDisponibles as $usuario)
                        <option value="{{ $usuario->id }}" {{ old('usuario_id') == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->username }} (Creado el: {{ \Carbon\Carbon::parse($usuario->created_at)->format('d/m/Y') }})
                        </option>
                    @endforeach
                </select>
                <p class="text-slate-400 text-[10px] mt-1 italic">Solo aparecen usuarios con rol de estudiante que no tienen datos en la bitácora matricular.</p>
            </div>

            <div class="border-t border-slate-100 pt-4">
                <p class="font-bold text-slate-900 mb-3 text-sm">2. Datos Personales del Estudiante</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nombre(s)</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" required 
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden"
                               placeholder="Ej: Juan Carlos">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Apellido Paterno</label>
                        <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno') }}" required 
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden"
                               placeholder="Ej: Pérez">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Apellido Materno (Opcional)</label>
                        <input type="text" name="apellido_materno" value="{{ old('apellido_materno') }}" 
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden"
                               placeholder="Ej: López">
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-4">
                <p class="font-bold text-slate-900 mb-3 text-sm">3. Información de Tutoría y Contacto</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nombre Completo del Tutor</label>
                        <input type="text" name="nombre_tutor" value="{{ old('nombre_tutor') }}" required 
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden"
                               placeholder="Ej: María Elena López Ruiz">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Teléfono de Emergencia / Tutor</label>
                        <input type="text" name="telefono_tutor" value="{{ old('telefono_tutor') }}" required 
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-mono focus:ring-1 focus:ring-[#841B44] focus:outline-hidden"
                               placeholder="Ej: 2481234567">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('AdAlumnos.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-[#841B44] hover:bg-[#6b1536] text-white font-bold rounded-xl shadow-2xs transition-colors cursor-pointer">
                    Guardar Expediente
                </button>
            </div>
        </form>

    </div>
</main>
@endsection
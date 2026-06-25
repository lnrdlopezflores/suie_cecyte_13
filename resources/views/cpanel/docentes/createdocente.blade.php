@extends('cpanel/plantillaadmin')
@section('title', 'Registrar Docente')
@section('content')
<main class="flex-1 max-w-3xl w-full mx-auto p-4 md:p-6">
    <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-200 space-y-6">
        
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-icons-round text-[#841B44]">person_add</span> Alta de Personal Docente
                </h2>
                <p class="text-slate-500 text-xs mt-0.5">Vincula los datos personales del maestro con su cuenta de acceso al sistema.</p>
            </div>
            <a href="{{ route('docentes.index') }}" class="text-xs font-bold text-slate-600 hover:text-[#841B44] flex items-center gap-1">
                <span class="material-icons-round text-sm">arrow_back</span> Regresar al catálogo
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 p-3 rounded-xl text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center"><span class="material-icons-round text-sm mr-1">error</span> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('docentes.store') }}" method="POST" class="space-y-5 text-xs">
            @csrf

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/60 space-y-3">
                <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                    <span class="material-icons-round text-sm text-[#841B44]">vpn_key</span> 1. Cuenta de Acceso Requerida
                </h3>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Seleccionar Usuario (Clave / Nómina disponible)</label>
                    <select name="usuario_id" required 
                            class="w-full bg-white border border-slate-300 rounded-xl p-2.5 font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                        <option value="" disabled selected>-- Elige un usuario con rol Docente disponible --</option>
                        @foreach($usuariosDisponibles as $user)
                            <option value="{{ $user->id }}" {{ old('usuario_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->username }} (Creado el: {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-slate-400 text-[10px] mt-1">Si la clave del maestro no aparece, asegúrate de haber creado primero su acceso con rol "Docente" en el Control de Usuarios.</p>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1.5 px-1">
                    <span class="material-icons-round text-sm text-[#841B44]">assignment_ind</span> 2. Información del Docente
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Correo Electrónico Institutional / Personal</label>
                        <input type="email" name="correo" value="{{ old('correo') }}" 
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden" 
                               placeholder="ejemplo@correo.com">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Teléfono de Contacto (Opcional)</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}" 
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden" 
                               placeholder="Ej: 2481234567">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('docentes.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-[#841B44] hover:bg-[#6b1536] text-white font-bold rounded-xl shadow-2xs transition-colors cursor-pointer">
                    Guardar Registro Docente
                </button>
            </div>
        </form>
    </div>
</main>
@endsection

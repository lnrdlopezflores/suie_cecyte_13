@extends('cpanel/plantillaadmin')

@section('title', 'Control de Usuarios')

@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <section class="lg:col-span-1">
        <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-200 space-y-6">
            <div>
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-icons-round text-[#841B44]">person_add</span> Registro de Usuario
                </h2>
                <p class="text-slate-500 text-xs mt-0.5">Añade credenciales de acceso para personal o alumnado.</p>
            </div>

            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 p-3 rounded-xl text-xs space-y-1">
                    @foreach ($errors->all() as $error)
                        <p class="flex items-center"><span class="material-icons-round text-sm mr-1">error</span> {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('usuarios.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Matrícula o Clave de Empleado (Username)</label>
                    <input type="text" name="username" value="{{ old('username') }}" required 
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden" 
                           placeholder="Ej: 22240105 o ADM-102">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Contraseña Provisional</label>
                    <input type="password" name="password" required 
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden" 
                           placeholder="••••••••">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Rol en el Sistema</label>
                    <select name="rol" required 
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                        <option value="" disabled selected>Selecciona un rol...</option>
                        <option value="Estudiante" {{ old('rol') == 'Estudiante' ? 'selected' : '' }}>Estudiante</option>
                        <option value="Docente" {{ old('rol') == 'Docente' ? 'selected' : '' }}>Docente</option>
                        <option value="Orientador" {{ old('rol') == 'Orientador' ? 'selected' : '' }}>Orientador Educativo</option>
                        <option value="Control Escolar" {{ old('rol') == 'Control Escolar' ? 'selected' : '' }}>Control Escolar</option>
                        <option value="coordinador" {{ old('rol') == 'coordinador' ? 'selected' : '' }}>Coordinador General</option>
                        <option value="administrador" {{ old('rol') == 'administrador' ? 'selected' : '' }}>Administrador del Sistema</option>
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-[#841B44] hover:bg-[#6b1536] text-white font-bold text-xs rounded-xl shadow-2xs transition-colors cursor-pointer">
                        Dar de Alta Usuario
                    </button>
                </div>
            </form>
        </div>
    </section>

    <section class="lg:col-span-2 space-y-4">
        
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-xs flex items-center gap-2 shadow-3xs">
                <span class="material-icons-round text-base">check_circle</span> {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-xs border border-slate-200 overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Bitácora de Usuarios</h3>
                    <p class="text-[11px] text-slate-500">Últimos accesos creados en la plataforma.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                            <th class="p-4">Identificador (Username)</th>
                            <th class="p-4">Rol Asignado</th>
                            <th class="p-4 text-center">Estatus</th>
                            <th class="p-4">Fecha Alta</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-xs">
                        @forelse($usuarios as $user)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="p-4 font-mono font-bold text-slate-900">{{ $user->username }}</td>
                                <td class="p-4">
                                    <span class="px-2 py-0.5 rounded-sm text-[10px] font-semibold border 
                                        {{ $user->rol == 'Estudiante' ? 'bg-indigo-50 text-indigo-700 border-indigo-100' : '' }}
                                        {{ $user->rol == 'Docente' ? 'bg-amber-50 text-amber-700 border-amber-100' : '' }}
                                        {{ $user->rol == 'administrador' ? 'bg-red-50 text-red-700 border-red-100' : '' }}
                                        {{ !in_array($user->rol, ['Estudiante','Docente','administrador']) ? 'bg-slate-100 text-slate-700 border-slate-200' : '' }}
                                    ">
                                        {{ $user->rol }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    @if($user->activo)
                                        <span class="inline-flex items-center text-emerald-600 font-medium"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Activo</span>
                                    @else
                                        <span class="inline-flex items-center text-slate-400"><span class="w-1.5 h-1.5 rounded-full bg-slate-300 mr-1.5"></span>Suspendido</span>
                                    @endif
                                </td>
                                <td class="p-4 text-slate-500 font-mono text-[11px]">{{ $user->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-slate-400 font-medium">No hay usuarios registrados en el sistema.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($usuarios->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                    {{ $usuarios->links() }}
                </div>
            @endif
        </div>
    </section>
</main>
@endsection

@extends('cpanel/plantillaadmin')
@section('title', 'Usuarios')
@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-6 space-y-6">

    <div class="bg-white p-4 rounded-xl shadow-xs border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
        
        <form action="{{ route('usuarios.index') }}" method="GET" class="flex flex-wrap items-center gap-3 text-xs w-full md:w-auto">
            <div class="relative w-full sm:w-64">
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-9 pr-4 py-2 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden placeholder:text-slate-400" 
                       placeholder="Buscar por matrícula o usuario...">
                <span class="material-icons-round text-slate-400 text-sm absolute left-3 top-2.5">search</span>
            </div>

            <div>
                <select name="rol" onchange="this.form.submit()" 
                        class="bg-slate-50 border border-slate-300 rounded-xl p-2 font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                    <option value="">Todos los Roles</option>
                    <option value="Estudiante" {{ request('rol') == 'Estudiante' ? 'selected' : '' }}>Estudiante</option>
                    <option value="Docente" {{ request('rol') == 'Docente' ? 'selected' : '' }}>Docente</option>
                    <option value="Orientador" {{ request('rol') == 'Orientador' ? 'selected' : '' }}>Orientador</option>
                    <option value="Control Escolar" {{ request('rol') == 'Control Escolar' ? 'selected' : '' }}>Control Escolar</option>
                    <option value="coordinador" {{ request('rol') == 'coordinador' ? 'selected' : '' }}>Coordinador</option>
                    <option value="administrador" {{ request('rol') == 'administrador' ? 'selected' : '' }}>Administrador</option>
                </select>
            </div>

            @if(request('buscar') || request('rol'))
                <a href="{{ route('usuarios.index') }}" class="text-[#841B44] hover:underline font-semibold flex items-center gap-0.5">
                    <span class="material-icons-round text-sm">clear</span> Limpiar filtros
                </a>
            @endif
        </form>

        <div class="shrink-0 w-full md:w-auto text-right">
            <a href="{{ route('usuarios.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#841B44] hover:bg-[#6b1536] text-white text-xs font-bold rounded-xl shadow-2xs transition-all">
                <span class="material-icons-round text-sm">person_add</span> Nuevo Usuario
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-xs flex items-center gap-2 shadow-3xs">
            <span class="material-icons-round text-base">check_circle</span> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-xs border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                        <th class="p-4 w-12 text-center">ID</th>
                        <th class="p-4">Identificador (Username)</th>
                        <th class="p-4">Rol Asignado</th>
                        <th class="p-4 text-center">Estatus</th>
                        <th class="p-4">Creado el</th>
                        <th class="p-4 text-right">Acciones de Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs">
                    @forelse($usuarios as $user)
                        <tr class="hover:bg-slate-50/40 transition-colors {{ !$user->activo ? 'bg-slate-50/50 opacity-70' : '' }}">
                            <td class="p-4 text-center font-mono text-slate-400">{{ $user->id }}</td>
                            <td class="p-4 font-mono font-bold text-slate-900 flex items-center gap-2">
                                <span class="material-icons-round text-slate-400 text-sm">account_circle</span>
                                {{ $user->username }}
                            </td>
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
                                    <span class="inline-flex items-center text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-rose-600 font-semibold bg-rose-50 px-2 py-0.5 rounded-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>Suspendido
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-slate-500 font-mono text-[11px]">{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') }}</td>
                            <td class="p-4 text-right">
                                <form action="{{ route('usuarios.toggle-status', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    
                                    @if($user->activo)
                                        <button type="submit" class="px-2.5 py-1.5 text-[11px] bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-lg transition-colors inline-flex items-center gap-1 cursor-pointer" title="Suspender acceso">
                                            <span class="material-icons-round text-sm">block</span> Suspender
                                        </button>
                                    @else
                                        <button type="submit" class="px-2.5 py-1.5 text-[11px] bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold rounded-lg transition-colors inline-flex items-center gap-1 cursor-pointer" title="Reactivar acceso">
                                            <span class="material-icons-round text-sm">check_circle</span> Activar
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400 font-medium">
                                <span class="material-icons-round text-2xl block mb-1">person_search</span>
                                No se encontraron usuarios que coincidan con la búsqueda o rol seleccionado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($usuarios->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                {{ $usuarios->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</main>
@endsection
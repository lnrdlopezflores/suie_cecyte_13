@extends('cpanel/plantillaadmin')
@section('title', 'Usuarios')
@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-6 space-y-6">

    <!-- BARRA DE BÚSQUEDA Y FILTROS -->
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

    <!-- MENSAJES DE ALERTA DE SESIÓN Y ERRORES DE VALIDACIÓN -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-xs flex items-center justify-between shadow-3xs">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-base">check_circle</span>
                <p class="font-bold">{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 cursor-pointer">
                <span class="material-icons-round text-sm">close</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl text-xs space-y-1">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-sm">error</span>
                <p class="font-bold">Ocurrieron errores en la operación:</p>
            </div>
            <ul class="list-disc pl-8 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- TABLA PRINCIPAL DE USUARIOS -->
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
                        <th class="p-4 text-right">Acciones</th>
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
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Botón Editar Usuario (Abre Modal) -->
                                    <button type="button" 
                                            onclick="abrirModalEditar('{{ $user->id }}', '{{ $user->username }}', '{{ $user->rol }}', '{{ $user->activo }}')" 
                                            class="p-1.5 bg-slate-100 hover:bg-[#841B44] text-slate-600 hover:text-white rounded-lg transition-colors cursor-pointer" 
                                            title="Editar cuenta de usuario">
                                        <span class="material-icons-round text-sm">edit</span>
                                    </button>

                                    <!-- Botón Cambiar Estatus Rápido (Toggle) -->
                                    <form action="{{ route('usuarios.toggle-status', $user->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        
                                        @if($user->activo)
                                            <button type="submit" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg transition-colors cursor-pointer" title="Suspender acceso">
                                                <span class="material-icons-round text-sm">block</span>
                                            </button>
                                        @else
                                            <button type="submit" class="p-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg transition-colors cursor-pointer" title="Reactivar acceso">
                                                <span class="material-icons-round text-sm">check_circle</span>
                                            </button>
                                        @endif
                                    </form>
                                </div>
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

<!-- MODAL PARA EDITAR USUARIO -->
<div id="modalEditarUsuario" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-200">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden transform scale-95 transition-transform duration-200" id="boxModalEditar">
        
        <!-- Encabezado Modal -->
        <div class="bg-slate-50 border-b border-slate-200 px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2 text-[#841B44]">
                <span class="material-icons-round text-lg">manage_accounts</span>
                <h3 class="font-bold text-slate-900 text-sm">Modificar Usuario</h3>
            </div>
            <button onclick="cerrarModalEditar()" class="text-slate-400 hover:text-slate-600 cursor-pointer focus:outline-hidden">
                <span class="material-icons-round text-sm">close</span>
            </button>
        </div>

        <!-- Formulario de Edición -->
        <form id="formEditarUsuario" action="" method="POST" class="p-6 space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-bold text-slate-700 mb-1">Identificador Institucional (Username)</label>
                <input type="text" id="edit_username" disabled 
                       class="w-full bg-slate-100 border border-slate-200 text-slate-500 font-mono font-bold rounded-xl p-2.5 cursor-not-allowed">
                <p class="text-[10px] text-slate-400 mt-1">El identificador único no se puede cambiar por seguridad.</p>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Rol Asignado *</label>
                <select name="rol" id="edit_rol" required 
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                    <option value="Estudiante">Estudiante</option>
                    <option value="Docente">Docente</option>
                    <option value="Orientador">Orientador Educativo</option>
                    <option value="Control Escolar">Control Escolar</option>
                    <option value="coordinador">Coordinador General</option>
                    <option value="administrador">Administrador del Sistema</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Nueva Contraseña (Opcional)</label>
                <input type="password" name="password" 
                       class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden" 
                       placeholder="Dejar en blanco para conservar la actual">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Estatus de la Cuenta *</label>
                <select name="activo" id="edit_activo" required 
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                    <option value="1">Activo / Permitir Acceso</option>
                    <option value="0">Suspendido / Bloquear Acceso</option>
                </select>
            </div>

            <!-- Botones de Acción -->
            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <button type="button" onclick="cerrarModalEditar()" 
                        class="px-4 py-2 border border-slate-300 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-colors cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-[#841B44] hover:bg-[#6b1536] text-white font-bold rounded-xl shadow-2xs transition-colors cursor-pointer">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModalEditar(id, username, rol, activo) {
        const modal = document.getElementById('modalEditarUsuario');
        const box = document.getElementById('boxModalEditar');
        const form = document.getElementById('formEditarUsuario');

        // Asignar dinámicamente la URL PUT del usuario
        form.action = `/admon/usuarios/${id}`;

        // Cargar los valores actuales del usuario seleccionado
        document.getElementById('edit_username').value = username;
        document.getElementById('edit_rol').value = rol;
        document.getElementById('edit_activo').value = activo ? '1' : '0';

        // Mostrar el modal
        modal.classList.remove('opacity-0', 'pointer-events-none');
        box.classList.remove('scale-95');
        box.classList.add('scale-100');
    }

    function cerrarModalEditar() {
        const modal = document.getElementById('modalEditarUsuario');
        const box = document.getElementById('boxModalEditar');

        modal.classList.add('opacity-0', 'pointer-events-none');
        box.classList.remove('scale-100');
        box.classList.add('scale-95');
    }
</script>
@endsection
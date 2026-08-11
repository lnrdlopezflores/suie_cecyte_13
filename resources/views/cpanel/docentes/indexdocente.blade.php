@extends('cpanel/plantillaadmin')
@section('title', 'Plantilla Docente')
@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-6 space-y-6">

    <!-- Mensajes de Alerta -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-xs font-bold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-sm">check_circle</span>
                <p>{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 cursor-pointer">
                <span class="material-icons-round text-sm">close</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl text-xs font-bold space-y-1">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-sm">error</span>
                <p>Por favor corrige los siguientes errores:</p>
            </div>
            <ul class="list-disc pl-8 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Barra de Búsqueda y Botón de Alta -->
    <div class="bg-white p-4 rounded-xl shadow-xs border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('docentes.index') }}" method="GET" class="flex flex-wrap items-center gap-3 text-xs w-full md:w-auto">
            <div class="relative w-full sm:w-80">
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-9 pr-4 py-2 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden placeholder:text-slate-400" 
                       placeholder="Buscar por nombre, apellidos o clave...">
                <span class="material-icons-round text-slate-400 text-sm absolute left-3 top-2.5">search</span>
            </div>

            @if(request('buscar'))
                <a href="{{ route('docentes.index') }}" class="text-[#841B44] hover:underline font-semibold flex items-center gap-0.5">
                    <span class="material-icons-round text-sm">clear</span> Limpiar búsqueda
                </a>
            @endif
        </form>

        <div class="shrink-0 w-full md:w-auto text-right">
            <a href="{{ route('docentes.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#841B44] hover:bg-[#631433] text-white text-xs font-bold rounded-xl shadow-2xs transition-colors cursor-pointer">
                <span class="material-icons-round text-sm">badge</span> Alta de Docente
            </a>
        </div>
    </div>

    <!-- Tabla Principal de Docentes -->
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                        <th class="p-4 w-16 text-center">No. Emp.</th>
                        <th class="p-4">Nombre Completo</th>
                        <th class="p-4">Clave de Acceso</th>
                        <th class="p-4">Contacto</th>
                        <th class="p-4 text-center">Estado del Usuario</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs">
                    @forelse($docentes as $docente)
                        <tr class="hover:bg-slate-50/40 transition-colors {{ !$docente->activo ? 'bg-slate-50/50 opacity-70' : '' }}">
                            <td class="p-4 text-center font-mono text-slate-500 font-bold">#{{ $docente->docente_id }}</td>
                            
                            <td class="p-4">
                                <div class="font-bold text-slate-900">
                                    {{ $docente->apellido_paterno }} {{ $docente->apellido_materno }} {{ $docente->nombre }}
                                </div>
                            </td>
                            
                            <td class="p-4 font-mono text-[#841B44] font-semibold">
                                {{ $docente->username }}
                            </td>
                            
                            <td class="p-4 space-y-0.5">
                                @if($docente->correo)
                                    <div class="flex items-center text-slate-600 gap-1">
                                        <span class="material-icons-round text-slate-400 text-xs">mail</span>
                                        {{ $docente->correo }}
                                    </div>
                                @endif
                                @if($docente->telefono)
                                    <div class="flex items-center text-slate-500 gap-1 text-[11px]">
                                        <span class="material-icons-round text-slate-400 text-xs">phone</span>
                                        {{ $docente->telefono }}
                                    </div>
                                @endif
                                @if(!$docente->correo && !$docente->telefono)
                                    <span class="text-slate-400 italic">Sin datos de contacto</span>
                                @endif
                            </td>
                            
                            <td class="p-4 text-center">
                                @if($docente->activo)
                                    <span class="inline-flex items-center text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Vigente
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-rose-600 font-semibold bg-rose-50 px-2 py-0.5 rounded-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>Usuario Suspendido
                                    </span>
                                @endif
                            </td>
                            
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <!-- Botón Editar Docente -->
                                    <button onclick="abrirModalEdicion('{{ $docente->docente_id }}', '{{ str_replace(' (Plain)', '', $docente->nombre) }}', '{{ $docente->apellido_paterno }}', '{{ $docente->apellido_materno }}', '{{ $docente->correo }}', '{{ $docente->telefono }}', '{{ $docente->activo }}')" 
                                            class="p-1.5 hover:bg-slate-100 rounded-lg text-slate-500 hover:text-[#841B44] transition-colors inline-flex items-center cursor-pointer" 
                                            title="Editar información del docente">
                                        <span class="material-icons-round text-sm">edit</span>
                                    </button>

                                    <!-- Ver Carga Académica -->
                                    <a href="{{ route('cargas.index', ['buscar' => $docente->username]) }}" 
                                       class="p-1.5 hover:bg-slate-100 rounded-lg text-slate-500 hover:text-slate-900 transition-colors inline-flex items-center" 
                                       title="Ver Carga Académica">
                                        <span class="material-icons-round text-sm">auto_stories</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400 font-medium">
                                <span class="material-icons-round text-2xl block mb-1">badge</span>
                                No hay docentes registrados o no coinciden con los términos de búsqueda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($docentes->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                {{ $docentes->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</main>

<!-- MODAL DE EDICIÓN DE DOCENTE -->
<div id="modalEditarDocente" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-200">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl overflow-hidden transform scale-95 transition-transform duration-200" id="boxModalEditar">
        
        <!-- Encabezado Modal -->
        <div class="bg-slate-50 border-b border-slate-200 px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2 text-[#841B44]">
                <span class="material-icons-round text-lg">edit</span>
                <h3 class="font-bold text-slate-900 text-sm">Editar Información del Docente</h3>
            </div>
            <button onclick="cerrarModalEdicion()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                <span class="material-icons-round text-sm">close</span>
            </button>
        </div>

        <!-- Formulario -->
        <form id="formEditarDocente" action="" method="POST" class="p-6 space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nombre(s) *</label>
                    <input type="text" name="nombre" id="edit_nombre" required
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Apellido Paterno *</label>
                    <input type="text" name="apellido_paterno" id="edit_apellido_paterno" required
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Apellido Materno</label>
                    <input type="text" name="apellido_materno" id="edit_apellido_materno"
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Estatus del Usuario *</label>
                    <select name="activo" id="edit_activo" required
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                        <option value="1">Vigente / Activo</option>
                        <option value="0">Suspendido / Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Correo Electrónico</label>
                    <input type="email" name="correo" id="edit_correo"
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden"
                           placeholder="ejemplo@cecyte.edu.mx">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Teléfono Móvil</label>
                    <input type="text" name="telefono" id="edit_telefono"
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden"
                           placeholder="10 dígitos">
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <button type="button" onclick="cerrarModalEdicion()" 
                        class="px-4 py-2 border border-slate-300 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-colors cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-[#841B44] hover:bg-[#631433] text-white font-bold rounded-xl shadow-2xs transition-colors cursor-pointer">
                    Actualizar Docente
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModalEdicion(id, nombre, paterno, materno, correo, telefono, activo) {
        const modal = document.getElementById('modalEditarDocente');
        const box = document.getElementById('boxModalEditar');
        const form = document.getElementById('formEditarDocente');

        // Asignamos la ruta dinámica para la petición PUT
        form.action = `/admon/docentes/${id}`;

        // Llenamos los inputs del modal con la información actual del registro
        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_apellido_paterno').value = paterno;
        document.getElementById('edit_apellido_materno').value = materno ?? '';
        document.getElementById('edit_correo').value = correo ?? '';
        document.getElementById('edit_telefono').value = telefono ?? '';
        document.getElementById('edit_activo').value = activo ? '1' : '0';

        // Mostramos el modal
        modal.classList.remove('opacity-0', 'pointer-events-none');
        box.classList.remove('scale-95');
        box.classList.add('scale-100');
    }

    function cerrarModalEdicion() {
        const modal = document.getElementById('modalEditarDocente');
        const box = document.getElementById('boxModalEditar');

        modal.classList.add('opacity-0', 'pointer-events-none');
        box.classList.remove('scale-100');
        box.classList.add('scale-95');
    }
</script>
@endsection
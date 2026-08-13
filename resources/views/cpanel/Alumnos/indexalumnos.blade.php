@extends('cpanel/plantillaadmin')
@section('title', 'Control de Matrícula - Alumnos')
@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-6 space-y-6">

    <!-- Tarjetas de Métricas -->
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

    <!-- Barra de Búsqueda y Filtros -->
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
                <a href="{{ route('AdAlumnos.index') }}" class="text-[#841B44] hover:underline font-semibold flex items-center gap-0.5">
                    <span class="material-icons-round text-sm">clear</span> Limpiar filtros
                </a>
            @endif
        </form>

        <div class="shrink-0 w-full md:w-auto text-right">
            <a class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#841B44] hover:bg-[#631433] text-white text-xs font-bold rounded-xl shadow-2xs transition-colors" href="{{ route('AdAlumnos.create') }}">
                <span class="material-icons-round text-sm">person_add</span> Alta de Alumno
            </a>
        </div>
    </div>

    <!-- Tabla Principal de Alumnos -->
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
                        <th class="p-4 text-center w-28">Acciones</th>
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
                                <div class="flex items-center justify-center gap-1">
                                    <!-- Botón Editar Alumno -->
                                    <button onclick="abrirModalEdicion('{{ $alumno->alumno_id }}', '{{ addslashes(str_replace(' (Plain)', '', $alumno->nombre)) }}', '{{ addslashes($alumno->apellido_paterno) }}', '{{ addslashes($alumno->apellido_materno) }}', '{{ addslashes($alumno->nombre_tutor) }}', '{{ addslashes($alumno->telefono_tutor) }}')" 
                                            class="p-1.5 hover:bg-slate-100 rounded-lg text-slate-500 hover:text-[#841B44] transition-colors inline-flex items-center cursor-pointer" 
                                            title="Editar Información">
                                        <span class="material-icons-round text-sm">edit</span>
                                    </button>

                                    <!-- Botón Suspender/Activar -->
                                    <form action="{{ route('admin.alumnos.toggle-status', $alumno->usuario_id) }}" method="POST" class="inline-block">
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
                                </div>
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

<!-- MODAL DE EDICIÓN DE ALUMNO -->
<div id="modalEditarAlumno" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-200">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl overflow-hidden transform scale-95 transition-transform duration-200" id="boxModalEditar">
        
        <!-- Encabezado Modal -->
        <div class="bg-slate-50 border-b border-slate-200 px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2 text-[#841B44]">
                <span class="material-icons-round text-lg">edit_note</span>
                <h3 class="font-bold text-slate-900 text-sm">Editar Información del Alumno</h3>
            </div>
            <button onclick="cerrarModalEdicion()" class="text-slate-400 hover:text-slate-600 cursor-pointer focus:outline-hidden">
                <span class="material-icons-round text-sm">close</span>
            </button>
        </div>

        <!-- Formulario de Edición -->
        <form id="formEditarAlumno" action="" method="POST" class="p-6 space-y-4 text-xs">
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
            </div>

            <hr class="border-slate-100 my-2">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nombre del Tutor *</label>
                    <input type="text" name="nombre_tutor" id="edit_nombre_tutor" required
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Teléfono del Tutor *</label>
                    <input type="text" name="telefono_tutor" id="edit_telefono_tutor" required
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden"
                           placeholder="Ej: 2481234567">
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
                    Actualizar Alumno
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModalEdicion(id, nombre, paterno, materno, tutor, telefono) {
        const modal = document.getElementById('modalEditarAlumno');
        const box = document.getElementById('boxModalEditar');
        const form = document.getElementById('formEditarAlumno');

        // Asignamos la ruta dinámica para la petición PUT (Asegúrate de que esta ruta coincida con tu web.php)
        form.action = `/admon/alumnos/${id}`;

        // Llenamos los inputs del modal con la información limpia
        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_apellido_paterno').value = paterno;
        document.getElementById('edit_apellido_materno').value = materno ?? '';
        document.getElementById('edit_nombre_tutor').value = tutor ?? '';
        document.getElementById('edit_telefono_tutor').value = telefono ?? '';

        // Mostramos el modal
        modal.classList.remove('opacity-0', 'pointer-events-none');
        box.classList.remove('scale-95');
        box.classList.add('scale-100');
    }

    function cerrarModalEdicion() {
        const modal = document.getElementById('modalEditarAlumno');
        const box = document.getElementById('boxModalEditar');

        modal.classList.add('opacity-0', 'pointer-events-none');
        box.classList.remove('scale-100');
        box.classList.add('scale-95');
    }
</script>
@endsection
@extends('cpanel/plantillaadmin')
@section('title', 'Control de Matrícula - Alumnos')
@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-6 space-y-6 transition-colors duration-200">

    <!-- Tarjetas de Métricas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-3xs flex items-center gap-4 transition-colors">
            <div class="w-12 h-12 bg-custom-light text-custom-primary rounded-2xl flex items-center justify-center shrink-0 border border-custom-primary/20 shadow-2xs">
                <span class="material-icons-round text-2xl">school</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">Matrícula Total</p>
                <p class="text-xl font-black text-slate-900 dark:text-slate-100">{{ $alumnos->total() }} Estudiantes</p>
            </div>
        </div>
    </div>

    <!-- Mensajes de Alerta -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 p-4 rounded-2xl text-xs font-bold flex items-center justify-between shadow-3xs">
            <div class="flex items-center gap-2.5">
                <span class="material-icons-round text-lg text-emerald-600 dark:text-emerald-400">check_circle</span>
                <p>{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-100 cursor-pointer">
                <span class="material-icons-round text-sm">close</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900 text-rose-800 dark:text-rose-200 p-4 rounded-2xl text-xs font-bold space-y-1.5 shadow-3xs">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-lg text-rose-600 dark:text-rose-400">error</span>
                <p>Por favor corrige los siguientes errores:</p>
            </div>
            <ul class="list-disc pl-8 font-medium space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Barra de Búsqueda y Filtros -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl shadow-xs border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4 transition-colors">
        
        <form action="{{ route('AdAlumnos.index') }}" method="GET" class="flex flex-wrap items-center gap-3 text-xs w-full md:w-auto">
            
            <div class="relative w-full sm:w-80">
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl pl-9 pr-4 py-2.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden placeholder:text-slate-400 dark:placeholder:text-slate-500 transition-all" 
                       placeholder="Buscar por matrícula, nombre o apellidos...">
                <span class="material-icons-round text-slate-400 dark:text-slate-500 text-sm absolute left-3 top-3">search</span>
            </div>

            <div>
                <select name="activo" onchange="this.form.submit()" 
                        class="bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-semibold focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all">
                    <option value="">Todos los Estatus</option>
                    <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Solo Vigentes / Activos</option>
                    <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Solo Bajas / Inactivos</option>
                </select>
            </div>

            @if(request('buscar') || request('activo') !== null && request('activo') !== '')
                <a href="{{ route('AdAlumnos.index') }}" class="text-custom-primary dark:text-rose-400 hover:underline font-bold flex items-center gap-0.5 ml-1">
                    <span class="material-icons-round text-sm">clear</span> Limpiar filtros
                </a>
            @endif
        </form>
    </div>

    <!-- Tabla Principal de Alumnos -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xs border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-400 text-[10px] font-black uppercase tracking-wider">
                        <th class="p-4 w-32">Matrícula</th>
                        <th class="p-4">Estudiante</th>
                        <th class="p-4">Grupo / Especialidad</th>
                        <th class="p-4">Tutor y Contacto</th>
                        <th class="p-4 text-center w-32">Estatus</th>
                        <th class="p-4 text-center w-28">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                    @forelse($alumnos as $alumno)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors {{ !$alumno->activo ? 'bg-slate-50/60 dark:bg-slate-800/20 opacity-75' : '' }}">
                            
                            <td class="p-4 font-mono font-bold text-slate-900 dark:text-slate-100 tracking-wide">
                                {{ $alumno->username }}
                            </td>
                            
                            <td class="p-4">
                                <div class="font-bold text-slate-900 dark:text-slate-100">
                                    {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }} {{ $alumno->nombre }}
                                </div>
                            </td>
                            
                            <td class="p-4 space-y-0.5">
                                @if($alumno->semestre)
                                    <div class="font-bold text-slate-700 dark:text-slate-200">
                                        {{ $alumno->semestre }}° Semestre "{{ $alumno->grupo }}"
                                    </div>
                                    <div class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-semibold leading-tight">{{ $alumno->especialidad }}</div>
                                @else
                                    <span class="text-slate-400 dark:text-slate-500 italic">Sin grupo asignado</span>
                                @endif
                            </td>
                            
                            <td class="p-4 space-y-0.5">
                                <div class="font-medium text-slate-700 dark:text-slate-300">{{ $alumno->nombre_tutor }}</div>
                                <div class="text-slate-500 dark:text-slate-400 font-mono text-[11px] flex items-center gap-1">
                                    <span class="material-icons-round text-slate-400 dark:text-slate-500 text-xs">phone</span>
                                    {{ $alumno->telefono_tutor }}
                                </div>
                            </td>
                            
                            <td class="p-4 text-center">
                                @if($alumno->activo)
                                    <span class="inline-flex items-center text-emerald-700 dark:text-emerald-300 font-extrabold bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-900/60 px-2.5 py-1 rounded-full text-[10px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Vigente
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-rose-700 dark:text-rose-300 font-extrabold bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900/60 px-2.5 py-1 rounded-full text-[10px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>Baja
                                    </span>
                                @endif
                            </td>
                            
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <!-- Botón Editar Alumno -->
                                    <button onclick="abrirModalEdicion('{{ $alumno->alumno_id }}', '{{ addslashes(str_replace(' (Plain)', '', $alumno->nombre)) }}', '{{ addslashes($alumno->apellido_paterno) }}', '{{ addslashes($alumno->apellido_materno) }}', '{{ addslashes($alumno->nombre_tutor) }}', '{{ addslashes($alumno->telefono_tutor) }}')" 
                                            class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl text-slate-500 dark:text-slate-400 hover:text-custom-primary transition-colors inline-flex items-center cursor-pointer" 
                                            title="Editar Información">
                                        <span class="material-icons-round text-base">edit</span>
                                    </button>

                                    <!-- Botón Suspender/Activar -->
                                    <form action="{{ route('admin.alumnos.toggle-status', $alumno->usuario_id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        @if($alumno->activo)
                                            <button type="submit" class="p-2 hover:bg-rose-50 dark:hover:bg-rose-950/60 rounded-xl text-rose-600 dark:text-rose-400 transition-colors inline-flex items-center cursor-pointer" title="Suspender Acceso">
                                                <span class="material-icons-round text-base">block</span>
                                            </button>
                                        @else
                                            <button type="submit" class="p-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/60 rounded-xl text-emerald-600 dark:text-emerald-400 transition-colors inline-flex items-center cursor-pointer" title="Reactivar Cuenta">
                                                <span class="material-icons-round text-base">check_circle</span>
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400 dark:text-slate-500 font-medium">
                                <span class="material-icons-round text-3xl block mb-1.5 text-slate-300 dark:text-slate-700">person_search</span>
                                No se encontraron registros que coincidan con los filtros de auditoría establecidos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($alumnos->hasPages())
            <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/40 dark:bg-slate-800/40">
                {{ $alumnos->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</main>

<!-- MODAL DE EDICIÓN DE ALUMNO -->
<div id="modalEditarAlumno" class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-200">
    <div class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden transform scale-95 transition-transform duration-200" id="boxModalEditar">
        
        <!-- Encabezado Modal -->
        <div class="bg-slate-50/80 dark:bg-slate-800/60 border-b border-slate-100 dark:border-slate-800 px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2 text-custom-primary">
                <span class="material-icons-round text-xl">edit_note</span>
                <h3 class="font-black text-slate-900 dark:text-slate-100 text-sm md:text-base">Editar Información del Alumno</h3>
            </div>
            <button onclick="cerrarModalEdicion()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer focus:outline-hidden">
                <span class="material-icons-round text-lg">close</span>
            </button>
        </div>

        <!-- Formulario de Edición -->
        <form id="formEditarAlumno" action="" method="POST" class="p-6 space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nombre(s) *</label>
                    <input type="text" name="nombre" id="edit_nombre" required
                           class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Apellido Paterno *</label>
                    <input type="text" name="apellido_paterno" id="edit_apellido_paterno" required
                           class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Apellido Materno</label>
                    <input type="text" name="apellido_materno" id="edit_apellido_materno"
                           class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all">
                </div>
            </div>

            <hr class="border-slate-100 dark:border-slate-800 my-2">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nombre del Tutor *</label>
                    <input type="text" name="nombre_tutor" id="edit_nombre_tutor" required
                           class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Teléfono del Tutor *</label>
                    <input type="text" name="telefono_tutor" id="edit_telefono_tutor" required
                           class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all"
                           placeholder="Ej: 2481234567">
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                <button type="button" onclick="cerrarModalEdicion()" 
                        class="px-4 py-2.5 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold rounded-xl shadow-2xs transition-all cursor-pointer">
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

        form.action = `/admon/alumnos/${id}`;

        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_apellido_paterno').value = paterno;
        document.getElementById('edit_apellido_materno').value = materno ?? '';
        document.getElementById('edit_nombre_tutor').value = tutor ?? '';
        document.getElementById('edit_telefono_tutor').value = telefono ?? '';

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
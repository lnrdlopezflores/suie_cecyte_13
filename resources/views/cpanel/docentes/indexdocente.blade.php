@extends('cpanel/plantillaadmin')
@section('title', 'Plantilla Docente')
@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-6 space-y-6 transition-colors duration-200">

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

    <!-- Barra de Búsqueda -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl shadow-xs border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4 transition-colors">
        <form action="{{ route('docentes.index') }}" method="GET" class="flex flex-wrap items-center gap-3 text-xs w-full md:w-auto">
            <div class="relative w-full sm:w-80">
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl pl-9 pr-4 py-2.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden placeholder:text-slate-400 dark:placeholder:text-slate-500 transition-all" 
                       placeholder="Buscar por nombre, apellidos o clave...">
                <span class="material-icons-round text-slate-400 dark:text-slate-500 text-sm absolute left-3 top-3">search</span>
            </div>

            @if(request('buscar'))
                <a href="{{ route('docentes.index') }}" class="text-custom-primary dark:text-rose-400 hover:underline font-bold flex items-center gap-0.5 ml-1">
                    <span class="material-icons-round text-sm">clear</span> Limpiar búsqueda
                </a>
            @endif
        </form>
    </div>

    <!-- Tabla Principal de Docentes -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xs border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-400 text-[10px] font-black uppercase tracking-wider">
                        <th class="p-4 w-20 text-center">No. Emp.</th>
                        <th class="p-4">Nombre Completo</th>
                        <th class="p-4">Clave de Acceso</th>
                        <th class="p-4">Contacto</th>
                        <th class="p-4 text-center">Estado del Usuario</th>
                        <th class="p-4 text-center w-28">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                    @forelse($docentes as $docente)
                        @php
                            $nombre = $docente->nombre;
                            $paterno = $docente->apellido_paterno;
                            $materno = $docente->apellido_materno;
                            $correo = $docente->correo;
                            $telefono = $docente->telefono;

                            try {
                                if (is_string($nombre) && (str_starts_with($nombre, 'ey') || strlen($nombre) > 50)) {
                                    $nombre = decrypt($nombre);
                                }
                                if (is_string($paterno) && (str_starts_with($paterno, 'ey') || strlen($paterno) > 50)) {
                                    $paterno = decrypt($paterno);
                                }
                                if (is_string($materno) && (str_starts_with($materno, 'ey') || strlen($materno) > 50)) {
                                    $materno = decrypt($materno);
                                }
                                if (is_string($correo) && (str_starts_with($correo, 'ey') || strlen($correo) > 50)) {
                                    $correo = decrypt($correo);
                                }
                                if (is_string($telefono) && (str_starts_with($telefono, 'ey') || strlen($telefono) > 50)) {
                                    $telefono = decrypt($telefono);
                                }
                            } catch (\Throwable $e) {
                                $nombre = str_replace(' (Plain)', '', $nombre);
                                $paterno = str_replace(' (Plain)', '', $paterno);
                                $materno = str_replace(' (Plain)', '', $materno);
                                $correo = str_replace(' (Plain)', '', $correo);
                                $telefono = str_replace(' (Plain)', '', $telefono);
                            }
                        @endphp

                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors {{ !$docente->activo ? 'bg-slate-50/60 dark:bg-slate-800/20 opacity-75' : '' }}">
                            <td class="p-4 text-center font-mono text-slate-500 dark:text-slate-400 font-bold">#{{ $docente->docente_id }}</td>
                            
                            <td class="p-4">
                                <div class="font-bold text-slate-900 dark:text-slate-100">
                                    {{ $paterno }} {{ $materno }} {{ $nombre }}
                                </div>
                            </td>
                            
                            <td class="p-4 font-mono text-custom-primary font-bold">
                                {{ $docente->username }}
                            </td>
                            
                            <td class="p-4 space-y-0.5">
                                @if($correo)
                                    <div class="flex items-center text-slate-600 dark:text-slate-300 gap-1">
                                        <span class="material-icons-round text-slate-400 dark:text-slate-500 text-xs">mail</span>
                                        <span>{{ $correo }}</span>
                                    </div>
                                @endif
                                @if($telefono)
                                    <div class="flex items-center text-slate-500 dark:text-slate-400 gap-1 text-[11px] font-mono">
                                        <span class="material-icons-round text-slate-400 dark:text-slate-500 text-xs">phone</span>
                                        <span>{{ $telefono }}</span>
                                    </div>
                                @endif
                                @if(!$correo && !$telefono)
                                    <span class="text-slate-400 dark:text-slate-500 italic">Sin datos de contacto</span>
                                @endif
                            </td>
                            
                            <td class="p-4 text-center">
                                @if($docente->activo)
                                    <span class="inline-flex items-center text-emerald-700 dark:text-emerald-300 font-extrabold bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-900/60 px-2.5 py-1 rounded-full text-[10px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Vigente
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-rose-700 dark:text-rose-300 font-extrabold bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900/60 px-2.5 py-1 rounded-full text-[10px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>Usuario Suspendido
                                    </span>
                                @endif
                            </td>
                            
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <!-- Botón Editar Docente -->
                                    <button onclick="abrirModalEdicion('{{ $docente->docente_id }}', '{{ addslashes($nombre) }}', '{{ addslashes($paterno) }}', '{{ addslashes($materno) }}', '{{ addslashes($correo) }}', '{{ addslashes($telefono) }}', '{{ $docente->activo }}')" 
                                            class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl text-slate-500 dark:text-slate-400 hover:text-custom-primary transition-colors inline-flex items-center cursor-pointer" 
                                            title="Editar información del docente">
                                        <span class="material-icons-round text-base">edit</span>
                                    </button>

                                    <!-- Ver Carga Académica -->
                                    <a href="{{ route('coordinador.cargas.index', ['buscar' => $docente->username]) }}" 
                                       class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 transition-colors inline-flex items-center" 
                                       title="Ver Carga Académica">
                                        <span class="material-icons-round text-base">auto_stories</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400 dark:text-slate-500 font-medium">
                                <span class="material-icons-round text-3xl block mb-1.5 text-slate-300 dark:text-slate-700">badge</span>
                                No hay docentes registrados o no coinciden con los términos de búsqueda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($docentes->hasPages())
            <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/40 dark:bg-slate-800/40">
                {{ $docentes->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</main>

<!-- MODAL DE EDICIÓN DE DOCENTE -->
<div id="modalEditarDocente" class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-200">
    <div class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden transform scale-95 transition-transform duration-200" id="boxModalEditar">
        
        <!-- Encabezado Modal -->
        <div class="bg-slate-50/80 dark:bg-slate-800/60 border-b border-slate-100 dark:border-slate-800 px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2 text-custom-primary">
                <span class="material-icons-round text-xl">edit</span>
                <h3 class="font-black text-slate-900 dark:text-slate-100 text-sm md:text-base">Editar Información del Docente</h3>
            </div>
            <button onclick="cerrarModalEdicion()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer focus:outline-hidden">
                <span class="material-icons-round text-lg">close</span>
            </button>
        </div>

        <!-- Formulario -->
        <form id="formEditarDocente" action="" method="POST" class="p-6 space-y-4 text-xs">
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

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Estatus del Usuario *</label>
                    <select name="activo" id="edit_activo" required
                            class="w-full bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all">
                        <option value="1">Vigente / Activo</option>
                        <option value="0">Suspendido / Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Correo Electrónico</label>
                    <input type="email" name="correo" id="edit_correo"
                           class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all"
                           placeholder="ejemplo@cecyte.edu.mx">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Teléfono Móvil</label>
                    <input type="text" name="telefono" id="edit_telefono"
                           class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all"
                           placeholder="10 dígitos">
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

        form.action = `/admon/docentes/${id}`;

        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_apellido_paterno').value = paterno;
        document.getElementById('edit_apellido_materno').value = materno ?? '';
        document.getElementById('edit_correo').value = correo ?? '';
        document.getElementById('edit_telefono').value = telefono ?? '';
        document.getElementById('edit_activo').value = (activo === '1' || activo === 1 || activo === true) ? '1' : '0';

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
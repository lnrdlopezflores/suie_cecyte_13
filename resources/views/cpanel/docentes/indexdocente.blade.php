@extends('cpanel/plantillaadmin')
@section('title', 'Plantilla Docente')

@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-8 space-y-8 transition-colors duration-200">

    <!-- ENCABEZADO Y TARJETA DE MÉTRICA PRINCIPAL -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-custom-primary text-xs font-black uppercase tracking-widest mb-1">
                <span class="material-icons-round text-base">badge</span>
                <span>Recursos Académicos</span>
            </div>
            <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-slate-100 flex items-center gap-2.5">
                Personal Docente
            </h2>
        </div>

        <div class="bg-white dark:bg-slate-900 px-6 py-4 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs flex items-center gap-4 shrink-0 transition-colors">
            <div class="w-12 h-12 bg-custom-light text-custom-primary rounded-2xl flex items-center justify-center shrink-0 border border-custom-primary/30 shadow-2xs">
                <span class="material-icons-round text-2xl">co_present</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-extrabold tracking-wider">Docentes Totales</p>
                <p class="text-xl md:text-2xl font-black text-slate-900 dark:text-slate-100">{{ $docentes->total() }} <span class="text-xs font-semibold text-slate-500">Profesores</span></p>
            </div>
        </div>
    </div>

    <!-- MENSAJES DE ALERTA -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 p-4 md:p-5 rounded-2xl text-xs md:text-sm font-semibold flex items-center justify-between shadow-3xs">
            <div class="flex items-center gap-3">
                <span class="material-icons-round text-xl text-emerald-600 dark:text-emerald-400">check_circle</span>
                <p>{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-100 cursor-pointer">
                <span class="material-icons-round text-sm">close</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900 text-rose-800 dark:text-rose-200 p-4 md:p-5 rounded-2xl text-xs font-bold space-y-2 shadow-3xs">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-xl text-rose-600 dark:text-rose-400">error</span>
                <p>Por favor corrige los siguientes errores:</p>
            </div>
            <ul class="list-disc pl-8 font-medium space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- BARRA DE BÚSQUEDA -->
    <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl shadow-xs border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4 transition-colors">
        <form action="{{ route('docentes.index') }}" method="GET" class="flex flex-wrap items-center gap-3.5 text-xs w-full">
            <div class="relative flex-1 min-w-[280px]">
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-2xl pl-10 pr-4 py-3 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden placeholder:text-slate-400 dark:placeholder:text-slate-500 transition-all" 
                       placeholder="Buscar por nombre, apellidos o clave de acceso...">
                <span class="material-icons-round text-slate-400 dark:text-slate-500 text-lg absolute left-3.5 top-3">search</span>
            </div>

            @if(request('buscar'))
                <a href="{{ route('docentes.index') }}" class="text-custom-primary dark:text-rose-400 hover:underline font-extrabold flex items-center gap-1 px-2 py-1">
                    <span class="material-icons-round text-base">clear</span> Limpiar búsqueda
                </a>
            @endif
        </form>
    </div>

    <!-- TABLA PRINCIPAL DE DOCENTES -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xs border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-400 text-[10px] font-black uppercase tracking-wider">
                        <th class="p-4.5 pl-6 text-center w-24">No. Emp.</th>
                        <th class="p-4.5">Docente</th>
                        <th class="p-4.5">Clave de Acceso</th>
                        <th class="p-4.5">Contacto</th>
                        <th class="p-4.5 text-center">Estado</th>
                        <th class="p-4.5 text-center pr-6">Acciones</th>
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
                                if (is_string($nombre) && (str_starts_with($nombre, 'ey') || strlen($nombre) > 50)) $nombre = decrypt($nombre);
                                if (is_string($paterno) && (str_starts_with($paterno, 'ey') || strlen($paterno) > 50)) $paterno = decrypt($paterno);
                                if (is_string($materno) && (str_starts_with($materno, 'ey') || strlen($materno) > 50)) $materno = decrypt($materno);
                                if (is_string($correo) && (str_starts_with($correo, 'ey') || strlen($correo) > 50)) $correo = decrypt($correo);
                                if (is_string($telefono) && (str_starts_with($telefono, 'ey') || strlen($telefono) > 50)) $telefono = decrypt($telefono);
                            } catch (\Throwable $e) {
                                $nombre = str_replace(' (Plain)', '', $nombre);
                                $paterno = str_replace(' (Plain)', '', $paterno);
                                $materno = str_replace(' (Plain)', '', $materno);
                                $correo = str_replace(' (Plain)', '', $correo);
                                $telefono = str_replace(' (Plain)', '', $telefono);
                            }

                            $iniciales = strtoupper(substr($nombre ?: '', 0, 1) . substr($paterno ?: '', 0, 1));
                        @endphp

                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors {{ !$docente->activo ? 'bg-slate-50/60 dark:bg-slate-800/20 opacity-75' : '' }}">
                            
                            <!-- Número de Empleado -->
                            <td class="p-4.5 pl-6 text-center">
                                <span class="font-mono text-slate-500 dark:text-slate-400 font-bold bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded-lg text-xs">
                                    #{{ $docente->docente_id }}
                                </span>
                            </td>
                            
                            <!-- Nombre Completo y Avatar -->
                            <td class="p-4.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-custom-light text-custom-primary font-black flex items-center justify-center text-xs shrink-0 border border-custom-primary/30 shadow-3xs">
                                        {{ $iniciales ?: 'DC' }}
                                    </div>
                                    <div>
                                        <p class="font-black text-slate-900 dark:text-slate-100 text-sm leading-tight">
                                            {{ $paterno }} {{ $materno }} {{ $nombre }}
                                        </p>
                                        <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold mt-0.5">Profesor Titular</p>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Clave de Acceso -->
                            <td class="p-4.5">
                                <span class="font-mono text-custom-primary font-black tracking-wider text-xs">
                                    {{ $docente->username }}
                                </span>
                            </td>
                            
                            <!-- Contacto -->
                            <td class="p-4.5 space-y-1">
                                @if($correo)
                                    <div class="flex items-center text-slate-600 dark:text-slate-300 gap-1.5 font-medium">
                                        <span class="material-icons-round text-slate-400 dark:text-slate-500 text-xs">mail</span>
                                        <span>{{ $correo }}</span>
                                    </div>
                                @endif
                                @if($telefono)
                                    <div class="flex items-center text-slate-500 dark:text-slate-400 gap-1.5 font-mono text-[11px]">
                                        <span class="material-icons-round text-slate-400 dark:text-slate-500 text-xs">phone</span>
                                        <span>{{ $telefono }}</span>
                                    </div>
                                @endif
                                @if(!$correo && !$telefono)
                                    <span class="text-slate-400 dark:text-slate-500 italic text-[11px]">Sin datos registrados</span>
                                @endif
                            </td>
                            
                            <!-- Estado -->
                            <td class="p-4.5 text-center">
                                @if($docente->activo)
                                    <span class="inline-flex items-center text-emerald-700 dark:text-emerald-300 font-black bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-900/60 px-3 py-1 rounded-full text-[10px] uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>Vigente
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-rose-700 dark:text-rose-300 font-black bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900/60 px-3 py-1 rounded-full text-[10px] uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>Inactivo
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Acciones -->
                            <td class="p-4.5 text-center pr-6">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Botón Editar Docente -->
                                    <button onclick="abrirModalEdicion('{{ $docente->docente_id }}', '{{ addslashes($nombre) }}', '{{ addslashes($paterno) }}', '{{ addslashes($materno) }}', '{{ addslashes($correo) }}', '{{ addslashes($telefono) }}', '{{ $docente->activo }}')" 
                                            class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl text-slate-500 dark:text-slate-400 hover:text-custom-primary transition-colors inline-flex items-center cursor-pointer shadow-3xs border border-slate-200 dark:border-slate-700/80" 
                                            title="Editar información del docente">
                                        <span class="material-icons-round text-base">edit</span>
                                    </button>

                                    <!-- Ver Carga Académica -->
                                    <a href="{{ route('coordinador.cargas.index', ['buscar' => $docente->username]) }}" 
                                       class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 transition-colors inline-flex items-center shadow-3xs border border-slate-200 dark:border-slate-700/80" 
                                       title="Ver Carga Académica">
                                        <span class="material-icons-round text-base">auto_stories</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-slate-400 dark:text-slate-500 font-medium space-y-2">
                                <span class="material-icons-round text-4xl block text-slate-300 dark:text-slate-700">badge</span>
                                <p class="text-sm">No hay docentes registrados o no coinciden con los términos de búsqueda.</p>
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
        <div class="bg-slate-50/80 dark:bg-slate-800/60 border-b border-slate-100 dark:border-slate-800 px-6 py-4.5 flex justify-between items-center">
            <div class="flex items-center gap-2.5 text-custom-primary">
                <span class="material-icons-round text-xl">edit</span>
                <h3 class="font-black text-slate-900 dark:text-slate-100 text-sm md:text-base">Editar Información del Docente</h3>
            </div>
            <button onclick="cerrarModalEdicion()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer focus:outline-hidden p-1 rounded-xl">
                <span class="material-icons-round text-xl">close</span>
            </button>
        </div>

        <!-- Formulario -->
        <form id="formEditarDocente" action="" method="POST" class="p-6 md:p-7 space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nombre(s) *</label>
                    <input type="text" name="nombre" id="edit_nombre" required
                           class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Apellido Paterno *</label>
                    <input type="text" name="apellido_paterno" id="edit_apellido_paterno" required
                           class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Apellido Materno</label>
                    <input type="text" name="apellido_materno" id="edit_apellido_materno"
                           class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Estatus del Usuario *</label>
                    <select name="activo" id="edit_activo" required
                            class="w-full bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-bold focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all cursor-pointer">
                        <option value="1">Vigente / Activo</option>
                        <option value="0">Suspendido / Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Correo Electrónico</label>
                    <input type="email" name="correo" id="edit_correo"
                           class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all"
                           placeholder="ejemplo@cecyte.edu.mx">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Teléfono Móvil</label>
                    <input type="text" name="telefono" id="edit_telefono"
                           class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all"
                           placeholder="10 dígitos">
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                <button type="button" onclick="cerrarModalEdicion()" 
                        class="px-5 py-3 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-6 py-3 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold rounded-2xl shadow-md transition-all cursor-pointer">
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

    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('modalEditarDocente');

        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                cerrarModalEdicion();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.classList.contains('pointer-events-none')) {
                cerrarModalEdicion();
            }
        });
    });
</script>
@endsection
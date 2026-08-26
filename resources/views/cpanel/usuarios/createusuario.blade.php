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
                <p class="text-slate-500 text-xs mt-0.5">Añade credenciales y expediente correspondiente.</p>
            </div>

            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 p-3 rounded-xl text-xs space-y-1">
                    @foreach ($errors->all() as $error)
                        <p class="flex items-center"><span class="material-icons-round text-sm mr-1">error</span> {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('usuarios.store') }}" method="POST" class="space-y-4 text-xs" id="formUsuario">
                @csrf
                
                <!-- 1. SELECCIÓN DE ROL (DETONADOR) -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Rol en el Sistema *</label>
                    <select name="rol" id="selectRol" required onchange="cambiarFormularioRol(this.value)"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                        <option value="" disabled selected>Selecciona un rol...</option>
                        <option value="Estudiante" {{ old('rol') == 'Estudiante' ? 'selected' : '' }}>Estudiante</option>
                        <option value="Docente" {{ old('rol') == 'Docente' ? 'selected' : '' }}>Docente</option>
                        <option value="Orientador" {{ old('rol') == 'Orientador' ? 'selected' : '' }}>Orientador</option>
                        <option value="Control Escolar" {{ old('rol') == 'Control Escolar' ? 'selected' : '' }}>Control Escolar</option>
                        <option value="Coordinador" {{ old('rol') == 'Coordinador' ? 'selected' : '' }}>Coordinador</option>
                        <option value="administrador" {{ old('rol') == 'administrador' ? 'selected' : '' }}>Administrador</option>
                    </select>
                </div>

                <!-- 2. CREDENCIALES (TABLA: USUARIOS) -->
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Credenciales de Acceso</p>
                    
                    <div>
                        <label class="block font-bold text-slate-700 mb-1" id="labelUsername">Identificador (Username) *</label>
                        <input type="text" name="username" value="{{ old('username') }}" required 
                               class="w-full bg-white border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44]" 
                               placeholder="Ej: 22240105 o DOC-2401">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Contraseña *</label>
                        <input type="password" name="password" required 
                               class="w-full bg-white border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44]" 
                               placeholder="••••••••">
                    </div>
                </div>

                <!-- 3. DATOS PERSONALES GENERALES -->
                <div id="seccionPersonales" class="space-y-3 hidden">
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Datos Personales</p>
                    
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nombre(s) *</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" id="inputNombre"
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44]">
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Apellido Paterno *</label>
                            <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno') }}" id="inputPaterno"
                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44]">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Apellido Materno</label>
                            <input type="text" name="apellido_materno" value="{{ old('apellido_materno') }}"
                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44]">
                        </div>
                    </div>
                </div>

                <!-- 4. CAMPOS ESPECÍFICOS DE LA TABLA: ALUMNOS -->
                <div id="seccionEstudiante" class="space-y-3 p-3 bg-indigo-50/50 border border-indigo-100 rounded-xl hidden">
                    <p class="text-[11px] font-bold text-indigo-700 uppercase tracking-wider">Expediente Alumno y Tutor</p>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nombre Completo del Tutor</label>
                        <input type="text" name="nombre_tutor" value="{{ old('nombre_tutor') }}"
                               class="w-full bg-white border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44]" 
                               placeholder="Nombre del padre/madre o tutor">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Teléfono del Tutor</label>
                        <input type="text" name="telefono_tutor" value="{{ old('telefono_tutor') }}"
                               class="w-full bg-white border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44]" 
                               placeholder="Ej: 2481234567">
                    </div>
                </div>

                <!-- 5. CAMPOS ESPECÍFICOS DE LA TABLA: DOCENTES -->
                <div id="seccionDocente" class="space-y-3 p-3 bg-amber-50/50 border border-amber-100 rounded-xl hidden">
                    <p class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Contacto Docente</p>
                    
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Correo Electrónico</label>
                        <input type="email" name="correo" value="{{ old('correo') }}"
                               class="w-full bg-white border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44]" 
                               placeholder="profesor@cecyte.edu.mx">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Teléfono de Contacto</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}"
                               class="w-full bg-white border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44]" 
                               placeholder="Ej: 2481234567">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-[#841B44] hover:bg-[#6b1536] text-white font-bold text-xs rounded-xl shadow-2xs transition-colors cursor-pointer">
                        Dar de Alta Usuario
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- BITÁCORA DE USUARIOS -->
    <section class="lg:col-span-2 space-y-4">
        
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-xs flex items-center justify-between shadow-3xs">
                <div class="flex items-center gap-2">
                    <span class="material-icons-round text-base">check_circle</span> 
                    <p>{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 cursor-pointer">
                    <span class="material-icons-round text-sm">close</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl text-xs flex items-center justify-between shadow-3xs">
                <div class="flex items-center gap-2">
                    <span class="material-icons-round text-base">error</span> 
                    <p>{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 cursor-pointer">
                    <span class="material-icons-round text-sm">close</span>
                </button>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-xs border border-slate-200 overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Bitácora de Usuarios</h3>
                    <p class="text-[11px] text-slate-500">Últimos accesos, expedientes y control de acceso.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                            <th class="p-4">Identificador</th>
                            <th class="p-4">Nombre Completo</th>
                            <th class="p-4">Rol</th>
                            <th class="p-4 text-center">Estatus</th>
                            <th class="p-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-xs">
                        @forelse($usuarios as $user)
                            @php
                                $nom = $user->nombre ?? '';
                                $pat = $user->apellido_paterno ?? '';
                                try {
                                    if (is_string($nom) && (str_starts_with($nom, 'ey') || strlen($nom) > 50)) $nom = decrypt($nom);
                                    if (is_string($pat) && (str_starts_with($pat, 'ey') || strlen($pat) > 50)) $pat = decrypt($pat);
                                } catch (\Throwable $e) {}
                            @endphp
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="p-4 font-mono font-bold text-slate-900">{{ $user->username }}</td>
                                <td class="p-4 font-medium text-slate-700">
                                    {{ $pat || $nom ? "$pat $nom" : 'Sin expediente vinculado' }}
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
                                        <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full font-bold text-[10px]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 rounded-full font-bold text-[10px]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>Suspendido
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('usuarios.toggle-status', $user->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas {{ $user->activo ? 'suspender' : 'reactivar' }} este usuario?')">
                                            @csrf
                                            @method('PATCH')
                                            @if($user->activo)
                                                <button type="submit" title="Suspender acceso" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold rounded-lg text-[10px] transition-colors flex items-center gap-1 mx-auto cursor-pointer">
                                                    <span class="material-icons-round text-xs">block</span> Suspender
                                                </button>
                                            @else
                                                <button type="submit" title="Reactivar acceso" class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 font-bold rounded-lg text-[10px] transition-colors flex items-center gap-1 mx-auto cursor-pointer">
                                                    <span class="material-icons-round text-xs">check_circle</span> Reactivar
                                                </button>
                                            @endif
                                        </form>
                                    @else
                                        <span class="text-[10px] text-slate-400 italic font-semibold">Tu usuario</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-400 font-medium">No hay usuarios registrados en el sistema.</td>
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

<script>
    function cambiarFormularioRol(rol) {
        const secPersonales = document.getElementById('seccionPersonales');
        const secEstudiante = document.getElementById('seccionEstudiante');
        const secDocente    = document.getElementById('seccionDocente');
        
        const inputNombre   = document.getElementById('inputNombre');
        const inputPaterno  = document.getElementById('inputPaterno');
        const labelUser     = document.getElementById('labelUsername');

        secPersonales.classList.add('hidden');
        secEstudiante.classList.add('hidden');
        secDocente.classList.add('hidden');

        inputNombre.removeAttribute('required');
        inputPaterno.removeAttribute('required');

        if (rol === 'Estudiante') {
            secPersonales.classList.remove('hidden');
            secEstudiante.classList.remove('hidden');
            inputNombre.setAttribute('required', 'required');
            inputPaterno.setAttribute('required', 'required');
            labelUser.innerText = 'Matrícula Oficial (Username) *';
        } else if (rol === 'Docente') {
            secPersonales.classList.remove('hidden');
            secDocente.classList.remove('hidden');
            inputNombre.setAttribute('required', 'required');
            inputPaterno.setAttribute('required', 'required');
            labelUser.innerText = 'Clave de Docente (Username) *';
        } else if (['Orientador', 'Control Escolar', 'Coordinador', 'administrador'].includes(rol)) {
            secPersonales.classList.remove('hidden');
            inputNombre.setAttribute('required', 'required');
            inputPaterno.setAttribute('required', 'required');
            labelUser.innerText = 'Clave de Personal (Username) *';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const rolSeleccionado = document.getElementById('selectRol').value;
        if (rolSeleccionado) {
            cambiarFormularioRol(rolSeleccionado);
        }
    });
</script>
@endsection
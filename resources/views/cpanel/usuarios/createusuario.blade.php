@extends('cpanel/plantillaadmin')
@section('title', 'Control de Usuarios')
@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-6 grid grid-cols-1 lg:grid-cols-3 gap-6 transition-colors duration-200">
    
    <!-- PANEL DE REGISTRO DE USUARIO -->
    <section class="lg:col-span-1">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl shadow-xs border border-slate-200 dark:border-slate-800 space-y-6 transition-colors duration-200">
            <div>
                <h2 class="text-base font-black text-slate-900 dark:text-slate-100 flex items-center gap-2">
                    <span class="material-icons-round text-custom-primary">person_add</span> Registro de Usuario
                </h2>
                <p class="text-slate-500 dark:text-slate-400 text-xs mt-0.5">Añade credenciales y expediente correspondiente.</p>
            </div>

            @if ($errors->any())
                <div class="bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900 text-rose-800 dark:text-rose-200 p-3.5 rounded-2xl text-xs space-y-1.5 shadow-3xs">
                    @foreach ($errors->all() as $error)
                        <p class="flex items-center"><span class="material-icons-round text-sm mr-1.5 text-rose-600 dark:text-rose-400">error</span> {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('usuarios.store') }}" method="POST" class="space-y-4 text-xs" id="formUsuario">
                @csrf
                
                <!-- 1. SELECCIÓN DE ROL (DETONADOR) -->
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Rol en el Sistema *</label>
                    <select name="rol" id="selectRol" required onchange="cambiarFormularioRol(this.value)"
                            class="w-full bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 rounded-2xl p-3 font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all">
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
                <div class="p-4 bg-slate-50/80 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800 rounded-2xl space-y-3.5">
                    <p class="text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider">Credenciales de Acceso</p>
                    
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1" id="labelUsername">Identificador (Username) *</label>
                        <input type="text" name="username" value="{{ old('username') }}" required 
                               class="w-full bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all" 
                               placeholder="Ej: 22240105 o DOC-2401">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Contraseña *</label>
                        <input type="password" name="password" required 
                               class="w-full bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all" 
                               placeholder="••••••••">
                    </div>
                </div>

                <!-- 3. DATOS PERSONALES GENERALES -->
                <div id="seccionPersonales" class="space-y-3 hidden">
                    <p class="text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider">Datos Personales</p>
                    
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Nombre(s) *</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" id="inputNombre"
                               class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Apellido Paterno *</label>
                            <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno') }}" id="inputPaterno"
                                   class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Apellido Materno</label>
                            <input type="text" name="apellido_materno" value="{{ old('apellido_materno') }}"
                                   class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all">
                        </div>
                    </div>
                </div>

                <!-- 4. CAMPOS ESPECÍFICOS: ALUMNOS -->
                <div id="seccionEstudiante" class="space-y-3 p-3.5 bg-indigo-50/70 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/60 rounded-2xl hidden transition-colors">
                    <p class="text-[11px] font-black text-indigo-700 dark:text-indigo-300 uppercase tracking-wider">Expediente Alumno y Tutor</p>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Nombre Completo del Tutor</label>
                        <input type="text" name="nombre_tutor" value="{{ old('nombre_tutor') }}"
                               class="w-full bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all" 
                               placeholder="Nombre del padre/madre o tutor">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Teléfono del Tutor</label>
                        <input type="text" name="telefono_tutor" value="{{ old('telefono_tutor') }}"
                               class="w-full bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all" 
                               placeholder="Ej: 2481234567">
                    </div>
                </div>

                <!-- 5. CAMPOS ESPECÍFICOS: DOCENTES -->
                <div id="seccionDocente" class="space-y-3 p-3.5 bg-amber-50/70 dark:bg-amber-950/40 border border-amber-100 dark:border-amber-900/60 rounded-2xl hidden transition-colors">
                    <p class="text-[11px] font-black text-amber-700 dark:text-amber-300 uppercase tracking-wider">Contacto Docente</p>
                    
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Correo Electrónico</label>
                        <input type="email" name="correo" value="{{ old('correo') }}"
                               class="w-full bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all" 
                               placeholder="profesor@cecyte.edu.mx">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Teléfono de Contacto</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}"
                               class="w-full bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-medium focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all" 
                               placeholder="Ej: 2481234567">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold text-xs rounded-2xl shadow-sm hover:shadow-md transition-all cursor-pointer">
                        Dar de Alta Usuario
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- BITÁCORA DE USUARIOS -->
    <section class="lg:col-span-2 space-y-4">
        
        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 p-4 rounded-2xl text-xs flex items-center justify-between shadow-3xs font-semibold">
                <div class="flex items-center gap-2.5">
                    <span class="material-icons-round text-lg text-emerald-600 dark:text-emerald-400">check_circle</span> 
                    <p>{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-100 cursor-pointer">
                    <span class="material-icons-round text-sm">close</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 p-4 rounded-2xl text-xs flex items-center justify-between shadow-3xs font-semibold">
                <div class="flex items-center gap-2.5">
                    <span class="material-icons-round text-lg text-rose-600 dark:text-rose-400">error</span> 
                    <p>{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-600 dark:text-rose-400 hover:text-rose-900 dark:hover:text-rose-100 cursor-pointer">
                    <span class="material-icons-round text-sm">close</span>
                </button>
            </div>
        @endif

        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xs border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors duration-200">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 flex justify-between items-center">
                <div>
                    <h3 class="text-xs font-black text-slate-900 dark:text-slate-100 uppercase tracking-wider">Bitácora de Usuarios</h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Últimos accesos, expedientes y control de acceso.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-400 text-[10px] font-black uppercase tracking-wider">
                            <th class="p-4">Identificador</th>
                            <th class="p-4">Nombre Completo</th>
                            <th class="p-4">Rol</th>
                            <th class="p-4 text-center">Estatus</th>
                            <th class="p-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                        @forelse($usuarios as $user)
                            @php
                                $nom = $user->nombre ?? '';
                                $pat = $user->apellido_paterno ?? '';
                                try {
                                    if (is_string($nom) && (str_starts_with($nom, 'ey') || strlen($nom) > 50)) $nom = decrypt($nom);
                                    if (is_string($pat) && (str_starts_with($pat, 'ey') || strlen($pat) > 50)) $pat = decrypt($pat);
                                } catch (\Throwable $e) {}
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="p-4 font-mono font-bold text-slate-900 dark:text-slate-100">{{ $user->username }}</td>
                                <td class="p-4 font-medium text-slate-700 dark:text-slate-300">
                                    {{ $pat || $nom ? "$pat $nom" : 'Sin expediente vinculado' }}
                                </td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase border tracking-wider
                                        {{ $user->rol == 'Estudiante' ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border-indigo-200 dark:border-indigo-900/60' : '' }}
                                        {{ $user->rol == 'Docente' ? 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-900/60' : '' }}
                                        {{ $user->rol == 'administrador' ? 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-900/60' : '' }}
                                        {{ !in_array($user->rol, ['Estudiante','Docente','administrador']) ? 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700' : '' }}
                                    ">
                                        {{ $user->rol }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    @if($user->activo)
                                        <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-full font-extrabold text-[10px]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 rounded-full font-extrabold text-[10px]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>Suspendido
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- BOTÓN: CAMBIAR CONTRASEÑA -->
                                        <button type="button" 
                                                onclick="abrirModalPassword('{{ $user->id }}', '{{ $user->username }}')"
                                                class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-custom-primary rounded-xl transition-colors cursor-pointer"
                                                title="Cambiar contraseña">
                                            <span class="material-icons-round text-base">lock_reset</span>
                                        </button>

                                        <!-- BOTÓN: SUSPENDER / REACTIVAR -->
                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('usuarios.toggle-status', $user->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas {{ $user->activo ? 'suspender' : 'reactivar' }} este usuario?')">
                                                @csrf
                                                @method('PATCH')
                                                @if($user->activo)
                                                    <button type="submit" title="Suspender acceso" class="p-2 hover:bg-rose-50 dark:hover:bg-rose-950/60 text-rose-600 dark:text-rose-400 rounded-xl transition-colors cursor-pointer">
                                                        <span class="material-icons-round text-base">block</span>
                                                    </button>
                                                @else
                                                    <button type="submit" title="Reactivar acceso" class="p-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-xl transition-colors cursor-pointer">
                                                        <span class="material-icons-round text-base">check_circle</span>
                                                    </button>
                                                @endif
                                            </form>
                                        @else
                                            <span class="text-[10px] text-slate-400 dark:text-slate-500 italic font-semibold px-1">Tú</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-400 dark:text-slate-500 font-medium">No hay usuarios registrados en el sistema.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($usuarios->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/40 dark:bg-slate-800/40">
                    {{ $usuarios->links() }}
                </div>
            @endif
        </div>
    </section>
</main>

<!-- MODAL PARA EDITAR CONTRASEÑA -->
<div id="modalPassword" class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-3xl shadow-2xl overflow-hidden p-6 md:p-8 space-y-6 border border-slate-100 dark:border-slate-800 text-xs md:text-sm">
        
        <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-4">
            <h3 class="font-black text-slate-900 dark:text-slate-100 text-base md:text-lg flex items-center gap-2">
                <span class="material-icons-round text-xl text-custom-primary">vpn_key</span>
                Cambiar Contraseña
            </h3>
            <button onclick="cerrarModalPassword()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                <span class="material-icons-round text-2xl">close</span>
            </button>
        </div>

        <form id="formPassword" action="" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block font-black text-slate-400 uppercase tracking-wider text-[11px] mb-1">Usuario / Identificador</label>
                <p id="password_username" class="font-mono font-bold text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-slate-800/80 p-3 rounded-2xl border border-slate-200 dark:border-slate-700"></p>
            </div>

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nueva Contraseña *</label>
                <div class="relative">
                    <input type="password" name="password" id="input_new_password" required minlength="6"
                           class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 pr-10 font-medium text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all"
                           placeholder="Mínimo 6 caracteres">
                    <button type="button" onclick="togglePasswordVisibility('input_new_password', 'icon_toggle_1')" 
                            class="absolute right-3 top-3.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer focus:outline-hidden">
                        <span id="icon_toggle_1" class="material-icons-round text-lg">visibility</span>
                    </button>
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Confirmar Nueva Contraseña *</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="input_confirm_password" required minlength="6"
                           class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 pr-10 font-medium text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all"
                           placeholder="Repite la contraseña">
                    <button type="button" onclick="togglePasswordVisibility('input_confirm_password', 'icon_toggle_2')" 
                            class="absolute right-3 top-3.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer focus:outline-hidden">
                        <span id="icon_toggle_2" class="material-icons-round text-lg">visibility</span>
                    </button>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="cerrarModalPassword()" 
                        class="px-5 py-3 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-6 py-3 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold rounded-2xl shadow-md transition-colors cursor-pointer">
                    Actualizar Contraseña
                </button>
            </div>
        </form>

    </div>
</div>

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

    // Modal para actualizar contraseña
    function abrirModalPassword(userId, username) {
        document.getElementById('password_username').innerText = username;
        document.getElementById('formPassword').action = `/admon/usuarios/${userId}/password`;
        document.getElementById('input_new_password').value = '';
        document.getElementById('input_confirm_password').value = '';
        document.getElementById('modalPassword').classList.remove('hidden');
    }

    function cerrarModalPassword() {
        document.getElementById('modalPassword').classList.add('hidden');
    }

    // Alternar visibilidad de contraseña
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerText = 'visibility_off';
        } else {
            input.type = 'password';
            icon.innerText = 'visibility';
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
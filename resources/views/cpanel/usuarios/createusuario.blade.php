@extends('cpanel/plantillaadmin')
@section('title', 'Control de Usuarios')

@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-8 space-y-8 transition-colors duration-200">
    
    <!-- ENCABEZADO Y CONTADORES RÁPIDOS -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 text-custom-primary text-xs sm:text-sm font-black uppercase tracking-widest mb-1">
                <span class="material-icons-round text-lg">security</span>
                <span>Seguridad y Accesos</span>
            </div>
            <h2 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-slate-100 flex items-center gap-2.5">
                Administración de Usuarios y Expedientes
            </h2>
        </div>

        <div class="flex items-center gap-4 shrink-0">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 px-6 py-4 rounded-2xl shadow-xs text-center min-w-[120px]">
                <span class="text-xs text-slate-500 dark:text-slate-400 font-extrabold uppercase tracking-wider block mb-0.5">Registrados</span>
                <span class="font-black text-slate-900 dark:text-slate-100 text-xl md:text-2xl">{{ $usuarios->total() }}</span>
            </div>
            <div class="bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-900/60 px-6 py-4 rounded-2xl shadow-xs text-center min-w-[120px]">
                <span class="text-xs text-emerald-700 dark:text-emerald-400 font-extrabold uppercase tracking-wider block mb-0.5">Activos</span>
                <span class="font-black text-emerald-900 dark:text-emerald-100 text-xl md:text-2xl">{{ $usuarios->where('activo', 1)->count() }}</span>
            </div>
        </div>
    </div>

    <!-- ALERTAS DEL SISTEMA -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-900 dark:text-emerald-200 p-4 md:p-5 rounded-2xl text-sm md:text-base font-semibold flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-3">
                <span class="material-icons-round text-2xl text-emerald-600 dark:text-emerald-400">check_circle</span>
                <p>{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" aria-label="Cerrar notificación" class="text-emerald-700 dark:text-emerald-400 hover:text-emerald-950 cursor-pointer p-1">
                <span class="material-icons-round text-xl">close</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-900 dark:text-rose-200 p-4 md:p-5 rounded-2xl text-sm md:text-base font-semibold flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-3">
                <span class="material-icons-round text-2xl text-rose-600 dark:text-rose-400">error</span>
                <p>{{ session('error') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" aria-label="Cerrar notificación" class="text-rose-700 dark:text-rose-400 hover:text-rose-950 cursor-pointer p-1">
                <span class="material-icons-round text-xl">close</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900 text-rose-900 dark:text-rose-200 p-5 rounded-2xl text-sm space-y-2 shadow-2xs">
            <div class="flex items-center gap-2.5 font-bold text-base">
                <span class="material-icons-round text-xl text-rose-600 dark:text-rose-400">error_outline</span>
                <span>Corrige las siguientes observaciones antes de continuar:</span>
            </div>
            <ul class="list-disc pl-8 font-medium space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- CONTENEDOR PRINCIPAL: FORMULARIO + BITÁCORA -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- 1. PANEL DE REGISTRO DE USUARIO -->
        <section class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-800 space-y-6 transition-colors">
                
                <div class="border-b border-slate-100 dark:border-slate-800 pb-4">
                    <h3 class="text-lg font-black text-slate-900 dark:text-slate-100 flex items-center gap-2.5">
                        <span class="material-icons-round text-custom-primary text-2xl">person_add</span> 
                        <span>Alta de Nuevo Usuario</span>
                    </h3>
                </div>

                <form action="{{ route('usuarios.store') }}" method="POST" class="space-y-5 text-sm sm:text-base" id="formUsuario">
                    @csrf
                    
                    <!-- Selección de Rol -->
                    <div>
                        <label for="selectRol" class="block font-extrabold text-slate-800 dark:text-slate-200 mb-2">Rol Institucional *</label>
                        <select name="rol" id="selectRol" required onchange="cambiarFormularioRol(this.value)"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-semibold text-slate-900 dark:text-slate-100 focus:ring-4 focus:ring-custom-primary/20 focus:border-custom-primary transition-all cursor-pointer">
                            <option value="" disabled {{ old('rol') ? '' : 'selected' }}>Selecciona un rol...</option>
                            <option value="Estudiante" {{ old('rol') == 'Estudiante' ? 'selected' : '' }}>Estudiante</option>
                            <option value="Docente" {{ old('rol') == 'Docente' ? 'selected' : '' }}>Docente</option>
                            <option value="Orientador" {{ old('rol') == 'Orientador' ? 'selected' : '' }}>Orientador</option>
                            <option value="Control Escolar" {{ old('rol') == 'Control Escolar' ? 'selected' : '' }}>Control Escolar</option>
                            <option value="Coordinador" {{ old('rol') == 'Coordinador' ? 'selected' : '' }}>Coordinador</option>
                            <option value="administrador" {{ old('rol') == 'administrador' ? 'selected' : '' }}>Administrador</option>
                            <option value="Finanzas" {{ old('rol') == 'Finanzas' ? 'selected' : '' }}>Finanzas / Caja</option>
                        </select>
                    </div>

                    <!-- Credenciales de Inicio de Sesión -->
                    <div class="p-5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/80 rounded-2xl space-y-4">
                        <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300 text-xs font-black uppercase tracking-wider">
                            <span class="material-icons-round text-base text-custom-primary">key</span>
                            <span>Credenciales de Autenticación</span>
                        </div>
                        
                        <div>
                            <label for="inputUsername" class="block font-bold text-slate-800 dark:text-slate-200 mb-1.5" id="labelUsername">Identificador (Username) *</label>
                            <input type="text" name="username" value="{{ old('username') }}" required id="inputUsername"
                                   class="w-full bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 border-2 border-slate-300 dark:border-slate-700 rounded-xl p-3 font-medium focus:ring-4 focus:ring-custom-primary/20 focus:border-custom-primary transition-all placeholder:text-slate-400" 
                                   placeholder="Ej: 22240105, DOC-2401 o admin">
                        </div>

                        <div>
                            <label for="inputPassword" class="block font-bold text-slate-800 dark:text-slate-200 mb-1.5">Contraseña Inicial *</label>
                            <input type="password" id="inputPassword" name="password" required minlength="6"
                                   class="w-full bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 border-2 border-slate-300 dark:border-slate-700 rounded-xl p-3 font-medium focus:ring-4 focus:ring-custom-primary/20 focus:border-custom-primary transition-all placeholder:text-slate-400" 
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Datos Personales Base (Para Todos los Roles) -->
                    <div id="seccionPersonales" class="space-y-4 hidden">
                        <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300 text-xs font-black uppercase tracking-wider">
                            <span class="material-icons-round text-base text-custom-primary">badge</span>
                            <span id="tituloSeccionPersonales">Datos del Titular</span>
                        </div>
                        
                        <div>
                            <label for="inputNombre" class="block font-bold text-slate-800 dark:text-slate-200 mb-1.5">Nombre(s) *</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" id="inputNombre"
                                   class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border-2 border-slate-300 dark:border-slate-700 rounded-xl p-3 font-medium focus:ring-4 focus:ring-custom-primary/20 focus:border-custom-primary transition-all" 
                                   placeholder="Ej: Leonardo">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="inputPaterno" class="block font-bold text-slate-800 dark:text-slate-200 mb-1.5">Apellido Paterno *</label>
                                <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno') }}" id="inputPaterno"
                                       class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border-2 border-slate-300 dark:border-slate-700 rounded-xl p-3 font-medium focus:ring-4 focus:ring-custom-primary/20 focus:border-custom-primary transition-all" 
                                       placeholder="Ej: López">
                            </div>
                            <div id="contenedorMaterno">
                                <label for="inputMaterno" class="block font-bold text-slate-800 dark:text-slate-200 mb-1.5">Apellido Materno</label>
                                <input type="text" name="apellido_materno" value="{{ old('apellido_materno') }}" id="inputMaterno"
                                       class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border-2 border-slate-300 dark:border-slate-700 rounded-xl p-3 font-medium focus:ring-4 focus:ring-custom-primary/20 focus:border-custom-primary transition-all" 
                                       placeholder="Ej: Sánchez">
                            </div>
                        </div>
                    </div>

                    <!-- Contacto Personal Institucional -->
                    <div id="seccionContactoPersonal" class="space-y-4 p-5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/80 rounded-2xl hidden transition-colors">
                        <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300 text-xs font-black uppercase tracking-wider">
                            <span class="material-icons-round text-base text-custom-primary">contact_phone</span>
                            <span>Contacto Institucional</span>
                        </div>

                        <div id="campoTelefonoPersonal">
                            <label for="inputTelefono" class="block font-bold text-slate-800 dark:text-slate-200 mb-1.5">Teléfono de Contacto</label>
                            <input type="text" name="telefono" value="{{ old('telefono') }}" id="inputTelefono"
                                   class="w-full bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 border-2 border-slate-300 dark:border-slate-700 rounded-xl p-3 font-medium focus:ring-4 focus:ring-custom-primary/20 focus:border-custom-primary transition-all" 
                                   placeholder="Ej: 2481234567">
                        </div>

                        <div id="campoCorreoPersonal">
                            <label for="inputCorreo" class="block font-bold text-slate-800 dark:text-slate-200 mb-1.5">Correo Institucional</label>
                            <input type="email" name="correo" value="{{ old('correo') }}" id="inputCorreo"
                                   class="w-full bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 border-2 border-slate-300 dark:border-slate-700 rounded-xl p-3 font-medium focus:ring-4 focus:ring-custom-primary/20 focus:border-custom-primary transition-all" 
                                   placeholder="personal@cecyte.edu.mx">
                        </div>
                    </div>

                    <!-- Campos Específicos: Alumnos -->
                    <div id="seccionEstudiante" class="space-y-4 p-5 bg-indigo-50/70 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/60 rounded-2xl hidden transition-colors">
                        <div class="flex items-center gap-2 text-indigo-700 dark:text-indigo-300 text-xs font-black uppercase tracking-wider">
                            <span class="material-icons-round text-base">family_restroom</span>
                            <span>Expediente y Tutor</span>
                        </div>

                        <div>
                            <label for="inputNombreTutor" class="block font-bold text-slate-800 dark:text-slate-200 mb-1.5">Nombre Completo del Tutor</label>
                            <input type="text" id="inputNombreTutor" name="nombre_tutor" value="{{ old('nombre_tutor') }}"
                                   class="w-full bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 border-2 border-slate-300 dark:border-slate-700 rounded-xl p-3 font-medium focus:ring-4 focus:ring-custom-primary/20 focus:border-custom-primary transition-all" 
                                   placeholder="Nombre del padre/madre o tutor">
                        </div>

                        <div>
                            <label for="inputTelTutor" class="block font-bold text-slate-800 dark:text-slate-200 mb-1.5">Teléfono del Tutor</label>
                            <input type="text" id="inputTelTutor" name="telefono_tutor" value="{{ old('telefono_tutor') }}"
                                   class="w-full bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 border-2 border-slate-300 dark:border-slate-700 rounded-xl p-3 font-medium focus:ring-4 focus:ring-custom-primary/20 focus:border-custom-primary transition-all" 
                                   placeholder="Ej: 2481234567">
                        </div>
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="w-full py-4 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold text-base rounded-2xl shadow-md transition-all cursor-pointer flex items-center justify-center gap-2 active:scale-98">
                            <span class="material-icons-round text-xl">how_to_reg</span>
                            <span>Registrar Usuario y Expediente</span>
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- 2. BITÁCORA DE USUARIOS -->
        <section class="lg:col-span-2 space-y-4">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors">
                
                <div class="p-6 md:p-7 border-b border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 flex justify-between items-center">
                    <div>
                        <h3 class="text-sm sm:text-base font-black text-slate-900 dark:text-slate-100 uppercase tracking-wider">Bitácora de usuarios y personal</h3>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 text-xs font-black uppercase tracking-wider">
                                <th class="p-4 pl-6">Usuario</th>
                                <th class="p-4">Titular / Contacto</th>
                                <th class="p-4">Rol Asignado</th>
                                <th class="p-4 text-center">Estatus</th>
                                <th class="p-4 text-center pr-6">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                            @forelse($usuarios as $user)
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors {{ !$user->activo ? 'bg-slate-50/60 dark:bg-slate-800/20 opacity-75' : '' }}">
                                    
                                    <!-- Identificador y Monograma -->
                                    <td class="p-4 pl-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-2xl bg-slate-100 dark:bg-slate-800 text-custom-primary font-black flex items-center justify-center text-sm shrink-0 border border-slate-200 dark:border-slate-700 shadow-2xs">
                                                {{ $user->iniciales ?? strtoupper(substr($user->username, 0, 2)) }}
                                            </div>
                                            <span class="font-mono font-black text-slate-900 dark:text-slate-100 text-base tracking-wide">{{ $user->username }}</span>
                                        </div>
                                    </td>
                                    
                                    <!-- Nombre Completo y Teléfono -->
                                    <td class="p-4">
                                        @if(!empty($user->nombre_completo))
                                            <p class="font-bold text-slate-900 dark:text-slate-100 text-base leading-tight">
                                                {{ $user->nombre_completo }}
                                            </p>
                                            @if(!empty($user->telefono_contacto))
                                                <span class="text-xs text-slate-500 dark:text-slate-400 font-mono flex items-center gap-1 mt-1 font-semibold">
                                                    <span class="material-icons-round text-sm">call</span> {{ $user->telefono_contacto }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-slate-400 dark:text-slate-500 italic text-xs font-medium">
                                                Sin expediente vinculado
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <!-- Rol Asignado -->
                                    <td class="p-4">
                                        @php
                                            $rolKey = strtolower(trim($user->rol));
                                            $badgeClass = match($rolKey) {
                                                'estudiante'      => 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border-indigo-200 dark:border-indigo-900/60',
                                                'docente'         => 'bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-900/60',
                                                'coordinador'     => 'bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border-purple-200 dark:border-purple-900/60',
                                                'control escolar' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-900/60',
                                                'orientador'      => 'bg-cyan-50 dark:bg-cyan-950/60 text-cyan-800 dark:text-cyan-300 border-cyan-200 dark:border-cyan-900/60',
                                                'administrador'   => 'bg-rose-50 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 border-rose-200 dark:border-rose-900/60',
                                                'finanzas'        => 'bg-teal-50 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border-teal-200 dark:border-teal-900/60',
                                                default           => 'bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border-slate-300 dark:border-slate-700',
                                            };
                                        @endphp
                                        <span class="px-3 py-1.5 rounded-xl text-xs font-black uppercase border tracking-wider inline-block {{ $badgeClass }}">
                                            {{ $user->rol }}
                                        </span>
                                    </td>
                                    
                                    <!-- Estatus -->
                                    <td class="p-4 text-center">
                                        @if($user->activo)
                                            <span class="inline-flex items-center px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-full font-black text-xs uppercase tracking-wider">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1.5 bg-rose-50 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-800 rounded-full font-black text-xs uppercase tracking-wider">
                                                <span class="w-2 h-2 rounded-full bg-rose-500 mr-2"></span>Suspendido
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <!-- Acciones -->
                                    <td class="p-4 text-center pr-6">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Reestablecer Contraseña -->
                                            <button type="button" 
                                                    onclick="abrirModalPassword('{{ $user->id }}', '{{ $user->username }}')"
                                                    class="p-2.5 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-custom-primary rounded-xl transition-colors cursor-pointer border border-slate-200 dark:border-slate-700 shadow-2xs"
                                                    title="Reestablecer contraseña" aria-label="Reestablecer contraseña">
                                                <span class="material-icons-round text-lg">lock_reset</span>
                                            </button>

                                            @if(auth()->id() !== $user->id)
                                                <!-- Suspender / Reactivar -->
                                                <form action="{{ route('usuarios.toggle-status', $user->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas {{ $user->activo ? 'suspender' : 'reactivar' }} este usuario?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($user->activo)
                                                        <button type="submit" title="Suspender acceso" aria-label="Suspender acceso" class="p-2.5 hover:bg-amber-50 dark:hover:bg-amber-950/60 text-amber-700 dark:text-amber-400 rounded-xl transition-colors cursor-pointer border border-slate-200 dark:border-slate-700 shadow-2xs">
                                                            <span class="material-icons-round text-lg">block</span>
                                                        </button>
                                                    @else
                                                        <button type="submit" title="Reactivar acceso" aria-label="Reactivar acceso" class="p-2.5 hover:bg-emerald-50 dark:hover:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 rounded-xl transition-colors cursor-pointer border border-slate-200 dark:border-slate-700 shadow-2xs">
                                                            <span class="material-icons-round text-lg">check_circle</span>
                                                        </button>
                                                    @endif
                                                </form>

                                                <!-- Eliminar Usuario Permanentemente -->
                                                <form action="{{ route('usuarios.destroy', $user->id) }}" method="POST" onsubmit="return confirm('⚠️ ATENCIÓN: ¿Estás seguro de que deseas ELIMINAR PERMANENTEMENTE al usuario {{ $user->username }}? Esta acción borrará su cuenta y expediente.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="Eliminar usuario" aria-label="Eliminar usuario" class="p-2.5 hover:bg-rose-50 dark:hover:bg-rose-950/60 text-rose-600 dark:text-rose-400 rounded-xl transition-colors cursor-pointer border border-slate-200 dark:border-slate-700 shadow-2xs">
                                                        <span class="material-icons-round text-lg">delete_forever</span>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-slate-500 dark:text-slate-400 italic font-black px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">Tú</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-14 text-center text-slate-500 dark:text-slate-400 font-medium space-y-3">
                                        <span class="material-icons-round text-5xl block text-slate-300 dark:text-slate-700">group_off</span>
                                        <p class="text-base font-semibold">No hay usuarios registrados actualmente en el sistema.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($usuarios->hasPages())
                    <div class="p-5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/40 dark:bg-slate-800/40">
                        {{ $usuarios->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
</main>

<!-- MODAL PARA ACTUALIZAR CONTRASEÑA -->
<div id="modalPassword" class="fixed inset-0 bg-slate-950/80 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden" aria-hidden="true" role="dialog">
    <div class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden p-6 md:p-8 space-y-6 border border-slate-100 dark:border-slate-800 text-sm sm:text-base" id="boxModalPassword">
        
        <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-4">
            <h3 class="font-black text-slate-900 dark:text-slate-100 text-lg md:text-xl flex items-center gap-2.5">
                <span class="material-icons-round text-2xl text-custom-primary">vpn_key</span>
                <span>Reestablecer Contraseña</span>
            </h3>
            <button onclick="cerrarModalPassword()" aria-label="Cerrar modal" class="text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 cursor-pointer p-1">
                <span class="material-icons-round text-2xl">close</span>
            </button>
        </div>

        <form id="formPassword" action="" method="POST" class="space-y-5" onsubmit="return validarPasswordMatch(event)">
            @csrf
            @method('PATCH')

            <div>
                <label class="block font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider text-xs mb-1.5">Usuario Seleccionado</label>
                <p id="password_username" class="font-mono font-bold text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-800/80 p-3.5 rounded-2xl border border-slate-200 dark:border-slate-700 text-base"></p>
            </div>

            <div>
                <label for="input_new_password" class="block font-bold text-slate-800 dark:text-slate-200 mb-2">Nueva Contraseña *</label>
                <div class="relative">
                    <input type="password" name="password" id="input_new_password" required minlength="6"
                           class="w-full bg-slate-50 dark:bg-slate-800/80 border-2 border-slate-300 dark:border-slate-700 rounded-2xl p-4 pr-12 font-medium text-slate-900 dark:text-slate-100 focus:ring-4 focus:ring-custom-primary/20 focus:border-custom-primary transition-all"
                           placeholder="Mínimo 6 caracteres">
                    <button type="button" onclick="togglePasswordVisibility('input_new_password', 'icon_toggle_1')" 
                            class="absolute right-3.5 top-4 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 cursor-pointer p-1">
                        <span id="icon_toggle_1" class="material-icons-round text-xl">visibility</span>
                    </button>
                </div>
            </div>

            <div>
                <label for="input_confirm_password" class="block font-bold text-slate-800 dark:text-slate-200 mb-2">Confirmar Nueva Contraseña *</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="input_confirm_password" required minlength="6"
                           class="w-full bg-slate-50 dark:bg-slate-800/80 border-2 border-slate-300 dark:border-slate-700 rounded-2xl p-4 pr-12 font-medium text-slate-900 dark:text-slate-100 focus:ring-4 focus:ring-custom-primary/20 focus:border-custom-primary transition-all"
                           placeholder="Repite la contraseña">
                    <button type="button" onclick="togglePasswordVisibility('input_confirm_password', 'icon_toggle_2')" 
                            class="absolute right-3.5 top-4 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 cursor-pointer p-1">
                        <span id="icon_toggle_2" class="material-icons-round text-xl">visibility</span>
                    </button>
                </div>
                <p id="error_password_match" class="text-rose-600 dark:text-rose-400 text-xs font-bold mt-2 hidden">Las contraseñas ingresadas no coinciden.</p>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="cerrarModalPassword()" 
                        class="px-6 py-3.5 border-2 border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-extrabold rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer text-sm">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-6 py-3.5 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold rounded-2xl shadow-md transition-colors cursor-pointer text-sm">
                    Actualizar Contraseña
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function cambiarFormularioRol(rol) {
        if (!rol) return;

        const secPersonales       = document.getElementById('seccionPersonales');
        const secEstudiante       = document.getElementById('seccionEstudiante');
        const secContactoPersonal = document.getElementById('seccionContactoPersonal');
        const contenedorMaterno   = document.getElementById('contenedorMaterno');
        const campoTelefono       = document.getElementById('campoTelefonoPersonal');
        const campoCorreo         = document.getElementById('campoCorreoPersonal');
        
        const inputNombre         = document.getElementById('inputNombre');
        const inputPaterno        = document.getElementById('inputPaterno');
        const inputUser           = document.getElementById('inputUsername');
        const labelUser           = document.getElementById('labelUsername');
        const tituloPersonales    = document.getElementById('tituloSeccionPersonales');

        secPersonales.classList.remove('hidden');
        inputNombre.setAttribute('required', 'required');
        inputPaterno.setAttribute('required', 'required');

        secEstudiante.classList.add('hidden');
        secContactoPersonal.classList.add('hidden');
        contenedorMaterno.classList.remove('hidden');
        campoTelefono.classList.remove('hidden');
        campoCorreo.classList.remove('hidden');

        const rolLimpio = rol.toLowerCase().trim();

        if (rolLimpio === 'estudiante') {
            secEstudiante.classList.remove('hidden');
            labelUser.innerText = 'Matrícula Oficial (Username) *';
            inputUser.placeholder = 'Ej: 22240105';
            tituloPersonales.innerText = 'Datos del Alumno';
        } 
        else if (rolLimpio === 'docente') {
            secContactoPersonal.classList.remove('hidden');
            labelUser.innerText = 'Clave de Docente (Username) *';
            inputUser.placeholder = 'Ej: DOC-2401';
            tituloPersonales.innerText = 'Datos del Docente';
        } 
        else if (rolLimpio === 'administrador') {
            secContactoPersonal.classList.remove('hidden');
            campoTelefono.classList.add('hidden');
            labelUser.innerText = 'Identificador Admin (Username) *';
            inputUser.placeholder = 'Ej: admin';
            tituloPersonales.innerText = 'Datos del Administrador';
        } 
        else if (rolLimpio === 'finanzas') {
            secContactoPersonal.classList.remove('hidden');
            campoCorreo.classList.add('hidden');
            labelUser.innerText = 'Identificador Finanzas (Username) *';
            inputUser.placeholder = 'Ej: FIN-2401 o CAJA-01';
            tituloPersonales.innerText = 'Datos del Personal de Finanzas / Caja';
        }
        else if (['coordinador', 'orientador', 'control escolar'].includes(rolLimpio)) {
            contenedorMaterno.classList.add('hidden');
            secContactoPersonal.classList.remove('hidden');
            campoCorreo.classList.add('hidden');

            if (rolLimpio === 'coordinador') {
                labelUser.innerText = 'Identificador Coordinador (Username) *';
                inputUser.placeholder = 'Ej: COORD-2026-23';
                tituloPersonales.innerText = 'Datos del Coordinador';
            } else if (rolLimpio === 'orientador') {
                labelUser.innerText = 'Identificador Orientador (Username) *';
                inputUser.placeholder = 'Ej: ORIENT-2401';
                tituloPersonales.innerText = 'Datos del Orientador';
            } else {
                labelUser.innerText = 'Identificador Control Escolar (Username) *';
                inputUser.placeholder = 'Ej: CE-2425';
                tituloPersonales.innerText = 'Datos de Control Escolar';
            }
        }
    }

    function abrirModalPassword(userId, username) {
        document.getElementById('password_username').innerText = username;
        document.getElementById('formPassword').action = `/admon/usuarios/${userId}/password`;
        document.getElementById('input_new_password').value = '';
        document.getElementById('input_confirm_password').value = '';
        document.getElementById('error_password_match').classList.add('hidden');
        
        const modal = document.getElementById('modalPassword');
        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
    }

    function cerrarModalPassword() {
        const modal = document.getElementById('modalPassword');
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
    }

    function validarPasswordMatch(e) {
        const p1 = document.getElementById('input_new_password').value;
        const p2 = document.getElementById('input_confirm_password').value;
        const err = document.getElementById('error_password_match');

        if (p1 !== p2) {
            e.preventDefault();
            err.classList.remove('hidden');
            return false;
        }
        err.classList.add('hidden');
        return true;
    }

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

        const modal = document.getElementById('modalPassword');
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                cerrarModalPassword();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                cerrarModalPassword();
            }
        });
    });
</script>
@endsection
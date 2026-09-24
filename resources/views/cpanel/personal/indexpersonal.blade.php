@extends('cpanel/plantillaadmin')
@section('title', 'Directorio de Personal')

@section('content')
<main class="p-4 md:p-8 space-y-7 max-w-7xl w-full mx-auto text-sm md:text-base transition-colors duration-200">

    <!-- ENCABEZADO -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-custom-primary text-xs font-black uppercase tracking-widest mb-1">
                <span class="material-icons-round text-base">manage_accounts</span>
                <span>Recursos Humanos y Plantel</span>
            </div>
            <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-slate-100">
                Personal Administrativo e Institucional
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Directorio y expedientes de Coordinación, Control Escolar, Orientación y Administradores.
            </p>
        </div>
    </div>

    <!-- TARJETAS DE CONTEO RÁPIDO POR ROL -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Coordinación -->
        <a href="{{ route('admin.personal.index', ['rol' => 'Coordinador']) }}"
           class="bg-white dark:bg-slate-900 p-4 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs hover:border-custom-primary transition-all flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 border border-indigo-100 dark:border-indigo-900/60">
                <span class="material-icons-round text-xl">gavel</span>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Coordinación</p>
                <p class="text-lg font-black text-slate-900 dark:text-slate-100">{{ $conteoRoles['Coordinador'] ?? 0 }}</p>
            </div>
        </a>

        <!-- Control Escolar -->
        <a href="{{ route('admin.personal.index', ['rol' => 'Control Escolar']) }}"
           class="bg-white dark:bg-slate-900 p-4 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs hover:border-custom-primary transition-all flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/60">
                <span class="material-icons-round text-xl">analytics</span>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Control Escolar</p>
                <p class="text-lg font-black text-slate-900 dark:text-slate-100">{{ $conteoRoles['Control Escolar'] ?? 0 }}</p>
            </div>
        </a>

        <!-- Orientación Educativa -->
        <a href="{{ route('admin.personal.index', ['rol' => 'Orientador']) }}"
           class="bg-white dark:bg-slate-900 p-4 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs hover:border-custom-primary transition-all flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/60">
                <span class="material-icons-round text-xl">assignment_ind</span>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Orientación</p>
                <p class="text-lg font-black text-slate-900 dark:text-slate-100">{{ $conteoRoles['Orientador'] ?? 0 }}</p>
            </div>
        </a>

        <!-- Administradores -->
        <a href="{{ route('admin.personal.index', ['rol' => 'administrador']) }}"
           class="bg-white dark:bg-slate-900 p-4 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs hover:border-custom-primary transition-all flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-custom-light text-custom-primary flex items-center justify-center shrink-0 border border-custom-primary/30">
                <span class="material-icons-round text-xl">admin_panel_settings</span>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Admin Central</p>
                <p class="text-lg font-black text-slate-900 dark:text-slate-100">{{ $conteoRoles['administrador'] ?? 0 }}</p>
            </div>
        </a>

    </div>

    <!-- TABLA DE LISTADO -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
        
        <!-- Barra de Búsqueda y Filtros -->
        <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-slate-50/50 dark:bg-slate-800/30">
            <form action="{{ route('admin.personal.index') }}" method="GET" class="flex-1 flex flex-col sm:flex-row items-center gap-3">
                
                <div class="relative w-full sm:max-w-xs">
                    <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar usuario, correo o teléfono..." 
                           class="w-full bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-200 dark:border-slate-700 rounded-2xl py-2 pl-9 pr-3 text-xs focus:ring-2 focus:ring-custom-primary focus:outline-hidden">
                    <span class="material-icons-round text-base text-slate-400 absolute left-2.5 top-2.5">search</span>
                </div>

                <select name="rol" onchange="this.form.submit()"
                        class="w-full sm:w-auto bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-2xl py-2 px-3 text-xs focus:ring-2 focus:ring-custom-primary focus:outline-hidden">
                    <option value="">Todos los departamentos</option>
                    <option value="Coordinador" {{ $rolFiltro === 'Coordinador' ? 'selected' : '' }}>Coordinación</option>
                    <option value="Control Escolar" {{ $rolFiltro === 'Control Escolar' ? 'selected' : '' }}>Control Escolar</option>
                    <option value="Orientador" {{ $rolFiltro === 'Orientador' ? 'selected' : '' }}>Orientación</option>
                    <option value="administrador" {{ $rolFiltro === 'administrador' ? 'selected' : '' }}>Administrador</option>
                </select>

                @if($rolFiltro || $buscar)
                    <a href="{{ route('admin.personal.index') }}" class="text-xs text-rose-600 dark:text-rose-400 font-bold hover:underline flex items-center gap-1">
                        <span class="material-icons-round text-sm">filter_alt_off</span> Limpiar filtros
                    </a>
                @endif
            </form>

            <span class="text-xs font-mono text-slate-400">
                Registros: <strong class="text-slate-700 dark:text-slate-200">{{ $personal->count() }}</strong>
            </span>
        </div>

        <!-- Tabla de Datos -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800 text-[10px] font-black uppercase tracking-wider text-slate-400">
                        <th class="p-4 pl-6">Personal</th>
                        <th class="p-4">Contacto / Teléfono</th>
                        <th class="p-4">Departamento</th>
                        <th class="p-4">Seguridad 2FA</th>
                        <th class="p-4">Estatus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs">
                    @forelse($personal as $miembro)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            
                            <!-- Nombre e Identidad del Personal -->
                            <td class="p-4 pl-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-slate-100 dark:bg-slate-800 text-custom-primary font-black flex items-center justify-center shrink-0 border border-slate-200 dark:border-slate-700 text-xs shadow-3xs">
                                        {{ strtoupper(substr($miembro->username, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-extrabold text-slate-900 dark:text-slate-100 text-xs sm:text-sm leading-tight">
                                            {{ $miembro->nombre_completo }}
                                        </p>
                                        <p class="text-[11px] text-custom-primary font-mono font-bold mt-0.5">
                                            Usuario: {{ $miembro->username }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Correo y Teléfono -->
                            <td class="p-4">
                                <p class="text-slate-700 dark:text-slate-300 font-mono text-xs">
                                    {{ $miembro->email ?? 'Sin correo' }}
                                </p>
                                <span class="text-[11px] text-slate-400 flex items-center gap-1 mt-0.5">
                                    <span class="material-icons-round text-xs">call</span>
                                    <span>{{ $miembro->telefono }}</span>
                                </span>
                            </td>

                            <!-- Rol / Departamento -->
                            <td class="p-4">
                                @php
                                    $estiloRol = match(strtolower(trim($miembro->rol))) {
                                        'coordinador'     => 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 border-indigo-200 dark:border-indigo-900',
                                        'control escolar' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-900',
                                        'orientador'      => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-900',
                                        default           => 'bg-custom-light text-custom-primary border-custom-primary/30',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $estiloRol }}">
                                    {{ $miembro->rol }}
                                </span>
                            </td>

                            <!-- Estado 2FA -->
                            <td class="p-4">
                                @if($miembro->google2fa_enabled)
                                    <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-bold text-[11px]">
                                        <span class="material-icons-round text-sm">verified_user</span> Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-slate-400 font-medium text-[11px]">
                                        <span class="material-icons-round text-sm">gpp_maybe</span> Inactivo
                                    </span>
                                @endif
                            </td>

                            <!-- Estatus de Cuenta -->
                            <td class="p-4">
                                @if($miembro->activo)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-black text-[10px] uppercase border border-emerald-200 dark:border-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 font-black text-[10px] uppercase border border-rose-200 dark:border-rose-800">
                                        Suspendido
                                    </span>
                                @endif
                            </td>

                            <!-- Acción -->

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400 space-y-2">
                                <span class="material-icons-round text-3xl text-slate-300 dark:text-slate-600">group_off</span>
                                <p class="text-xs">No se encontró personal registrado con los filtros seleccionados.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</main>
@endsection
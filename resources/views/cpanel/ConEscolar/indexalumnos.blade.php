@extends('cpanel/plantillaCE')
@section('title', 'Matrícula Estudiantil')
@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-6 space-y-6 transition-colors duration-200">

    <!-- TARJETAS DE MÉTRICAS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-3xs flex items-center gap-4 transition-colors">
            <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/60 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round">check_circle</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">Alumnos Vigentes</p>
                <p class="text-lg font-black text-slate-800 dark:text-slate-100">{{ $totalVigentes }} Estudiantes</p>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-3xs flex items-center gap-4 transition-colors">
            <div class="w-10 h-10 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-100 dark:border-rose-900/60 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-icons-round">cancel</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">Bajas Registradas</p>
                <p class="text-lg font-black text-slate-800 dark:text-slate-100">{{ $totalBajas }} Expedientes</p>
            </div>
        </div>
    </div>

    <!-- BARRA DE BÚSQUEDA Y ACCIONES -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-xl shadow-xs border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4 transition-colors">
        
        <form action="{{ route('alumnos.index') }}" method="GET" class="flex flex-wrap items-center gap-3 text-xs w-full md:w-auto flex-1">
            
            <div class="relative w-full sm:w-80">
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-xl pl-9 pr-4 py-2 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden placeholder:text-slate-400 dark:placeholder:text-slate-500" 
                       placeholder="Buscar por nombre, apellido o matrícula...">
                <span class="material-icons-round text-slate-400 dark:text-slate-500 text-sm absolute left-3 top-2.5">search</span>
            </div>

            <div>
                <select name="estado" onchange="this.form.submit()" 
                        class="bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl p-2 font-semibold text-slate-700 dark:text-slate-200 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden cursor-pointer">
                    <option value="vigentes" {{ request('estado', 'vigentes') == 'vigentes' ? 'selected' : '' }}>Mostrar: Alumnos Vigentes</option>
                    <option value="bajas" {{ request('estado') == 'bajas' ? 'selected' : '' }}>Mostrar: Alumnos Dados de Baja</option>
                </select>
            </div>

            @if(request('buscar'))
                <a href="{{ route('alumnos.index') }}" class="text-[#841B44] dark:text-rose-400 hover:underline font-semibold flex items-center gap-0.5">
                    <span class="material-icons-round text-sm">clear</span> Limpiar búsqueda
                </a>
            @endif
        </form>

        <div class="w-full md:w-auto text-right shrink-0">
            <button onclick="toggleAsignarModal()" class="px-4 py-2 bg-[#841B44] hover:bg-[#6b1536] dark:bg-rose-800 dark:hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-2xs flex items-center gap-1.5 ml-auto cursor-pointer transition-colors">
                <span class="material-icons-round text-sm">group_add</span> Asignar Alumnos a Grupo
            </button>
        </div>
    </div>

    <!-- MENSAJE DE ÉXITO -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 p-4 rounded-xl text-xs flex items-center gap-2 shadow-3xs">
            <span class="material-icons-round text-base text-emerald-600 dark:text-emerald-400">check_circle</span> {{ session('success') }}
        </div>
    @endif

    <!-- TABLA DE ALUMNO Y EXPEDIENTES -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                        <th class="p-4">Matrícula</th>
                        <th class="p-4">Nombre del Estudiante</th>
                        <th class="p-4">Grupo / Especialidad Técnica</th>
                        <th class="p-4">Contacto del Tutor</th>
                        <th class="p-4 text-center">Estatus Matricular</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-xs">
                    @forelse($alumnos as $alumno)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors {{ !$alumno->activo ? 'bg-slate-50/50 dark:bg-slate-950/40 opacity-75' : '' }}">
                            <td class="p-4 font-mono font-bold text-slate-900 dark:text-slate-100 tracking-wide">{{ $alumno->username }}</td>
                            <td class="p-4">
                                <div class="font-bold text-slate-800 dark:text-slate-200">
                                    {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }} {{ $alumno->nombre }}
                                </div>
                            </td>
                            <td class="p-4 space-y-0.5">
                                @if($alumno->semestre)
                                    <div class="font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $alumno->semestre }}° "{{ $alumno->grupo }}"
                                    </div>
                                    <div class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-medium">{{ $alumno->especialidad }}</div>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-900/60 text-amber-800 dark:text-amber-300 px-2 py-0.5 rounded-xs font-semibold">
                                        <span class="w-1 h-1 rounded-full bg-amber-500"></span> Pendiente de Asignar
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 space-y-0.5">
                                <div class="font-medium text-slate-700 dark:text-slate-300">{{ $alumno->nombre_tutor }}</div>
                                <div class="text-slate-500 dark:text-slate-400 font-mono text-[11px] flex items-center gap-0.5">
                                    <span class="material-icons-round text-slate-400 dark:text-slate-500 text-xs">phone</span>
                                    {{ $alumno->telefono_tutor }}
                                </div>
                            </td>
                            <td class="p-4 text-center">
                                @if($alumno->activo)
                                    <span class="inline-flex items-center text-emerald-600 dark:text-emerald-400 font-semibold bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-900/60 px-2 py-0.5 rounded-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Vigente
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-rose-600 dark:text-rose-400 font-semibold bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900/60 px-2 py-0.5 rounded-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>Baja Escolar
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400 dark:text-slate-500 font-medium">
                                <span class="material-icons-round text-2xl block mb-1">person_search</span> No se encontraron registros.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($alumnos->hasPages())
            <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/20">
                {{ $alumnos->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</main>

<!-- MODAL DE ASIGNACIÓN MASIVA -->
<div id="asignarModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 transition-opacity opacity-0 pointer-events-none duration-300">
    <div class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-2xl p-6 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-5 transform scale-95 transition-transform duration-300 ease-out" id="asignarBox">
        
        <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-3">
            <div>
                <h3 class="font-black text-base text-slate-900 dark:text-slate-100">Asignación Masiva de Grupos</h3>
                <p class="text-[11px] text-slate-400 dark:text-slate-500">Distribuye alumnos inscritos vigentes a las secciones del ciclo</p>
            </div>
            <button onclick="toggleAsignarModal()" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg cursor-pointer">
                <span class="material-icons-round">close</span>
            </button>
        </div>

        <form action="{{ route('alumnos.asignar-grupo') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">1. Seleccionar Grupo de Destino</label>
                <select name="grupo_id" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-semibold text-slate-700 dark:text-slate-200 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                    <option value="" disabled selected>-- Elige el semestre, sección y carrera --</option>
                    @foreach($gruposActivos as $grp)
                        <option value="{{ $grp->id }}">{{ $grp->semestre }}° "{{ $grp->grupo }}" - {{ $grp->especialidad }} ({{ $grp->turno }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">2. Alumnos Vigentes Elegibles (Sin grupo o reasignación)</label>
                <div class="border border-slate-200 dark:border-slate-700 rounded-xl max-h-52 overflow-y-auto bg-slate-50 dark:bg-slate-800/50 divide-y divide-slate-200 dark:divide-slate-700/60 p-1">
                    @forelse($alumnosDisponibles as $al)
                        <label class="flex items-center justify-between p-2.5 hover:bg-white dark:hover:bg-slate-800 rounded-lg cursor-pointer transition-colors">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" name="alumno_ids[]" value="{{ $al->id }}" class="w-4 h-4 text-indigo-600 border-slate-300 dark:border-slate-600 rounded-sm focus:ring-indigo-500 dark:bg-slate-900">
                                <div>
                                    <p class="font-bold text-slate-800 dark:text-slate-200">{{ $al->apellido_paterno }} {{ $al->nombre }}</p>
                                    <p class="text-[10px] font-mono text-slate-400 dark:text-slate-500">{{ $al->username }}</p>
                                </div>
                            </div>
                            <span class="bg-amber-100 dark:bg-amber-950/80 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-900/60 text-[9px] font-bold px-2 py-0.5 rounded-sm uppercase tracking-wide">Sin Grupo</span>
                        </label>
                    @empty
                        <p class="p-4 text-center text-slate-400 dark:text-slate-500 font-medium">No hay alumnos vigentes pendientes por asignar.</p>
                    @endforelse
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2">
                <button type="button" onclick="toggleAsignarModal()" class="px-4 py-2 border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-semibold rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2 bg-[#841B44] hover:bg-[#6b1536] dark:bg-rose-800 dark:hover:bg-rose-700 text-white font-bold rounded-xl shadow-xs transition-colors cursor-pointer">
                    Confirmar Asignación
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleAsignarModal() {
        const modal = document.getElementById('asignarModal');
        const box = document.getElementById('asignarBox');
        if (modal.classList.contains('pointer-events-none')) {
            modal.classList.remove('pointer-events-none', 'opacity-0');
            box.classList.remove('scale-95');
        } else {
            modal.classList.add('pointer-events-none', 'opacity-0');
            box.classList.add('scale-95');
        }
    }
</script>
@endsection
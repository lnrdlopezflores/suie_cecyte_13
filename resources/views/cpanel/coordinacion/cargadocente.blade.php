@extends('cpanel/plantillacoordinacion')
@section('title', 'Carga Académica Docente')
@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-6 md:p-8 space-y-8 text-sm md:text-base">

    <!-- MENSAJES FLASH -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 p-4 md:p-5 rounded-2xl flex items-center justify-between shadow-xs font-semibold">
            <div class="flex items-center gap-3">
                <span class="material-icons-round text-2xl text-emerald-600 dark:text-emerald-400">check_circle</span>
                <p>{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-100 cursor-pointer">
                <span class="material-icons-round text-lg">close</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 p-4 md:p-5 rounded-2xl space-y-2 shadow-xs">
            <div class="flex items-center gap-2 font-bold">
                <span class="material-icons-round text-xl text-rose-600 dark:text-rose-400">error</span>
                <span>Ocurrieron errores al procesar la asignación:</span>
            </div>
            <ul class="list-disc pl-8 text-xs md:text-sm space-y-1 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ENCABEZADO Y ACCIONES -->
    <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6 transition-colors duration-200">
        <div>
            <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-slate-100 flex items-center gap-3">
                <span class="material-icons-round text-2xl md:text-3xl text-[#841B44] dark:text-rose-400">menu_book</span>
                Gestión de Cargas Académicas
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm mt-1">Supervisa y asigna materias, grupos, horarios y aulas para la plantilla docente.</p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <button onclick="document.getElementById('modalNuevaCarga').classList.remove('hidden')" 
                    class="px-6 py-3.5 bg-[#841B44] hover:bg-[#681535] dark:bg-rose-800 dark:hover:bg-rose-700 text-white font-extrabold rounded-2xl shadow-md transition-all cursor-pointer flex items-center gap-2 text-xs md:text-sm">
                <span class="material-icons-round text-lg">add_circle</span> Asignar Nueva Materia
            </button>
        </div>
    </div>

    <!-- LISTADO AGRUPADO POR DOCENTE -->
    <div class="space-y-6">
        @forelse($docentesConCarga as $docente)
            @php
                $nomD = $docente->nombre;
                $patD = $docente->apellido_paterno;
                $matD = $docente->apellido_materno;
                try {
                    if (is_string($nomD) && (str_starts_with($nomD, 'ey') || strlen($nomD) > 50)) $nomD = decrypt($nomD);
                    if (is_string($patD) && (str_starts_with($patD, 'ey') || strlen($patD) > 50)) $patD = decrypt($patD);
                    if (is_string($matD) && (str_starts_with($matD, 'ey') || strlen($matD) > 50)) $matD = decrypt($matD);
                } catch (\Throwable $e) {}

                $cargas = $docente->cargas ?? collect();
                $totalHoras = $cargas->sum('horas_semanales');
            @endphp

            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-200">
                
                <!-- CABECERA DE LA TARJETA DEL DOCENTE -->
                <div class="p-6 bg-slate-50/70 dark:bg-slate-800/40 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-rose-50 dark:bg-rose-950/60 text-[#841B44] dark:text-rose-300 rounded-2xl flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-900/60 font-black text-base shadow-3xs">
                            {{ strtoupper(mb_substr($patD ?? 'P', 0, 1) . mb_substr($nomD ?? 'D', 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-base md:text-lg font-black text-slate-900 dark:text-slate-100">
                                Prof. {{ $patD }} {{ $matD }} {{ $nomD }}
                            </h3>
                            <p class="text-xs text-slate-400 dark:text-slate-500 font-mono mt-0.5">
                                @php
                                    $correoDoc = $docente->correo ?? '';
                                    try {
                                        if (is_string($correoDoc) && (str_starts_with($correoDoc, 'ey') || strlen($correoDoc) > 50)) {
                                            $correoDoc = decrypt($correoDoc);
                                        }
                                    } catch (\Throwable $e) {}
                                @endphp
                                {{ $correoDoc ?: 'Sin correo registrado' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="px-3.5 py-1.5 bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-900 text-indigo-700 dark:text-indigo-300 text-xs font-black rounded-xl">
                            {{ $cargas->count() }} {{ Str::plural('Asignatura', $cargas->count()) }} ({{ $totalHoras }} hrs/sem)
                        </span>
                    </div>
                </div>

                <!-- TABLA DE ASIGNATURAS DEL DOCENTE -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 text-slate-400 text-xs font-extrabold uppercase tracking-wider">
                                <th class="p-4 md:px-6">Clave / Materia</th>
                                <th class="p-4">Grupo / Especialidad</th>
                                <th class="p-4">Horario</th>
                                <th class="p-4 text-center">Aula</th>
                                <th class="p-4 text-center">Horas</th>
                                <th class="p-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs md:text-sm">
                            @forelse($cargas as $carga)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="p-4 md:px-6 font-bold text-slate-900 dark:text-slate-100">
                                        <span class="font-mono text-[11px] text-[#841B44] dark:text-rose-400 bg-rose-50 dark:bg-rose-950/60 px-2 py-0.5 rounded-md mr-2">
                                            {{ $carga->clave }}
                                        </span>
                                        {{ $carga->materia_nombre }}
                                    </td>

                                    <td class="p-4">
                                        <span class="font-bold text-slate-800 dark:text-slate-200">
                                            {{ $carga->semestre }}° "{{ $carga->letra ?? $carga->grupo }}"
                                        </span>
                                        <span class="text-xs text-slate-400 dark:text-slate-500 block">
                                            {{ $carga->especialidad }} ({{ $carga->turno }})
                                        </span>
                                    </td>

                                    <td class="p-4 font-mono text-slate-600 dark:text-slate-300">
                                        {{ $carga->horario ?? 'No asignado' }}
                                    </td>

                                    <td class="p-4 text-center font-bold text-slate-700 dark:text-slate-300">
                                        <span class="bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded-lg border border-slate-200 dark:border-slate-700">
                                            {{ $carga->aula ?? 'S/A' }}
                                        </span>
                                    </td>

                                    <td class="p-4 text-center font-mono font-bold text-slate-800 dark:text-slate-200">
                                        {{ $carga->horas_semanales }} hrs
                                    </td>

                                    <td class="p-4 text-center">
                                        <form action="{{ route('coordinador.cargas.destroy', $carga->id) }}" method="POST" onsubmit="return confirm('¿Deseas desvincular esta asignatura de la carga del docente?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Eliminar asignación" class="p-1.5 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/60 rounded-xl transition-colors cursor-pointer">
                                                <span class="material-icons-round text-lg">delete_outline</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-slate-400 dark:text-slate-500 italic">
                                        Este docente aún no tiene materias asignadas para el ciclo actual.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 p-12 rounded-3xl border border-slate-200 dark:border-slate-800 text-center text-slate-400 dark:text-slate-500 font-medium space-y-3">
                <span class="material-icons-round text-5xl block text-slate-300 dark:text-slate-700">menu_book</span>
                <p class="text-base">No hay registros de docentes o cargas académicas registradas.</p>
            </div>
        @endforelse
    </div>

</main>

<!-- MODAL PARA ASIGNAR NUEVA CARGA ACADÉMICA -->
<div id="modalNuevaCarga" class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 w-full max-w-xl rounded-3xl shadow-2xl overflow-hidden p-6 md:p-8 space-y-6 border border-slate-100 dark:border-slate-800 text-xs md:text-sm">
        
        <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-4">
            <h3 class="font-black text-slate-900 dark:text-slate-100 text-base md:text-lg flex items-center gap-2">
                <span class="material-icons-round text-xl text-[#841B44] dark:text-rose-400">post_add</span> Asignar Asignatura a Docente
            </h3>
            <button onclick="document.getElementById('modalNuevaCarga').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <span class="material-icons-round text-2xl">close</span>
            </button>
        </div>

        <form action="{{ route('coordinador.cargas.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- 1. Docente -->
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Docente *</label>
                <select name="docente_id" required class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-[#841B44]">
                    <option value="" disabled selected>Selecciona al profesor...</option>
                    @foreach($docentesLista ?? [] as $doc)
                        @php
                            $nDoc = $doc->nombre;
                            $pDoc = $doc->apellido_paterno;
                            try {
                                if (is_string($nDoc) && (str_starts_with($nDoc, 'ey') || strlen($nDoc) > 50)) $nDoc = decrypt($nDoc);
                                if (is_string($pDoc) && (str_starts_with($pDoc, 'ey') || strlen($pDoc) > 50)) $pDoc = decrypt($pDoc);
                            } catch (\Throwable $e) {}
                        @endphp
                        <option value="{{ $doc->id }}">Prof. {{ $pDoc }} {{ $nDoc }} ({{ $doc->username }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- 2. Materia -->
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Materia / Asignatura *</label>
                    <select name="materia_id" required class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-[#841B44]">
                        <option value="" disabled selected>Selecciona la materia...</option>
                        @foreach($materiasLista ?? [] as $mat)
                            <option value="{{ $mat->id }}">{{ $mat->clave }} - {{ $mat->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 3. Grupo -->
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Grupo Receptor *</label>
                    <select name="grupo_id" required class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-[#841B44]">
                        <option value="" disabled selected>Selecciona el grupo...</option>
                        @foreach($gruposLista ?? [] as $grp)
                            <option value="{{ $grp->id }}">
                                {{ $grp->semestre }}° "{{ $grp->letra ?? $grp->grupo }}" - {{ $grp->especialidad }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- 4. Aula -->
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Aula / Laboratorio *</label>
                    <input type="text" name="aula" required placeholder="Ej: Aula B-12 o Lab. Cómputo 2"
                           class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-medium text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-[#841B44]">
                </div>

                <!-- 5. Horario -->
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Horario Semanal *</label>
                    <input type="text" name="horario" required placeholder="Ej: Lun y Mié 08:00 - 10:00"
                           class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-medium text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-[#841B44]">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modalNuevaCarga').classList.add('hidden')" 
                        class="px-5 py-3 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    Cancelar
                </button>
                <button type="submit" class="px-6 py-3 bg-[#841B44] hover:bg-[#681535] dark:bg-rose-800 dark:hover:bg-rose-700 text-white font-extrabold rounded-2xl shadow-md transition-colors">
                    Guardar Asignación
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
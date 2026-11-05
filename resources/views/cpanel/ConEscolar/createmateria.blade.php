@extends('cpanel/plantillaCE')
@section('title', 'Añadir Nueva Asignatura')

@section('content')
<main class="flex-1 max-w-2xl w-full mx-auto p-4 md:p-6 transition-colors duration-200">
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-xs border border-slate-200 dark:border-slate-800 space-y-6">
        
        <!-- Encabezado de la Tarjeta -->
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="material-icons-round text-[#841B44] dark:text-rose-400">add_box</span> Incorporar Nueva Materia
                </h2>
                <p class="text-slate-500 dark:text-slate-400 text-xs mt-0.5">Agrega asignaturas oficiales al catálogo del mapa curricular del plantel.</p>
            </div>
            <a href="{{ route('materias.index') }}" class="text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-[#841B44] dark:hover:text-rose-400 flex items-center gap-1.5 transition-colors">
                <span class="material-icons-round text-sm">arrow_back</span> Regresar al catálogo
            </a>
        </div>

        <!-- Alertas de Errores de Validación -->
        @if ($errors->any())
            <div class="bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900 text-rose-800 dark:text-rose-300 p-3.5 rounded-xl text-xs space-y-1 animate-fade-in">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center"><span class="material-icons-round text-sm mr-1.5 text-rose-600 dark:text-rose-400">error</span> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Formulario de Registro -->
        <form action="{{ route('materias.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Clave de la Materia -->
                <div class="sm:col-span-1">
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Clave de la Asignatura *</label>
                    <input type="text" name="clave" value="{{ old('clave') }}" required 
                           class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-mono font-bold text-[#841B44] dark:text-rose-400 focus:ring-1 focus:ring-[#841B44] dark:focus:ring-rose-500 focus:outline-hidden uppercase placeholder:text-slate-400 dark:placeholder:text-slate-500" 
                           placeholder="Ej: RED-6A">
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Debe ser un identificador único.</p>
                </div>

                <!-- Nombre de la Materia -->
                <div class="sm:col-span-2">
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Nombre Oficial de la Materia *</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required 
                           class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-bold text-slate-900 dark:text-white focus:ring-1 focus:ring-[#841B44] dark:focus:ring-rose-500 focus:outline-hidden placeholder:text-slate-400 dark:placeholder:text-slate-500" 
                           placeholder="Ej: Redes e Infraestructura de Datos">
                </div>
            </div>

            <!-- Horas Semanales -->
            <div class="w-full sm:w-1/3">
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Horas Semanales Totales *</label>
                <div class="relative flex items-center">
                    <span class="material-icons-round text-slate-400 dark:text-slate-500 text-sm absolute left-3">schedule</span>
                    <input type="number" name="horas_semanales" value="{{ old('horas_semanales', 4) }}" min="1" max="20" required 
                           class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl pl-9 pr-4 py-2.5 font-mono font-bold text-slate-900 dark:text-white focus:ring-1 focus:ring-[#841B44] dark:focus:ring-rose-500 focus:outline-hidden">
                </div>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Por defecto: 4 horas semanales.</p>
            </div>

            <!-- Botones de Control Inferior -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                <a href="{{ route('materias.index') }}" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-xl transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-[#841B44] hover:bg-[#6b1536] dark:bg-rose-700 dark:hover:bg-rose-800 text-white font-bold rounded-xl shadow-2xs transition-all active:scale-98 cursor-pointer">
                    Registrar Asignatura
                </button>
            </div>
        </form>
    </div>
</main>
@endsection
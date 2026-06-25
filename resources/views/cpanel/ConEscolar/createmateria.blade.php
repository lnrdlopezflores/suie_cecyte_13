@extends('cpanel/plantillaCE')
@section('title', 'Añadir Nueva Asignatura')
@section('content')
<main class="flex-1 max-w-2xl w-full mx-auto p-4 md:p-6">
    <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-200 space-y-6">
        
        <!-- Encabezado de la Tarjeta -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-icons-round text-[#841B44]">add_box</span> Incorporar Nueva Materia
                </h2>
                <p class="text-slate-500 text-xs mt-0.5">Agrega asignaturas oficiales al catálogo del mapa curricular del plantel.</p>
            </div>
            <a href="{{ route('materias.index') }}" class="text-xs font-bold text-slate-600 hover:text-[#841B44] flex items-center gap-1+">
                <span class="material-icons-round text-sm">arrow_back</span> Regresar al catálogo
            </a>
        </div>

        <!-- Alertas de Errores de Validación -->
        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 p-3 rounded-xl text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center"><span class="material-icons-round text-sm mr-1">error</span> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Formulario de Registro -->
        <form action="{{ route('materias.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Clave de la Materia -->
                <div class="sm:col-span-1">
                    <label class="block font-bold text-slate-700 mb-1">Clave de la Asignatura</label>
                    <input type="text" name="clave" value="{{ old('clave') }}" required 
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-mono font-bold text-[#841B44] focus:ring-1 focus:ring-[#841B44] focus:outline-hidden uppercase placeholder:text-slate-400" 
                           placeholder="Ej: RED-6A">
                    <p class="text-[10px] text-slate-400 mt-1">Debe ser un identificador único.</p>
                </div>

                <!-- Nombre de la Materia -->
                <div class="sm:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">Nombre Oficial de la Materia</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required 
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden placeholder:text-slate-400" 
                           placeholder="Ej: Redes e Infraestructura de Datos">
                </div>
            </div>

            <!-- Horas Semanales -->
            <div class="w-full sm:w-1/3">
                <label class="block font-bold text-slate-700 mb-1">Horas Semanales Totales</label>
                <div class="relative flex items-center">
                    <span class="material-icons-round text-slate-400 text-sm absolute left-3">schedule</span>
                    <input type="number" name="horas_semanales" value="{{ old('horas_semanales', 4) }}" min="1" max="20" required 
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-9 pr-4 py-2.5 font-mono font-bold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Por defecto: 4 horas semanales.</p>
            </div>

            <!-- Botones de Control Inferior -->
            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('materias.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-[#841B44] hover:bg-[#6b1536] text-white font-bold rounded-xl shadow-2xs transition-colors cursor-pointer">
                    Registrar Asignatura
                </button>
            </div>
        </form>
    </div>
</main>
@endsection
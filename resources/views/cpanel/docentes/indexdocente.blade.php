@extends('cpanel/plantillaadmin')
@section('title', 'Plantilla Docente')
@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-6 space-y-6">

    <div class="bg-white p-4 rounded-xl shadow-xs border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
        
        <form action="{{ route('docentes.index') }}" method="GET" class="flex flex-wrap items-center gap-3 text-xs w-full md:w-auto">
            <div class="relative w-full sm:w-80">
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-9 pr-4 py-2 font-medium focus:ring-1 focus:ring-[#841B44] focus:outline-hidden placeholder:text-slate-400" 
                       placeholder="Buscar por nombre, apellidos o clave...">
                <span class="material-icons-round text-slate-400 text-sm absolute left-3 top-2.5">search</span>
            </div>

            @if(request('buscar'))
                <a href="{{ route('docentes.index') }}" class="text-[#841B44] hover:underline font-semibold flex items-center gap-0.5">
                    <span class="material-icons-round text-sm">clear</span> Limpiar búsqueda
                </a>
            @endif
        </form>

        <div class="shrink-0 w-full md:w-auto text-right">
            <a href="{{ route('docentes.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#841B44] hover:bg-[#631433] text-white text-xs font-bold rounded-xl shadow-2xs transition-colors cursor-pointer">
                <span class="material-icons-round text-sm">badge</span> Alta de Docente
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xs border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                        <th class="p-4 w-16 text-center">No. Emp.</th>
                        <th class="p-4">Nombre Completo</th>
                        <th class="p-4">Clave de Acceso</th>
                        <th class="p-4">Contacto</th>
                        <th class="p-4 text-center">Estado del Usuario</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs">
                    @forelse($docentes as $docente)
                        <tr class="hover:bg-slate-50/40 transition-colors {{ !$docente->activo ? 'bg-slate-50/50 opacity-70' : '' }}">
                            <td class="p-4 text-center font-mono text-slate-500 font-bold">#{{ $docente->docente_id }}</td>
                            
                            <td class="p-4">
                                <div class="font-bold text-slate-900">
                                    {{ $docente->apellido_paterno }} {{ $docente->apellido_materno }} {{ $docente->nombre }}
                                </div>
                            </td>
                            
                            <td class="p-4 font-mono text-[#841B44] font-semibold">
                                {{ $docente->username }}
                            </td>
                            
                            <td class="p-4 space-y-0.5">
                                @if($docente->correo)
                                    <div class="flex items-center text-slate-600 gap-1">
                                        <span class="material-icons-round text-slate-400 text-xs">mail</span>
                                        {{ $docente->correo }}
                                    </div>
                                @endif
                                @if($docente->telefono)
                                    <div class="flex items-center text-slate-500 gap-1 text-[11px]">
                                        <span class="material-icons-round text-slate-400 text-xs">phone</span>
                                        {{ $docente->telefono }}
                                    </div>
                                @endif
                                @if(!$docente->correo && !$docente->telefono)
                                    <span class="text-slate-400 italic">Sin datos de contacto</span>
                                @endif
                            </td>
                            
                            <td class="p-4 text-center">
                                @if($docente->activo)
                                    <span class="inline-flex items-center text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Vigente
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-rose-600 font-semibold bg-rose-50 px-2 py-0.5 rounded-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>Usuario Suspendido
                                    </span>
                                @endif
                            </td>
                            
                            <td class="p-4 text-center">
                                <button class="p-1.5 hover:bg-slate-100 rounded-lg text-slate-500 hover:text-slate-900 transition-colors inline-flex items-center" title="Ver Carga Académica">
                                    <span class="material-icons-round text-sm">auto_stories</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400 font-medium">
                                <span class="material-icons-round text-2xl block mb-1">badge</span>
                                No hay docentes registrados o no coinciden con los términos de búsqueda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($docentes->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                {{ $docentes->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</main>
@endsection
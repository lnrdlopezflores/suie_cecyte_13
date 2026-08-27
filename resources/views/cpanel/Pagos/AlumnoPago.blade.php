@extends('cpanel/plantillaestudiante')
@section('title', 'Control de Pagos')

{{-- Inyección dinámica del Grado y Grupo en el Topbar --}}
@section('grupo_badge')
    @if(isset($infoAlumno) && !empty($infoAlumno->semestre))
        {{ $infoAlumno->semestre }}° Semestre — Grupo "{{ $infoAlumno->grupo }}"
    @else
        Aspirante / Sin Grupo
    @endif
@endsection

@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-6 md:p-8 space-y-8 text-sm md:text-base">

    <!-- ALERTAS DE ÉXITO Y ERROR -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 p-4 md:p-5 rounded-2xl flex items-center justify-between shadow-xs font-semibold">
            <div class="flex items-center gap-3">
                <span class="material-icons-round text-2xl text-emerald-600 dark:text-emerald-400">check_circle</span>
                <p class="text-sm md:text-base">{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-100 cursor-pointer">
                <span class="material-icons-round text-lg">close</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 p-4 md:p-5 rounded-2xl space-y-2 shadow-xs">
            <div class="flex items-center gap-2 font-bold text-sm md:text-base">
                <span class="material-icons-round text-xl text-rose-600 dark:text-rose-400">error</span>
                <span>Por favor revisa los siguientes errores:</span>
            </div>
            <ul class="list-disc pl-8 text-xs md:text-sm space-y-1 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- COLUMNA IZQUIERDA: HISTORIAL DE PAGOS (TABLA) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6 transition-colors duration-200">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-4">
                    <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-slate-100 flex items-center gap-3">
                        <span class="material-icons-round text-2xl md:text-3xl text-[#841B44] dark:text-rose-400">receipt_long</span> 
                        Historial y Control de Pagos
                    </h2>
                    <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm mt-1">Revisa el listado de tus transacciones, folios bancarios y el estatus de revisión institucional.</p>
                </div>
                
                <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-700/80 text-slate-400 dark:text-slate-400 text-xs font-black uppercase tracking-wider">
                                <th class="p-4">Concepto</th>
                                <th class="p-4 text-right">Monto</th>
                                <th class="p-4 text-center">Fecha Pago</th>
                                <th class="p-4">Ref. Bancaria</th>
                                <th class="p-4 text-center">Estatus</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-slate-700 dark:text-slate-200 text-xs md:text-sm">
                            @forelse($pagos as $pago)
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="p-4 font-bold text-slate-900 dark:text-slate-100">{{ $pago->concepto }}</td>
                                    
                                    <td class="p-4 text-right font-mono font-black text-slate-900 dark:text-slate-100 text-sm md:text-base">
                                        ${{ number_format($pago->monto, 2) }}
                                    </td>
                                    
                                    <td class="p-4 text-center font-mono text-slate-500 dark:text-slate-400">
                                        {{ $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') : '—' }}
                                    </td>
                                    
                                    <td class="p-4 font-mono text-slate-700 dark:text-slate-300 font-bold">
                                        @if($pago->referencia_bancaria)
                                            <span class="bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded-lg border border-slate-200 dark:border-slate-700">{{ $pago->referencia_bancaria }}</span>
                                        @else
                                            <span class="text-slate-400 dark:text-slate-500 italic font-normal">No registrada</span>
                                        @endif
                                    </td>
                                    
                                    <td class="p-4 text-center">
                                        @switch($pago->estatus)
                                            @case('Pagado')
                                                <span class="inline-flex items-center text-emerald-700 dark:text-emerald-300 font-extrabold bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-900 px-3 py-1 rounded-full text-xs">
                                                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span> Liquidado
                                                </span>
                                                @break
                                            @case('Pendiente')
                                                <span class="inline-flex items-center text-amber-700 dark:text-amber-300 font-extrabold bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-900 px-3 py-1 rounded-full text-xs">
                                                    <span class="w-2 h-2 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span> En Revisión
                                                </span>
                                                @break
                                            @case('Vencido')
                                                <span class="inline-flex items-center text-rose-700 dark:text-rose-300 font-extrabold bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900 px-3 py-1 rounded-full text-xs">
                                                    <span class="w-2 h-2 rounded-full bg-rose-500 mr-1.5"></span> Vencido
                                                </span>
                                                @break
                                            @case('Condonado')
                                                <span class="inline-flex items-center text-indigo-700 dark:text-indigo-300 font-extrabold bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-900 px-3 py-1 rounded-full text-xs">
                                                    <span class="w-2 h-2 rounded-full bg-indigo-500 mr-1.5"></span> Beca / Condonado
                                                </span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-10 text-center text-slate-400 dark:text-slate-500 font-medium">
                                        <span class="material-icons-round text-4xl block mb-2 text-slate-300 dark:text-slate-700">payments</span>
                                        No cuentas con cobros o historial de transacciones en este periodo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: FORMULARIO DE CARGA DE COMPROBANTE -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6 transition-colors duration-200">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-4">
                    <h3 class="text-lg md:text-xl font-black text-slate-900 dark:text-slate-100 flex items-center gap-2.5">
                        <span class="material-icons-round text-2xl text-[#841B44] dark:text-rose-400">file_upload</span> 
                        Reportar Pago
                    </h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm mt-1">Sube tu ficha de depósito o transferencia para validación en ventanilla.</p>
                </div>

                <form action="{{ route('alumnoPagos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5 text-xs md:text-sm">Concepto de Pago *</label>
                        <select name="concepto" required 
                                class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-semibold text-slate-800 dark:text-slate-100 text-xs md:text-sm focus:ring-2 focus:ring-[#841B44] focus:outline-hidden transition-all">
                            <option value="" disabled selected>-- Elige una opción --</option>
                            <option value="Colegiatura Ordinaria" {{ old('concepto') == 'Colegiatura Ordinaria' ? 'selected' : '' }}>Colegiatura Ordinaria</option>
                            <option value="Reinscripción" {{ old('concepto') == 'Reinscripción' ? 'selected' : '' }}>Reinscripción</option>
                            <option value="Derecho de Examen" {{ old('concepto') == 'Derecho de Examen' ? 'selected' : '' }}>Derecho de Examen</option>
                            <option value="Trámite de Titulación" {{ old('concepto') == 'Trámite de Titulación' ? 'selected' : '' }}>Trámite de Titulación</option>
                            <option value="Constancia" {{ old('concepto') == 'Constancia' ? 'selected' : '' }}>Constancia de Estudios</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5 text-xs md:text-sm">Monto Depositado ($) *</label>
                        <input type="number" name="monto" value="{{ old('monto') }}" step="0.01" min="1" required 
                               class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-mono font-bold text-slate-900 dark:text-slate-100 text-xs md:text-sm focus:ring-2 focus:ring-[#841B44] focus:outline-hidden transition-all" 
                               placeholder="Ej: 450.00">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5 text-xs md:text-sm">Número de Referencia / Folio *</label>
                        <input type="text" name="referencia_bancaria" value="{{ old('referencia_bancaria') }}" required 
                               class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-mono font-bold text-slate-900 dark:text-slate-100 text-xs md:text-sm uppercase focus:ring-2 focus:ring-[#841B44] focus:outline-hidden transition-all" 
                               placeholder="Ej: REF-92837482">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5 text-xs md:text-sm">Comprobante (PDF o Imagen) *</label>
                        <input type="file" name="comprobante" accept="image/*,application/pdf" required 
                               class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-300 dark:border-slate-700 rounded-2xl p-2.5 text-xs text-slate-500 dark:text-slate-400 font-semibold focus:outline-hidden file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-50 dark:file:bg-rose-950/60 file:text-[#841B44] dark:file:text-rose-300 hover:file:bg-rose-100 dark:hover:file:bg-rose-900/80 file:cursor-pointer transition-all">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-4 bg-[#841B44] hover:bg-[#681535] dark:bg-rose-800 dark:hover:bg-rose-700 text-white font-extrabold text-sm rounded-2xl shadow-md transition-all cursor-pointer flex items-center justify-center gap-2">
                            <span class="material-icons-round text-lg">cloud_upload</span> Enviar Comprobante
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</main>
@endsection
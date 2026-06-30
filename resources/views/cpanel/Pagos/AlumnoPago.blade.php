@extends('cpanel/plantillaestudiante')
@section('title', 'Control de Pagos')
@section('grupo_badge')
    @if(isset($infoAlumno->semestre))
        {{ $infoAlumno->semestre }}° Semestre — Grupo "{{ $infoAlumno->grupo }}"
    @else
        Aspirante / Sin Grupo
    @endif
@endsection
@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-6 space-y-6 text-xs">

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-3 rounded-xl flex items-center gap-2 font-semibold">
            <span class="material-icons-round text-sm">check_circle</span> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-3 rounded-xl space-y-1">
            @foreach ($errors->all() as $error)
                <p class="flex items-center"><span class="material-icons-round text-sm mr-1">error</span> {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-3xs">
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2 mb-1">
                    <span class="material-icons-round text-[#841B44]">receipt_long</span> Estado de Cuenta y Cobros
                </h2>
                <p class="text-slate-500 text-[11px] mb-4">Revisa el listado de tus conceptos asignados, montos institucionales y el estatus de revisión.</p>
                
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                                <th class="p-3">Concepto</th>
                                <th class="p-3 text-right">Monto</th>
                                <th class="p-3 text-center">Fecha Pago</th>
                                <th class="p-3">Ref. Bancaria</th>
                                <th class="p-3 text-center">Estatus</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-slate-700">
                            @forelse($pagos as $pago)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="p-3 font-semibold text-slate-900">{{ $pago->concepto }}</td>
                                    
                                    <td class="p-3 text-right font-mono font-bold text-slate-800">
                                        ${{ number_format($pago->monto, 2) }}
                                    </td>
                                    
                                    <td class="p-3 text-center font-mono text-slate-500">
                                        {{ $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') : '—' }}
                                    </td>
                                    
                                    <td class="p-3 font-mono text-slate-600">
                                        @if($pago->referencia_bancaria)
                                            <span class="bg-slate-100 px-1.5 py-0.5 rounded-sm">{{ $pago->referencia_bancaria }}</span>
                                        @else
                                            <span class="text-slate-400 italic">No registrada</span>
                                        @endif
                                    </td>
                                    
                                    <td class="p-3 text-center">
                                        @switch($pago->estatus)
                                            @case('Pagado')
                                                <span class="inline-flex items-center text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded-sm">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Liquidado
                                                </span>
                                                @break
                                            @case('Pendiente')
                                                <span class="inline-flex items-center text-amber-600 font-bold bg-amber-50 px-2 py-0.5 rounded-sm">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>En Revisión / Pendiente
                                                </span>
                                                @break
                                            @case('Vencido')
                                                <span class="inline-flex items-center text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded-sm">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>Vencido
                                                </span>
                                                @break
                                            @case('Condonado')
                                                <span class="inline-flex items-center text-indigo-600 font-bold bg-indigo-50 px-2 py-0.5 rounded-sm">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-1.5"></span>Beca / Condonado
                                                </span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-400 font-medium">
                                        <span class="material-icons-round text-2xl block mb-1 text-slate-300">payments</span>
                                        No cuentas con cobros o historial de transacciones en este periodo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-3xs space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-1.5">
                        <span class="material-icons-round text-[#841B44]">file_upload</span> Reportar Pago Realizado
                    </h3>
                    <p class="text-slate-400 text-[11px] mt-0.5">Sube tu ficha de depósito o transferencia bancaria para validación en ventanilla.</p>
                </div>

                <form action="{{ route('alumno.pagos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Concepto que reportas</label>
                        <select name="concepto" required 
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden">
                            <option value="" disabled selected>-- Elige una opción --</option>
                            <option value="Colegiatura Ordinaria" {{ old('concepto') == 'Colegiatura Ordinaria' ? 'selected' : '' }}>Colegiatura Ordinaria</option>
                            <option value="Reinscripción" {{ old('concepto') == 'Reinscripción' ? 'selected' : '' }}>Reinscripción</option>
                            <option value="Derecho de Examen" {{ old('concepto') == 'Derecho de Examen' ? 'selected' : '' }}>Derecho de Examen</option>
                            <option value="Trámite de Titulación" {{ old('concepto') == 'Trámite de Titulación' ? 'selected' : '' }}>Trámite de Titulación</option>
                            <option value="Constancia" {{ old('concepto') == 'Constancia' ? 'selected' : '' }}>Constancia</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Monto Depositado ($)</label>
                        <input type="number" name="monto" value="{{ old('monto') }}" step="0.01" min="1" required 
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-mono font-bold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden" 
                               placeholder="0.00">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Número de Referencia / Folio</label>
                        <input type="text" name="referencia_bancaria" value="{{ old('referencia_bancaria') }}" required 
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-mono font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden uppercase" 
                               placeholder="Ej: REF1928374">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Archivo Comprobante (PDF o Imagen)</label>
                        <input type="file" name="comprobante" accept="image/*,application/pdf" required 
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2 text-slate-500 font-semibold focus:outline-hidden file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-[#841B44]/10 file:text-[#841B44] hover:file:bg-[#841B44]/20 file:cursor-pointer">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full px-4 py-2.5 bg-[#841B44] hover:bg-[#6b1536] text-white font-bold rounded-xl shadow-2xs transition-colors cursor-pointer flex items-center justify-center gap-1.5">
                            <span class="material-icons-round text-sm">cloud_upload</span> Cargar y Enviar Pago
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</main>
@endsection
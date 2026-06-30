@extends('cpanel.plantillafinazas')
@section('title', 'Validación de Comprobante')
@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-6 text-xs h-full flex flex-col space-y-4 overflow-hidden">
    
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-3xs flex justify-between items-center shrink-0">
        <div>
            <span class="text-[9px] bg-amber-50 text-amber-700 px-2 py-0.5 font-bold uppercase tracking-wider rounded-sm font-mono">Folio Pendiente #{{ $pago->id }}</span>
            <h2 class="text-base font-bold text-slate-900 mt-1 flex items-center gap-1.5">
                <span class="material-icons-round text-[#841B44]">fact_check</span> Auditoría y Conciliación de Pago
            </h2>
        </div>
        <a href="{{ route('contador.pagos.index') }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-colors flex items-center gap-1">
            <span class="material-icons-round text-sm">arrow_back</span> Regresar a la fila
        </a>
    </div>

    <div class="flex-1 grid grid-cols-1 lg:grid-cols-12 gap-4 overflow-hidden h-full pb-4">
        
        <div class="lg:col-span-7 bg-slate-800 rounded-2xl border border-slate-700 shadow-inner flex flex-col overflow-hidden h-full">
            <div class="bg-slate-900 px-4 py-2 flex justify-between items-center border-b border-slate-700">
                <span class="text-slate-400 font-mono text-[10px] flex items-center gap-1">
                    <span class="material-icons-round text-xs">picture_as_pdf</span> Documento Digital Alumno
                </span>
                <a href="{{ asset('storage/' . $pago->comprobante_url) }}" target="_blank" class="text-sky-400 hover:underline font-bold text-[11px] flex items-center gap-0.5">
                    Abrir en pestaña nueva <span class="material-icons-round text-xs">open_in_new</span>
                </a>
            </div>
            
            <div class="flex-1 bg-slate-700/40 relative">
                @if(pathinfo($pago->comprobante_url, PATHINFO_EXTENSION) == 'pdf')
                    <embed src="{{ asset('storage/' . $pago->comprobante_url) }}" type="application/pdf" class="w-full h-full object-contain" />
                @else
                    <div class="w-full h-full overflow-auto flex items-center justify-center p-4">
                        <img src="{{ asset('storage/' . $pago->comprobante_url) }}" alt="Voucher de banco" class="max-w-full max-h-full rounded-lg shadow-lg object-contain">
                    </div>
                @endif
            </div>
        </div>

        <div class="lg:col-span-5 flex flex-col h-full overflow-y-auto space-y-4">
            
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-3xs space-y-3">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Información del Estudiante</h3>
                <div class="grid grid-cols-2 gap-3 text-[11px]">
                    <div>
                        <span class="text-slate-400 block font-medium">Nombre Completo:</span>
                        <span class="font-bold text-slate-800 text-xs">{{ $pago->alumno_nombre }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Matrícula/ID:</span>
                        <span class="font-mono font-bold text-slate-700 text-xs">#{{ $pago->alumno_id }}</span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-slate-400 block font-medium">Concepto Registrado:</span>
                        <span class="font-bold text-[#841B44] bg-rose-50 px-2 py-0.5 rounded-sm uppercase tracking-wide text-[10px]">
                            {{ $pago->concepto }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-3xs space-y-3">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Datos de Conciliación Bancaria</h3>
                <div class="grid grid-cols-2 gap-3 text-[11px]">
                    <div>
                        <span class="text-slate-400 block font-medium">Monto Declarado:</span>
                        <span class="font-mono font-black text-emerald-600 text-sm">${{ number_format($pago->monto, 2) }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Referencia / Folio Banco:</span>
                        <span class="font-mono font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded-md text-xs uppercase select-all">
                            {{ $pago->referencia_bancaria }}
                        </span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-slate-400 block font-medium">Fecha de Envío por el Alumno:</span>
                        <span class="font-mono text-slate-600">{{ \Carbon\Carbon::parse($pago->created_at)->format('d/m/Y h:i A') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-3xs">
                <form action="{{ route('contador.pagos.validar', $pago->id) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 space-y-2">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Acción del Cajero</span>
                        <p class="text-slate-500 leading-normal text-[11px]">Al procesar la validación, el estatus pasará a <b class="text-emerald-600">"Pagado"</b> y el sistema generará automáticamente el comprobante fiscal interno del **SUIE** con sello digital.</p>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Observaciones / Notas en el Comprobante (Opcional)</label>
                        <textarea name="observaciones" rows="2" 
                                  class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-semibold text-slate-700 focus:ring-1 focus:ring-[#841B44] focus:outline-hidden"
                                  placeholder="Ej: Pago de parcialidad correspondiente al mes en curso. Conectado a cuenta Santander."></textarea>
                    </div>

                    <button type="submit" class="w-full px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-xl shadow-xs transition-colors cursor-pointer flex items-center justify-center gap-2 text-xs">
                        <span class="material-icons-round text-base">verified</span> Validar y Generar Comprobante Institucional
                    </button>
                </form>
            </div>

        </div>
    </div>
</main>
@endsection
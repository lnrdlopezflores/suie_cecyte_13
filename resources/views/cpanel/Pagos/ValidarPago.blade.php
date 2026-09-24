@extends('cpanel.plantillafinazas')
@section('title', 'Validación de Comprobante')
@section('content')
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-6 text-xs h-full flex flex-col space-y-4 overflow-hidden transition-colors duration-200">
    
    <!-- BARRA SUPERIOR DE ACCIÓN -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-3xs flex justify-between items-center shrink-0 transition-colors duration-200">
        <div>
            <span class="text-[9px] bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-900/60 px-2 py-0.5 font-bold uppercase tracking-wider rounded-md font-mono">Folio Pendiente #{{ $pago->id }}</span>
            <h2 class="text-base font-bold text-slate-900 dark:text-slate-100 mt-1 flex items-center gap-1.5">
                <span class="material-icons-round text-custom-primary">fact_check</span> Auditoría y Conciliación de Pago
            </h2>
        </div>
        <a href="{{ route('contador.pagos.index') }}" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold rounded-xl transition-colors flex items-center gap-1">
            <span class="material-icons-round text-sm">arrow_back</span> Regresar a la fila
        </a>
    </div>

    <!-- CONTENEDOR PRINCIPAL DIVIDIDO -->
    <div class="flex-1 grid grid-cols-1 lg:grid-cols-12 gap-4 overflow-hidden h-full pb-4">
        
        <!-- PANEL IZQUIERDO: VISOR DIGITAL -->
        <div class="lg:col-span-7 bg-slate-800 dark:bg-slate-950 rounded-2xl border border-slate-700 dark:border-slate-800 shadow-inner flex flex-col overflow-hidden h-full">
            <div class="bg-slate-900 dark:bg-slate-900/90 px-4 py-2 flex justify-between items-center border-b border-slate-700 dark:border-slate-800">
                <span class="text-slate-400 font-mono text-[10px] flex items-center gap-1">
                    <span class="material-icons-round text-xs">picture_as_pdf</span> Documento Digital Alumno
                </span>
                <a href="{{ route('contador.pagos.comprobante', $pago->id) }}" target="_blank" class="text-sky-400 hover:underline font-bold text-[11px] flex items-center gap-0.5">
                    Abrir en pestaña nueva <span class="material-icons-round text-xs">open_in_new</span>
                </a>
            </div>
            
            <div class="flex-1 bg-slate-700/40 dark:bg-slate-900/50 relative h-full">
                @php
                    $extension = strtolower(pathinfo($pago->comprobante_url, PATHINFO_EXTENSION));
                @endphp

                @if($extension === 'pdf')
                    <iframe src="{{ route('contador.pagos.comprobante', $pago->id) }}#toolbar=1&navpanes=0" 
                            class="w-full h-full border-0 absolute inset-0" 
                            title="Comprobante de Pago en PDF">
                    </iframe>
                @else
                    <div class="w-full h-full overflow-auto flex items-center justify-center p-4">
                        <img src="{{ route('contador.pagos.comprobante', $pago->id) }}" 
                             alt="Voucher de banco" 
                             class="max-w-full max-h-full rounded-lg shadow-lg object-contain">
                    </div>
                @endif
            </div>
        </div>

        <!-- PANEL DERECHO: DETALLES Y ACCIÓN DE VALIDACIÓN -->
        <div class="lg:col-span-5 flex flex-col h-full overflow-y-auto space-y-4">
            
            <!-- Datos del Estudiante -->
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-3xs space-y-3 transition-colors duration-200">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800 pb-2">Información del Estudiante</h3>
                <div class="grid grid-cols-2 gap-3 text-[11px]">
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block font-medium">Nombre Completo:</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 text-xs">{{ $pago->alumno_nombre }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block font-medium">Matrícula/ID:</span>
                        <span class="font-mono font-bold text-slate-700 dark:text-slate-300 text-xs">#{{ $pago->alumno_id }}</span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-slate-400 dark:text-slate-500 block font-medium">Concepto Registrado:</span>
                        <span class="font-bold text-custom-primary bg-custom-light border border-custom-primary/30 px-2 py-0.5 rounded-sm uppercase tracking-wide text-[10px]">
                            {{ $pago->concepto }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Datos Bancarios -->
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-3xs space-y-3 transition-colors duration-200">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800 pb-2">Datos de Conciliación Bancaria</h3>
                <div class="grid grid-cols-2 gap-3 text-[11px]">
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block font-medium">Monto Declarado:</span>
                        <span class="font-mono font-black text-emerald-600 dark:text-emerald-400 text-sm">${{ is_numeric($pago->monto) ? number_format($pago->monto, 2) : $pago->monto }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block font-medium">Referencia / Folio Banco:</span>
                        <span class="font-mono font-bold text-slate-800 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md text-xs uppercase select-all border border-slate-200/80 dark:border-slate-700">
                            {{ $pago->referencia_bancaria }}
                        </span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-slate-400 dark:text-slate-500 block font-medium">Fecha de Envío por el Alumno:</span>
                        <span class="font-mono text-slate-600 dark:text-slate-300">{{ \Carbon\Carbon::parse($pago->created_at)->format('d/m/Y h:i A') }}</span>
                    </div>
                </div>
            </div>

            <!-- Formulario de Aprobación -->
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-3xs transition-colors duration-200">
                <form action="{{ route('contador.pagos.validar', $pago->id) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="bg-slate-50 dark:bg-slate-800/60 p-3.5 rounded-xl border border-slate-200 dark:border-slate-700/80 space-y-2">
                        <span class="text-[10px] text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider block">Acción del Cajero</span>
                        <p class="text-slate-500 dark:text-slate-300 leading-normal text-[11px]">Al procesar la validación, el estatus pasará a <b class="text-emerald-600 dark:text-emerald-400 font-bold">"Pagado"</b> y el sistema generará automáticamente el comprobante fiscal interno del <strong>SUIE</strong> con sello digital.</p>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Observaciones / Notas en el Comprobante (Opcional)</label>
                        <textarea name="observaciones" rows="2" 
                                  class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl p-2.5 font-semibold text-slate-700 dark:text-slate-200 focus:ring-1 focus:ring-custom-primary focus:outline-hidden transition-colors"
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
<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Caja #{{ $pago->id }} - SUIE</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <style>
        @page {
            size: letter portrait;
            margin: 10mm;
        }

        @media print {
            .no-print { 
                display: none !important; 
            }
            html, body {
                width: 100% !important;
                height: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
            }
            .print-container {
                width: 100% !important;
                max-width: 100% !important;
                height: 100% !important;
                min-height: 100% !important;
                border: none !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                padding: 0 !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: space-between !important;
            }
            .print-section {
                flex: 1 1 0% !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: space-between !important;
                padding: 12px 0 !important;
            }
            .print-separator {
                margin: 8px 0 !important;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 text-xs p-4 md:p-8 flex flex-col items-center min-h-screen">

    <!-- BOTONES DE ACCIÓN (NO SALEN EN LA IMPRESIÓN) -->
    <div class="no-print max-w-4xl w-full mb-6 flex items-center justify-between bg-white p-4 rounded-2xl shadow-xs border border-slate-200">
        <a href="{{ route('contador.cobro-directo.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all flex items-center gap-1.5">
            <span class="material-icons-round text-sm">arrow_back</span>
            <span>Nuevo Cobro</span>
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('contador.pagos.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all">
                Ir a la Fila de Pagos
            </a>
            <button onclick="window.print()" class="px-5 py-2 bg-[#841B44] hover:bg-[#681535] text-white font-black rounded-xl shadow-xs transition-all flex items-center gap-2 cursor-pointer">
                <span class="material-icons-round text-sm">print</span>
                <span>Imprimir Recibo en Hoja Completa</span>
            </button>
        </div>
    </div>

    <!-- DOCUMENTO EN HOJA CARTA COMPLETA -->
    <div class="print-container max-w-4xl w-full bg-white border border-slate-300 rounded-3xl shadow-sm p-8 space-y-6">

        <!-- PARTE 1: TALÓN PARA CONTROL ESCOLAR / FINANZAS -->
        <div class="print-section space-y-4">
            
            <!-- Cabecera -->
            <div class="flex justify-between items-start border-b-2 border-slate-800 pb-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-[#841B44] rounded-xl flex items-center justify-center text-white">
                        <span class="material-icons-round text-2xl">account_balance</span>
                    </div>
                    <div>
                        <h1 class="text-sm font-black tracking-widest text-[#841B44]">COLEGIO DE ESTUDIOS CIENTÍFICOS Y TECNOLÓGICOS</h1>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Departamento de Finanzas</p>
                        <p class="text-[9px] text-slate-400 font-mono">Comprobante Fiscal Digital Institucional (CFDI)</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="bg-rose-50 text-[#841B44] border border-[#841B44]/40 px-3 py-1 rounded-md font-mono font-black text-xs block">
                        ORIGINAL / COPIA CAJA
                    </span>
                    <span class="font-mono text-slate-600 font-black text-xs mt-1 block">Folio: #{{ str_pad($pago->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>

            <!-- Tabla de Datos -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 bg-slate-50 p-5 rounded-2xl border border-slate-200">
                <div class="col-span-2">
                    <span class="text-slate-400 block text-[9px] uppercase font-bold tracking-wider">Nombre del Estudiante:</span>
                    <strong class="text-slate-900 text-sm block">{{ $pago->alumno_nombre }}</strong>
                    <span class="text-[10px] text-slate-500">{{ $pago->semestre }}° Semestre - Grupo "{{ $pago->grupo }}" ({{ $pago->especialidad }})</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[9px] uppercase font-bold tracking-wider">Matrícula Escolar:</span>
                    <span class="font-mono font-bold text-slate-800 text-xs block mt-0.5">{{ $pago->matricula }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[9px] uppercase font-bold tracking-wider">Fecha y Hora de Emisión:</span>
                    <span class="font-mono text-slate-800 text-xs block mt-0.5">{{ \Carbon\Carbon::parse($pago->created_at)->format('d/m/Y h:i A') }}</span>
                </div>

                <div class="col-span-2">
                    <span class="text-slate-400 block text-[9px] uppercase font-bold tracking-wider">Concepto Liquidado:</span>
                    <span class="font-bold text-slate-900 text-xs block mt-0.5">{{ $pago->concepto }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[9px] uppercase font-bold tracking-wider">Referencia Bancaria / Folio:</span>
                    <span class="font-mono text-slate-700 text-[11px] block mt-0.5">{{ $pago->referencia_bancaria }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[9px] uppercase font-bold tracking-wider">Importe Pagado:</span>
                    <strong class="font-mono text-base text-emerald-700 block mt-0.5">${{ number_format((float)$pago->monto, 2) }} MXN</strong>
                </div>
            </div>

            <!-- Firmas y sellos -->
            <div class="grid grid-cols-3 gap-6 pt-4 items-end text-center">
                <div class="text-left text-[10px] text-slate-500">
                    <p><strong>Cajero:</strong> {{ auth()->user()->username ?? 'Ventanilla Central' }}</p>
                    <p class="font-mono text-[9px]">Sello: SUIE-FIN-{{ substr(md5($pago->id . $pago->created_at), 0, 10) }}</p>
                </div>
                <div>
                    <div class="border-b border-slate-400 h-8 mb-1"></div>
                    <span class="text-[9px] font-bold text-slate-600 block uppercase">Firma del Alumno / Solicitante</span>
                </div>
                <div>
                    <div class="border-b border-slate-400 h-8 mb-1"></div>
                    <span class="text-[9px] font-bold text-slate-600 block uppercase">Sello y Firma del Cajero Receptor</span>
                </div>
            </div>

        </div>

        <!-- LÍNEA DE CORTE (SEPARADOR DE TIJERAS) -->
        <div class="print-separator relative py-3 flex items-center justify-center border-t-2 border-dashed border-slate-400">
            <span class="absolute bg-white px-4 text-slate-400 text-[10px] flex items-center gap-1.5 font-mono uppercase tracking-widest select-none">
                <span class="material-icons-round text-sm">content_cut</span> Recorte por la línea punteada • Talón independiente
            </span>
        </div>

        <!-- PARTE 2: TALÓN PARA EL ALUMNO -->
        <div class="print-section space-y-4">
            
            <!-- Cabecera -->
            <div class="flex justify-between items-start border-b-2 border-slate-800 pb-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-emerald-700 rounded-xl flex items-center justify-center text-white">
                        <span class="material-icons-round text-2xl">verified</span>
                    </div>
                    <div>
                        <h2 class="text-sm font-black tracking-widest text-[#841B44]">COLEGIO DE ESTUDIOS CIENTÍFICOS Y TECNOLÓGICOS</h2>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Comprobante Oficial de Pago para el Estudiante</p>
                        <p class="text-[9px] text-slate-400">Consérvese este documento como comprobante oficial para reinscripciones o trámites.</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="bg-emerald-50 text-emerald-800 border border-emerald-300 px-3 py-1 rounded-md font-mono font-black text-xs block">
                        TALÓN DEL ALUMNO
                    </span>
                    <span class="font-mono text-slate-600 font-black text-xs mt-1 block">Folio: #{{ str_pad($pago->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>

            <!-- Tabla de Datos -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 bg-slate-50 p-5 rounded-2xl border border-slate-200">
                <div class="col-span-2">
                    <span class="text-slate-400 block text-[9px] uppercase font-bold tracking-wider">Nombre del Estudiante:</span>
                    <strong class="text-slate-900 text-sm block">{{ $pago->alumno_nombre }}</strong>
                    <span class="text-[10px] text-slate-500">{{ $pago->semestre }}° Semestre - Grupo "{{ $pago->grupo }}" ({{ $pago->especialidad }})</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[9px] uppercase font-bold tracking-wider">Matrícula Escolar:</span>
                    <span class="font-mono font-bold text-slate-800 text-xs block mt-0.5">{{ $pago->matricula }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[9px] uppercase font-bold tracking-wider">Fecha y Hora de Emisión:</span>
                    <span class="font-mono text-slate-800 text-xs block mt-0.5">{{ \Carbon\Carbon::parse($pago->created_at)->format('d/m/Y h:i A') }}</span>
                </div>

                <div class="col-span-2">
                    <span class="text-slate-400 block text-[9px] uppercase font-bold tracking-wider">Concepto:</span>
                    <span class="font-bold text-slate-900 text-xs block mt-0.5">{{ $pago->concepto }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[9px] uppercase font-bold tracking-wider">Estado de la Operación:</span>
                    <span class="font-black text-emerald-700 bg-emerald-100 border border-emerald-300 px-2 py-0.5 rounded text-[10px] inline-block mt-0.5">
                        LIQUIDADO / PAGADO
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[9px] uppercase font-bold tracking-wider">Total Cubierto:</span>
                    <strong class="font-mono text-base text-emerald-700 block mt-0.5">${{ number_format((float)$pago->monto, 2) }} MXN</strong>
                </div>
            </div>

            <!-- Firmas y sellos -->
            <div class="grid grid-cols-3 gap-6 pt-4 items-end text-center">
                <div class="text-left text-[10px] text-slate-500">
                    <p class="text-[9px] font-mono">Validador: SUIE-BOX-{{ auth()->id() ?? '01' }}</p>
                    <p class="text-[9px] text-slate-400">Verificación disponible en plataforma institucional.</p>
                </div>
                <div>
                    <div class="border-b border-slate-400 h-8 mb-1"></div>
                    <span class="text-[9px] font-bold text-slate-600 block uppercase">Firma del Estudiante</span>
                </div>
                <div>
                    <div class="border-b border-slate-400 h-8 mb-1"></div>
                    <span class="text-[9px] font-bold text-slate-600 block uppercase">Sello Digital de Caja Institucional</span>
                </div>
            </div>

        </div>

    </div>

</body>
</html>
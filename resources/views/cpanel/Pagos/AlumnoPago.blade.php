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
<main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-8 space-y-8 text-sm md:text-base transition-colors duration-200">

    <!-- RESUMEN FINANCIERO / KPIS -->
    @php
        $totalPagado = collect($pagos)->where('estatus', 'Pagado')->sum('monto');
        $pendientesCount = collect($pagos)->where('estatus', 'Pendiente')->count();
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">
        <!-- 1. Total Liquidado -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs flex items-center justify-between transition-colors">
            <div class="space-y-1">
                <span class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-black tracking-wider block">Total Acreditado</span>
                <p class="text-2xl font-black text-slate-900 dark:text-slate-100 font-mono">${{ number_format($totalPagado, 2) }}</p>
                <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                    <span class="material-icons-round text-xs">verified</span> Pagos validados
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-100 dark:border-emerald-900 shadow-2xs">
                <span class="material-icons-round text-2xl">check_circle</span>
            </div>
        </div>

        <!-- 2. Pagos en Revisión -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs flex items-center justify-between transition-colors">
            <div class="space-y-1">
                <span class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-black tracking-wider block">En Validación</span>
                <p class="text-2xl font-black text-amber-600 dark:text-amber-400 font-mono">{{ $pendientesCount }}</p>
                <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">Comprobantes por cotejar</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center border border-amber-100 dark:border-amber-900 shadow-2xs">
                <span class="material-icons-round text-2xl">hourglass_top</span>
            </div>
        </div>

        <!-- 3. Total Movimientos -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs flex items-center justify-between transition-colors">
            <div class="space-y-1">
                <span class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-black tracking-wider block">Transacciones</span>
                <p class="text-2xl font-black text-slate-900 dark:text-slate-100 font-mono">{{ count($pagos) }}</p>
                <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">Historial completo</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-custom-light text-custom-primary flex items-center justify-center border border-custom-primary/30 shadow-2xs">
                <span class="material-icons-round text-2xl">receipt_long</span>
            </div>
        </div>
    </div>

    <!-- ALERTAS DE ÉXITO Y ERROR -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 p-4 md:p-5 rounded-2xl flex items-center justify-between shadow-3xs font-semibold text-xs md:text-sm">
            <div class="flex items-center gap-3">
                <span class="material-icons-round text-xl text-emerald-600 dark:text-emerald-400">check_circle</span>
                <p>{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-100 cursor-pointer">
                <span class="material-icons-round text-base">close</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900 text-rose-800 dark:text-rose-200 p-4 md:p-5 rounded-2xl space-y-2 shadow-3xs text-xs">
            <div class="flex items-center gap-2 font-bold text-sm">
                <span class="material-icons-round text-lg text-rose-600 dark:text-rose-400">error</span>
                <span>Por favor revisa los siguientes errores:</span>
            </div>
            <ul class="list-disc pl-8 space-y-1 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- COLUMNA IZQUIERDA: HISTORIAL DE PAGOS (TABLA) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden transition-colors">
                
                <div class="p-6 md:p-7 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/60 dark:bg-slate-800/40">
                    <div>
                        <div class="flex items-center gap-2 text-custom-primary text-xs font-black uppercase tracking-widest mb-1">
                            <span class="material-icons-round text-base">payments</span>
                            <span>Tesorería Escolar</span>
                        </div>
                        <h2 class="text-lg md:text-xl font-black text-slate-900 dark:text-slate-100">
                            Historial y Control de Pagos
                        </h2>
                        <p class="text-slate-500 dark:text-slate-400 text-xs mt-0.5">Comprobantes presentados ante el área de Glosa y Finanzas.</p>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-400 text-[10px] font-black uppercase tracking-wider">
                                <th class="p-4.5 pl-6">Concepto</th>
                                <th class="p-4.5 text-right">Monto</th>
                                <th class="p-4.5 text-center">Fecha Pago</th>
                                <th class="p-4.5">Ref. Bancaria</th>
                                <th class="p-4.5 text-center pr-6">Estatus</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-200 text-xs">
                            @forelse($pagos as $pago)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="p-4.5 pl-6 font-bold text-slate-900 dark:text-slate-100 text-sm">
                                        {{ $pago->concepto }}
                                    </td>
                                    
                                    <td class="p-4.5 text-right font-mono font-black text-slate-900 dark:text-slate-100 text-sm md:text-base">
                                        ${{ number_format($pago->monto, 2) }}
                                    </td>
                                    
                                    <td class="p-4.5 text-center font-mono text-slate-500 dark:text-slate-400 text-xs">
                                        {{ $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') : '—' }}
                                    </td>
                                    
                                    <td class="p-4.5 font-mono text-xs">
                                        @if($pago->referencia_bancaria)
                                            <span class="bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-bold px-2.5 py-1 rounded-lg border border-slate-200 dark:border-slate-700">
                                                {{ $pago->referencia_bancaria }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 dark:text-slate-500 italic">No registrada</span>
                                        @endif
                                    </td>
                                    
                                    <td class="p-4.5 text-center pr-6">
                                        @switch($pago->estatus)
                                            @case('Pagado')
                                                <span class="inline-flex items-center text-emerald-700 dark:text-emerald-300 font-black bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-900 px-3 py-1 rounded-full text-[10px] uppercase tracking-wider">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Liquidado
                                                </span>
                                                @break
                                            @case('Pendiente')
                                                <span class="inline-flex items-center text-amber-700 dark:text-amber-300 font-black bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-900 px-3 py-1 rounded-full text-[10px] uppercase tracking-wider">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span> En Revisión
                                                </span>
                                                @break
                                            @case('Vencido')
                                                <span class="inline-flex items-center text-rose-700 dark:text-rose-300 font-black bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900 px-3 py-1 rounded-full text-[10px] uppercase tracking-wider">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span> Vencido
                                                </span>
                                                @break
                                            @case('Condonado')
                                                <span class="inline-flex items-center text-indigo-700 dark:text-indigo-300 font-black bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-900 px-3 py-1 rounded-full text-[10px] uppercase tracking-wider">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-1.5"></span> Beca / Condonado
                                                </span>
                                                @break
                                            @default
                                                <span class="inline-flex items-center text-slate-700 dark:text-slate-300 font-black bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-3 py-1 rounded-full text-[10px] uppercase tracking-wider">
                                                    {{ $pago->estatus }}
                                                </span>
                                        @endswitch
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-12 text-center text-slate-400 dark:text-slate-500 font-medium space-y-2">
                                        <span class="material-icons-round text-4xl block text-slate-300 dark:text-slate-700">point_of_sale</span>
                                        <p class="text-sm">No cuentas con cobros o historial de transacciones en este periodo escolar.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: FORMULARIO DE REPORTE DE COMPROBANTE -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white dark:bg-slate-900 p-6 md:p-7 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs space-y-6 transition-colors">
                
                <div class="border-b border-slate-100 dark:border-slate-800 pb-4">
                    <h3 class="text-base font-black text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        <span class="material-icons-round text-custom-primary text-xl">upload_file</span> 
                        Reportar Nuevo Depósito
                    </h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs mt-1">Carga tu comprobante bancario para cotejo y validación de matrícula.</p>
                </div>

                <form action="{{ route('alumnoPagos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                    @csrf
                    
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Concepto del Pago *</label>
                        <select name="concepto" required 
                                class="w-full bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 rounded-2xl p-3 font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all cursor-pointer">
                            <option value="" disabled selected>-- Selecciona un concepto --</option>
                            <option value="Colegiatura Ordinaria" {{ old('concepto') == 'Colegiatura Ordinaria' ? 'selected' : '' }}>Colegiatura Ordinaria</option>
                            <option value="Reinscripción" {{ old('concepto') == 'Reinscripción' ? 'selected' : '' }}>Reinscripción Semestral</option>
                            <option value="Derecho de Examen" {{ old('concepto') == 'Derecho de Examen' ? 'selected' : '' }}>Derecho de Examen Extraordinario</option>
                            <option value="Trámite de Titulación" {{ old('concepto') == 'Trámite de Titulación' ? 'selected' : '' }}>Trámite de Titulación</option>
                            <option value="Constancia" {{ old('concepto') == 'Constancia' ? 'selected' : '' }}>Constancia de Estudios Oficial</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Monto Depositado ($ MXN) *</label>
                        <input type="number" name="monto" value="{{ old('monto') }}" step="0.01" min="1" required 
                               class="w-full bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 rounded-2xl p-3 font-mono font-bold text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all" 
                               placeholder="Ej: 450.00">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Folio / Referencia Bancaria *</label>
                        <input type="text" name="referencia_bancaria" value="{{ old('referencia_bancaria') }}" required 
                               class="w-full bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 rounded-2xl p-3 font-mono font-bold text-slate-900 dark:text-slate-100 uppercase focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all" 
                               placeholder="Ej: BBVA-92837482">
                    </div>

                    <!-- DROPZONE PERSONALIZADO -->
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Comprobante de Pago *</label>
                        <div class="relative border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-custom-primary dark:hover:border-custom-primary rounded-2xl p-5 text-center transition-all group bg-slate-50/50 dark:bg-slate-800/30">
                            <input type="file" name="comprobante" id="fileComprobante" accept="image/*,application/pdf" required 
                                   onchange="actualizarNombreArchivo(this)"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <div class="space-y-1.5 pointer-events-none">
                                <span class="material-icons-round text-3xl text-slate-400 group-hover:text-custom-primary transition-colors">cloud_upload</span>
                                <p id="nombreArchivoLabel" class="font-bold text-slate-700 dark:text-slate-300 text-xs truncate">
                                    Haz clic para adjuntar o arrastra aquí
                                </p>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">Formatos válidos: PDF, JPG, PNG (Máx 5MB)</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3.5 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold text-xs rounded-2xl shadow-sm hover:shadow-md transition-all cursor-pointer flex items-center justify-center gap-2">
                            <span class="material-icons-round text-base">send</span> 
                            <span>Registrar Comprobante</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</main>

<script>
    function actualizarNombreArchivo(input) {
        const label = document.getElementById('nombreArchivoLabel');
        if (input.files && input.files[0]) {
            label.innerText = '📎 ' + input.files[0].name;
            label.classList.add('text-custom-primary', 'font-black');
        } else {
            label.innerText = 'Haz clic para adjuntar o arrastra aquí';
            label.classList.remove('text-custom-primary', 'font-black');
        }
    }
</script>
@endsection
@extends('cpanel/plantillaorientacion')
@section('title', 'Alerta Inasistencia')
@section('content')
     <!-- CONTENIDO PRINCIPAL -->
        <main class="flex-1 max-w-6xl w-full mx-auto p-4 md:p-6 space-y-6">
            
            <!-- CONTENEDOR INFORMATIVO / FILTRO -->
            <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-1">
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-600 animate-pulse"></span>
                        <span class="text-xs font-bold text-red-700 uppercase tracking-wider">Reporte Crítico de Asistencias</span>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900">Alumnos con Menos del 80% de Asistencia</h2>
                    <p class="text-xs text-slate-500">Listado consolidado de estudiantes que se encuentran en riesgo de perder derecho a examen.</p>
                </div>
                
                <div class="flex gap-2">
                    <select class="bg-slate-50 border border-slate-300 rounded-xl p-2.5 text-xs font-semibold focus:ring-1 focus:ring-slate-500 focus:outline-hidden">
                        <option>Todos los Grupos Críticos</option>
                        <option>6° Semestre - Grupo A</option>
                        <option>4° Semestre - Grupo B</option>
                    </select>
                </div>
            </div>

            <!-- TABLA DE ALUMNOS EN RIESGO -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[11px] font-bold uppercase tracking-wider">
                                <th class="p-4">Alumno</th>
                                <th class="p-4">Grupo / Especialidad</th>
                                <th class="p-4 text-center">Faltas Acum.</th>
                                <th class="p-4 text-center">Porcentaje</th>
                                <th class="p-4">Tutor Registrado</th>
                                <th class="p-4 text-right">Acción Preventiva</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-xs">
                            
                            <!-- Fila Alumno 1 -->
                            <tr class="hover:bg-slate-50/50 transition-colors bg-red-50/10">
                                <td class="p-4">
                                    <div class="font-bold text-slate-900">Mendoza Vazquez Carlos</div>
                                    <div class="text-[10px] text-slate-400 font-mono mt-0.5">Matrícula: 22240105</div>
                                </td>
                                <td class="p-4 text-slate-600 font-medium">
                                    6° Semestre - Grupo A
                                    <div class="text-[10px] text-slate-400">Téc. Soporte y Mantenimiento</div>
                                </td>
                                <td class="p-4 text-center font-bold text-red-600">6 Faltas</td>
                                <td class="p-4 text-center">
                                    <span class="inline-flex items-center gap-1 bg-red-600 text-white font-black px-2 py-1 rounded-md text-[11px]">
                                        <span class="material-icons-round text-xs">warning</span> 75.0%
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="font-semibold text-slate-800">Sra. María Elena Vazquez</div>
                                    <div class="text-[10px] text-slate-400 flex items-center mt-0.5">
                                        <span class="material-icons-round text-xs mr-1 text-slate-400">phone_iphone</span> Tel: 248-123-4567
                                    </div>
                                </td>
                                <td class="p-4 text-right">
                                    <button class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-[11px] font-bold rounded-xl shadow-2xs transition-all flex items-center gap-1.5 ml-auto cursor-pointer">
                                        <span class="material-icons-round text-sm">emergency_share</span> Enviar Alerta al Tutor
                                    </button>
                                </td>
                            </tr>

                            <!-- Fila Alumno 2 -->
                            <tr class="hover:bg-slate-50/50 transition-colors bg-red-50/10">
                                <td class="p-4">
                                    <div class="font-bold text-slate-900">Juárez Reyes María Fernanda</div>
                                    <div class="text-[10px] text-slate-400 font-mono mt-0.5">Matrícula: 22240089</div>
                                </td>
                                <td class="p-4 text-slate-600 font-medium">
                                    4° Semestre - Grupo B
                                    <div class="text-[10px] text-slate-400">Téc. en Programación</div>
                                </td>
                                <td class="p-4 text-center font-bold text-red-600">5 Faltas</td>
                                <td class="p-4 text-center">
                                    <span class="inline-flex items-center gap-1 bg-red-500 text-white font-black px-2 py-1 rounded-md text-[11px]">
                                        <span class="material-icons-round text-xs">warning</span> 79.1%
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="font-semibold text-slate-800">Sr. Roberto Juárez Castro</div>
                                    <div class="text-[10px] text-slate-400 flex items-center mt-0.5">
                                        <span class="material-icons-round text-xs mr-1 text-slate-400">phone_iphone</span> Tel: 248-987-6543
                                    </div>
                                </td>
                                <td class="p-4 text-right">
                                    <button class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-[11px] font-bold rounded-xl shadow-2xs transition-all flex items-center gap-1.5 ml-auto cursor-pointer">
                                        <span class="material-icons-round text-sm">emergency_share</span> Enviar Alerta al Tutor
                                    </button>
                                </td>
                            </tr>

                            <!-- Fila Alumno 3 (Caso límite rozando el riesgo) -->
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="p-4">
                                    <div class="font-bold text-slate-900">López Portillo Ana María</div>
                                    <div class="text-[10px] text-slate-400 font-mono mt-0.5">Matrícula: 22240150</div>
                                </td>
                                <td class="p-4 text-slate-600 font-medium">
                                    6° Semestre - Grupo A
                                    <div class="text-[10px] text-slate-400">Téc. Soporte y Mantenimiento</div>
                                </td>
                                <td class="p-4 text-center text-slate-600 font-medium">4 Faltas</td>
                                <td class="p-4 text-center">
                                    <span class="inline-flex items-center gap-1 bg-amber-500 text-white font-bold px-2 py-1 rounded-md text-[11px]">
                                        <span class="material-icons-round text-xs">error_outline</span> 83.3%
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="font-semibold text-slate-800">Sra. Alicia Portillo Galicia</div>
                                    <div class="text-[10px] text-slate-400 flex items-center mt-0.5">
                                        <span class="material-icons-round text-xs mr-1 text-slate-400">phone_iphone</span> Tel: 248-555-1234
                                    </div>
                                </td>
                                <td class="p-4 text-right">
                                    <!-- Botón atenuado o con estilo preventivo por estar en semáforo amarillo -->
                                    <button class="px-3 py-2 border border-amber-500 hover:bg-amber-50 text-amber-700 text-[11px] font-bold rounded-xl shadow-3xs transition-all flex items-center gap-1.5 ml-auto cursor-pointer">
                                        <span class="material-icons-round text-sm">sms</span> Notificación Preventiva
                                    </button>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PANEL INFERIOR: ACCIÓN DEL BOTÓN EN EL SISTEMA -->
            <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl text-xs text-blue-800 leading-relaxed flex items-start space-x-3">
                <span class="material-icons-round text-base text-blue-600 mt-0.5">bolt</span>
                <div>
                    <strong>Automatización de Alertas SUIE:</strong> Al presionar el botón <code class="bg-blue-100 px-1 rounded font-mono font-bold">Enviar Alerta al Tutor</code>, el sistema genera de forma automatizada un reporte con la bitácora exacta de las fechas faltadas y lo envía al teléfono registrado del padre de familia.
                </div>
            </div>

        </main>
@endsection

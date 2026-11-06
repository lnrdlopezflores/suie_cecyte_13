<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUIE - Lista de Asistencia - {{ $carga->materia }}</title>
    <style>
        @page {
            size: letter landscape;
            margin: 8mm 10mm;
        }

        :root {
            --primary: #1e293b;
            --primary-accent: #0f172a;
            --border-color: #cbd5e1;
            --bg-alt: #f8fafc;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            font-size: 9.5px;
            color: #334155;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }

        /* --- BOTÓN IMPRESIÓN --- */
        .no-print {
            margin-bottom: 12px;
            text-align: right;
        }

        .btn-print {
            padding: 8px 18px;
            background-color: #4f46e5;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 700;
            font-size: 12px;
            box-shadow: 0 2px 4px rgba(79, 70, 229, 0.2);
            transition: all 0.2s ease;
        }

        .btn-print:hover {
            background-color: #4338ca;
        }

        /* --- ENCABEZADO INSTITUCIONAL --- */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .header-title h1 {
            margin: 0;
            font-size: 15px;
            font-weight: 900;
            color: var(--primary-accent);
            text-transform: uppercase;
            letter-spacing: -0.2px;
        }

        .header-title p {
            margin: 2px 0 0 0;
            font-size: 10px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .header-meta {
            text-align: right;
            font-size: 9px;
            color: #475569;
            background-color: var(--bg-alt);
            padding: 6px 12px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .header-meta div {
            margin-bottom: 2px;
        }

        .header-meta div:last-child {
            margin-bottom: 0;
        }

        /* --- TABLA PRINCIPAL --- */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 8px;
        }

        th, td {
            border: 1px solid var(--border-color);
            text-align: center;
            padding: 3px 1px;
            height: 19px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        th {
            background-color: #e2e8f0;
            color: #0f172a;
            font-size: 8.5px;
            font-weight: 800;
            line-height: 1.1;
        }

        th small {
            display: block;
            font-size: 7px;
            font-weight: 700;
            color: #64748b;
            margin-top: 1px;
            text-transform: uppercase;
        }

        /* Anchos específicos de columnas */
        .th-np { width: 22px; background-color: #cbd5e1; }
        .th-nombre { width: 195px; text-align: left; padding-left: 6px; background-color: #cbd5e1; }
        .th-totales { width: 42px; background-color: #cbd5e1; font-weight: 800; }

        /* Filas y celdas */
        tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .td-np {
            font-weight: 700;
            color: #64748b;
            font-size: 8.5px;
        }

        .td-nombre {
            text-align: left;
            padding-left: 6px;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .td-total {
            font-weight: 900;
            background-color: #f1f5f9;
            color: #0f172a;
            font-size: 10px;
        }

        /* Simbología de asistencias */
        .simbolo-asistencia {
            font-size: 13px;
            font-weight: 900;
            color: #0f172a;
            line-height: 1;
        }

        .simbolo-falta {
            font-size: 11px;
            font-weight: 900;
            color: #ef4444;
        }

        .simbolo-justificado {
            font-size: 9px;
            font-weight: 800;
            color: #d97706;
        }

        .simbolo-retardo {
            font-size: 9px;
            font-weight: 800;
            color: #2563eb;
        }

        /* --- PIE DE PÁGINA Y NOTAS --- */
        .footer-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 10px;
            padding-top: 4px;
        }

        .legend-box {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 8.5px;
            color: #475569;
            background-color: var(--bg-alt);
            padding: 5px 12px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .signature-box {
            display: flex;
            gap: 40px;
            text-align: center;
        }

        .signature-line {
            width: 160px;
            border-top: 1px solid #94a3b8;
            padding-top: 3px;
            font-size: 8px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
        }

        @media print {
            .no-print { display: none !important; }
            body { background-color: transparent; }
            tbody tr:nth-child(even) { background-color: #f8fafc !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            th, .td-total, .header-meta, .legend-box { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

    @php
        // Días hábiles (lunes a viernes) del mes
        $letrasDia = [1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V'];
        $diasHabiles = [];
        for ($d = 1; $d <= $diasDelMes; $d++) {
            $fechaDia = \Carbon\Carbon::createFromDate($anio, $mes, $d);
            if ($fechaDia->isWeekday()) {
                $diasHabiles[$d] = $letrasDia[$fechaDia->dayOfWeekIso];
            }
        }
    @endphp

    {{-- BOTÓN DE IMPRESIÓN --}}
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            🖨️ Imprimir / Guardar en PDF
        </button>
    </div>

    {{-- ENCABEZADO --}}
    <div class="header-container">
        <div class="header-title">
            <h1>Reporte de Asistencia Mensual</h1>
            <p><strong>Materia:</strong> {{ $carga->materia }}</p>
        </div>
        <div class="header-meta">
            <div><strong>Mes:</strong> {{ strtoupper($nombreMes) }} {{ $anio }}</div>
            <div><strong>Grupo:</strong> {{ $carga->semestre }}° "{{ $carga->grupo }}" | <strong>Especialidad:</strong> {{ $carga->especialidad }}</div>
        </div>
    </div>

    {{-- TABLA DE ASISTENCIAS --}}
    <table>
        <thead>
            <tr>
                <th class="th-np">NP</th>
                <th class="th-nombre">Nombre del Alumno</th>
                @foreach($diasHabiles as $d => $letra)
                    <th>
                        {{ str_pad($d, 2, '0', STR_PAD_LEFT) }}
                        <small>{{ $letra }}</small>
                    </th>
                @endforeach
                <th class="th-totales">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($alumnos as $index => $al)
                @php
                    $idAlumno = (int) $al->alumno_id;
                @endphp
                <tr>
                    <td class="td-np">{{ $index + 1 }}</td>
                    <td class="td-nombre">{{ $al->apellido_paterno }} {{ $al->apellido_materno }} {{ $al->nombre }}</td>

                    @foreach($diasHabiles as $d => $letra)
                        @php
                            $val = $matrizAsistencias[$idAlumno][$d] ?? '';
                        @endphp
                        <td>
                            @if($val === '•')
                                <span class="simbolo-asistencia">•</span>
                            @elseif($val === '/')
                                <span class="simbolo-falta">/</span>
                            @elseif($val === 'J' || $val === 'j')
                                <span class="simbolo-justificado">J</span>
                            @elseif($val === 'R' || $val === 'r')
                                <span class="simbolo-retardo">R</span>
                            @else
                                {{ $val }}
                            @endif
                        </td>
                    @endforeach

                    <td class="td-total">
                        {{ isset($totalesAsistencias[$idAlumno]) ? (float)$totalesAsistencias[$idAlumno] : 0 }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-container">
        <div class="legend-box">
            <span style="font-weight: 800; color: #0f172a;">Simbología:</span>
            <div class="legend-item"><span class="simbolo-asistencia">•</span> Asistencia (+1)</div>
            <div class="legend-item"><span class="simbolo-falta">/</span> Falta (0)</div>
            <div class="legend-item"><span class="simbolo-justificado">J</span> Justificado (+1)</div>
            <div class="legend-item"><span class="simbolo-retardo">R</span> Retardo (+0.5)</div>
        </div>

        <div class="signature-box">
            <div class="signature-line">Firma del Docente</div>
            <div class="signature-line">Vo.Bo. Control Escolar</div>
        </div>
    </div>

</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Asistencia - {{ $carga->materia }}</title>
    <style>
        @page {
            size: letter landscape;
            margin: 10mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .header {
            margin-bottom: 12px;
            border-bottom: 2px solid #334155;
            padding-bottom: 6px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header p {
            margin: 3px 0 0 0;
            font-size: 11px;
            color: #475569;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #94a3b8;
            text-align: center;
            padding: 3px 1px;
            height: 18px;
            word-wrap: break-word;
        }
        th {
            background-color: #cbd5e1;
            font-size: 9px;
            font-weight: bold;
        }
        th small {
            display: block;
            font-size: 7px;
            font-weight: normal;
            color: #475569;
        }
        .th-np { width: 25px; }
        .th-nombre { width: 180px; text-align: left; padding-left: 5px; }
        .th-totales { width: 45px; background-color: #e2e8f0; font-weight: bold; }
        .td-nombre { text-align: left; padding-left: 5px; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .simbolo-asistencia { font-size: 14px; font-weight: bold; line-height: 1; }
        .simbolo-falta { font-size: 12px; font-weight: bold; color: #dc2626; }

        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    @php
        // Solo días hábiles (lunes a viernes) del mes seleccionado
        $letrasDia = [1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V'];
        $diasHabiles = [];
        for ($d = 1; $d <= $diasDelMes; $d++) {
            $fechaDia = \Carbon\Carbon::createFromDate($anio, $mes, $d);
            if ($fechaDia->isWeekday()) {
                $diasHabiles[$d] = $letrasDia[$fechaDia->dayOfWeekIso];
            }
        }
    @endphp

    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #4f46e5; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">
            🖨️ Imprimir / Guardar como PDF
        </button>
    </div>

    <div class="header">
        <h2>Reporte de Asistencia Mensual - {{ strtoupper($nombreMes) }}</h2>
        <p><strong>Materia:</strong> {{ $carga->materia }} | <strong>Grupo:</strong> {{ $carga->semestre }}° "{{ $carga->grupo }}" | <strong>Especialidad:</strong> {{ $carga->especialidad }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="th-np">NP</th>
                <th class="th-nombre">Nombre del Alumno</th>
                @foreach($diasHabiles as $d => $letra)
                    <th>{{ str_pad($d, 2, '0', STR_PAD_LEFT) }}<small>{{ $letra }}</small></th>
                @endforeach
                <th class="th-totales">Total Asist.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($alumnos as $index => $al)
                @php
                    $idAlumno = (int) $al->alumno_id;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
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
                            @else
                                {{ $val }}
                            @endif
                        </td>
                    @endforeach

                    <td style="font-weight: bold; background-color: #f8fafc;">
                        {{ $totalesAsistencias[$idAlumno] ?? 0 }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 15px; font-size: 9px; color: #64748b; display: flex; gap: 20px;">
        <span><strong>Simbología:</strong></span>
        <span>Asistencia: <strong>•</strong></span>
        <span>Falta: <strong>/</strong></span>
        <span>Justificado: <strong>J</strong></span>
        <span>Retardo: <strong>R</strong></span>
    </div>

</body>
</html>
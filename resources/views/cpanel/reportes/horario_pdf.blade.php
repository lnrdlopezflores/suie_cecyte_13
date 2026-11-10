<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Horario Semanal - {{ $nombreDocente }}</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; font-size: 11px; color: #1e293b; margin: 15px; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #841B44; padding-bottom: 8px; }
        .header h1 { margin: 0; font-size: 16px; color: #841B44; text-transform: uppercase; letter-spacing: 0.5px; }
        .header p { margin: 3px 0 0; font-size: 11px; color: #64748b; }
        
        table { width: 100%; border-collapse: collapse; table-layout: fixed; margin-top: 10px; }
        th, td { border: 1px solid #cbd5e1; padding: 5px; text-align: center; vertical-align: middle; word-wrap: break-word; }
        th { background-color: #f8fafc; color: #475569; text-transform: uppercase; font-size: 10px; font-weight: 800; height: 26px; }
        .col-hora { width: 110px; background-color: #f8fafc; font-weight: 700; color: #334155; font-size: 9.5px; }
        
        .card-clase { background-color: #f1f5f9; border-left: 3px solid #6366f1; padding: 4px 6px; border-radius: 4px; text-align: left; }
        .card-clase strong { display: block; color: #0f172a; font-size: 10px; font-weight: 700; line-height: 1.2; }
        .card-clase .detalles { font-size: 9px; color: #475569; margin-top: 1px; }
        .card-clase .aula { display: inline-block; background-color: #e2e8f0; color: #334155; font-size: 8.5px; font-weight: 700; padding: 1px 4px; border-radius: 3px; margin-top: 2px; }
        
        .receso-row td { background-color: #fef3c7; color: #92400e; font-weight: 800; font-size: 9.5px; letter-spacing: 0.5px; text-transform: uppercase; padding: 5px; }

        .btn-print { background: #841B44; color: white; border: none; padding: 7px 14px; border-radius: 6px; font-weight: bold; cursor: pointer; float: right; font-size: 11px; }
        @media print { 
            .btn-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="btn-print">Imprimir / Guardar PDF</button>

    <div class="header">
        <h1>Horario Académico Semanal</h1>
        <p><strong>Docente:</strong> {{ $nombreDocente }} | <strong>Ciclo Escolar:</strong> 2026 - 2027</p>
    </div>

    @php
        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

        // Mapeo de equivalencias de días y abreviaturas
        $diasMapa = [
            'Lunes'     => ['lunes', 'lun', 'lu'],
            'Martes'    => ['martes', 'mar', 'ma'],
            'Miércoles' => ['miércoles', 'miercoles', 'mié', 'mie', 'mi'],
            'Jueves'    => ['jueves', 'jue', 'ju'],
            'Viernes'   => ['viernes', 'vie', 'vi'],
        ];

        // Módulos en minutos desde medianoche (8:00 AM = 480 min)
        $modulos = [
            ['inicio' => 480, 'fin' => 530, 'texto' => '08:00 - 08:50', 'es_receso' => false], // 8:00 AM - 8:50 AM
            ['inicio' => 530, 'fin' => 580, 'texto' => '08:50 - 09:40', 'es_receso' => false], // 8:50 AM - 9:40 AM
            ['inicio' => 580, 'fin' => 630, 'texto' => '09:40 - 10:30', 'es_receso' => false], // 9:40 AM - 10:30 AM
            ['inicio' => 630, 'fin' => 650, 'texto' => '10:30 - 10:50', 'es_receso' => true, 'label' => 'RECESO INSTITUCIONAL'],
            ['inicio' => 650, 'fin' => 700, 'texto' => '10:50 - 11:40', 'es_receso' => false], // 10:50 AM - 11:40 AM
            ['inicio' => 700, 'fin' => 750, 'texto' => '11:40 - 12:30', 'es_receso' => false], // 11:40 AM - 12:30 PM
            ['inicio' => 750, 'fin' => 800, 'texto' => '12:30 - 13:20', 'es_receso' => false], // 12:30 PM - 1:20 PM
            ['inicio' => 800, 'fin' => 850, 'texto' => '13:20 - 14:10', 'es_receso' => false], // 1:20 PM - 2:10 PM
            ['inicio' => 850, 'fin' => 900, 'texto' => '14:10 - 15:00', 'es_receso' => false], // 2:10 PM - 3:00 PM
        ];

        // Convertir strings "HH:MM" a minutos transcurridos
        $horaAMinutos = function($horaStr) {
            $partes = explode(':', trim($horaStr));
            if (count($partes) < 2) return 0;
            return ((int)$partes[0] * 60) + (int)$partes[1];
        };

        // Inicializar matriz limpia
        $matrizHorario = [];
        foreach ($modulos as $mod) {
            if (!$mod['es_receso']) {
                foreach ($diasSemana as $dia) {
                    $matrizHorario[$mod['texto']][$dia] = null;
                }
            }
        }

        // Procesamiento de las cargas del docente
        foreach ($cargas as $carga) {
            $horarioTexto = mb_strtolower($carga->horario);

            // Reemplazar caracteres especiales y separar por comas/paréntesis
            $horarioLimpio = str_replace(['(', ')'], ' ', $horarioTexto);
            $bloquesClase = explode(',', $horarioLimpio);

            foreach ($bloquesClase as $bloqueClase) {
                $bloqueClase = trim($bloqueClase);
                if (empty($bloqueClase)) continue;

                // 1. Detectar el día correspondiente
                $diaEncontrado = null;
                foreach ($diasMapa as $nombreDia => $aliases) {
                    foreach ($aliases as $alias) {
                        if (preg_match('/(?:\b|_)' . preg_quote($alias, '/') . '(?:\b|_)/u', $bloqueClase)) {
                            $diaEncontrado = $nombreDia;
                            break 2;
                        }
                    }
                }

                if (!$diaEncontrado) continue;

                // 2. Extraer rango de tiempo (ej. "08:00 - 08:50" o "08:50 - 10:30")
                if (preg_match('/(\d{1,2}:\d{2})\s*[-a]\s*(\d{1,2}:\d{2})/', $bloqueClase, $coincidencias)) {
                    $minInicioClase = $horaAMinutos($coincidencias[1]);
                    $minFinClase    = $horaAMinutos($coincidencias[2]);

                    // Ajuste nocturno o corrección si ponen 00:30 en lugar de 12:30 / 15:00
                    if ($minFinClase < $minInicioClase) {
                        $minFinClase += 720; // +12 horas
                    }

                    // 3. Evaluar coincidencia con cada módulo por superposición de tiempo
                    foreach ($modulos as $mod) {
                        if ($mod['es_receso']) continue;

                        if ($minInicioClase < $mod['fin'] && $minFinClase > $mod['inicio']) {
                            $matrizHorario[$mod['texto']][$diaEncontrado] = $carga;
                        }
                    }
                }
            }
        }
    @endphp

    <table>
        <thead>
            <tr>
                <th class="col-hora">Módulo / Horario</th>
                @foreach($diasSemana as $dia)
                    <th>{{ $dia }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($modulos as $mod)
                @if($mod['es_receso'])
                    <tr class="receso-row">
                        <td class="col-hora">{{ $mod['texto'] }}</td>
                        <td colspan="5">{{ $mod['label'] }}</td>
                    </tr>
                @else
                    <tr>
                        <td class="col-hora">{{ $mod['texto'] }}</td>

                        @foreach($diasSemana as $dia)
                            @php $clase = $matrizHorario[$mod['texto']][$dia] ?? null; @endphp
                            <td>
                                @if($clase)
                                    <div class="card-clase">
                                        <strong>{{ $clase->materia }}</strong>
                                        <div class="detalles">
                                            {{ $clase->semestre }}° "{{ $clase->grupo }}" ({{ $clase->especialidad }})
                                        </div>
                                        <div class="aula">
                                            Aula: {{ $clase->aula ?? 'N/A' }}
                                        </div>
                                    </div>
                                @else
                                    <span style="color: #cbd5e1;">-</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</body>
</html>
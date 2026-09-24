<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Encryption\DecryptException;
use Carbon\Carbon;

class ReportesIngresosController extends Controller
{
    public function index(Request $request)
    {
        $anio = $request->input('anio', Carbon::now()->year);

        // 1. Obtener todos los pagos del año seleccionado
        $pagos = DB::table('pagos')
            ->whereYear('created_at', $anio)
            ->select('id', 'monto', 'estatus', 'created_at', 'fecha_pago')
            ->get();

        // 2. Desencriptar y normalizar montos
        $pagos->transform(function ($pago) {
            $montoLimpio = $pago->monto;
            try {
                if (is_string($montoLimpio) && (str_starts_with($montoLimpio, 'ey') || strlen($montoLimpio) > 50)) {
                    $montoLimpio = decrypt($montoLimpio);
                }
            } catch (DecryptException $e) {}

            $pago->monto_num = is_numeric($montoLimpio) ? (float) $montoLimpio : 0.0;
            $pago->mes = Carbon::parse($pago->created_at)->month;
            return $pago;
        });

        // 3. Métricas para tarjetas
        $totalRecaudado = $pagos->where('estatus', 'Pagado')->sum('monto_num');
        $totalPendiente = $pagos->where('estatus', 'Pendiente')->sum('monto_num');
        $conteoRegistrados = $pagos->count();
        $conteoValidados = $pagos->where('estatus', 'Pagado')->count();

        // 4. Preparar datos mensuales (12 meses)
        $mesesNombres = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        
        $serieRegistrados = array_fill(1, 12, 0);
        $seriePendientes  = array_fill(1, 12, 0);
        $serieValidados   = array_fill(1, 12, 0);
        $serieIngresos    = array_fill(1, 12, 0.0);

        foreach ($pagos as $p) {
            $m = (int) $p->mes;
            if ($m >= 1 && $m <= 12) {
                $serieRegistrados[$m]++;
                if ($p->estatus === 'Pendiente') {
                    $seriePendientes[$m]++;
                } elseif ($p->estatus === 'Pagado') {
                    $serieValidados[$m]++;
                    $serieIngresos[$m] += $p->monto_num;
                }
            }
        }

        return view('cpanel.Pagos.reporte-ingresos', [
            'anio'              => $anio,
            'totalRecaudado'    => $totalRecaudado,
            'totalPendiente'    => $totalPendiente,
            'conteoRegistrados' => $conteoRegistrados,
            'conteoValidados'   => $conteoValidados,
            'meses'             => $mesesNombres,
            'datosRegistrados'  => array_values($serieRegistrados),
            'datosPendientes'   => array_values($seriePendientes),
            'datosValidados'    => array_values($serieValidados),
            'datosIngresos'     => array_values($serieIngresos),
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ValidarPagoController extends Controller
{
    public function index(Request $request)
    {
        $buscar  = $request->input('buscar');
        $estatus = $request->input('estatus');

        // Métricas superiores
        $totalesRaw = DB::table('pagos')
            ->select('estatus', DB::raw('count(*) as total'))
            ->groupBy('estatus')
            ->pluck('total', 'estatus')
            ->toArray();
            
        $totales = array_merge(['Pendiente' => 0, 'Pagado' => 0, 'Vencido' => 0], $totalesRaw);

        // CONSULTA CORREGIDA: Agrega explícitamente los campos de apellidos
        $query = DB::table('pagos')
            ->join('alumnos', 'pagos.alumno_id', '=', 'alumnos.id')
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->select(
                'pagos.*',
                'alumnos.nombre as alumno_nombre',
                'alumnos.apellido_paterno as alumno_paterno',  // <-- AÑADE ESTO
                'alumnos.apellido_materno as alumno_materno',  // <-- AÑADE ESTO
                'usuarios.username'
            );

        if (!empty($estatus)) {
            $query->where('pagos.estatus', '=', $estatus);
        }

        if (!empty($buscar)) {
            $query->where(function ($q) use ($buscar) {
                $q->where('alumnos.nombre', 'LIKE', '%' . $buscar . '%')
                ->orWhere('alumnos.apellido_paterno', 'LIKE', '%' . $buscar . '%')
                ->orWhere('usuarios.username', 'LIKE', '%' . $buscar . '%')
                ->orWhere('pagos.referencia_bancaria', 'LIKE', '%' . $buscar . '%');
            });
        }

        $pagos = $query->orderByRaw("FIELD(pagos.estatus, 'Pendiente', 'Vencido', 'Pagado', 'Condonado') ASC")
                    ->orderBy('pagos.created_at', 'desc')
                    ->paginate(15);

        return view('cpanel.Pagos.indexpagos', compact('pagos', 'totales'));
    }
    /**
     * Muestra la pantalla dividida con el PDF y la cédula de validación.
     */
    public function revisar($id)
    {
        // Recuperar el pago con el nombre del alumno mediante un Join
        $pago = DB::table('pagos')
            ->join('alumnos', 'pagos.alumno_id', '=', 'alumnos.id')
            ->select('pagos.*', 'alumnos.nombre as alumno_nombre')
            ->where('pagos.id', $id)
            ->first();

        if (!$pago) {
            return redirect()->back()->withErrors(['error' => 'El registro de pago no existe.']);
        }

        return view('cpanel/Pagos/ValidarPago', compact('pago'));
    }

    /**
     * Procesa la validación, cambia el estatus a Pagado y genera el Recibo Institucional.
     */
    public function validar(Request $request, $id)
    {
        // 1. Actualizar el estado del pago en la Base de Datos
        DB::table('pagos')->where('id', $id)->update([
            'estatus'    => 'Pagado',
            'fecha_pago' => now()->toDateString(), // Fecha de timbrado de caja
            'updated_at' => now()
        ]);

        // 2. Aquí llamarías a tu lógica de generación de PDF institucional (Ej: DomPDF o Barryvdh\DomPDF)
        // Por ahora redirigimos al flujo para imprimir el comprobante institucional generado
        return redirect()
            ->route('contador.pagos.index')
            ->with('success', "El pago #{$id} ha sido aprobado con éxito.");
    }
}
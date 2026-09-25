<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CobroDirectoController extends Controller
{
    public function index()
    {
        return view('cpanel.Pagos.cobro-directo');
    }

    public function buscarAlumnos(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $alumnos = DB::table('alumnos')
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->join('grupos', 'alumnos.grupo_id', '=', 'grupos.id')
            ->select(
                'alumnos.id',
                'alumnos.nombre',
                'alumnos.apellido_paterno',
                'alumnos.apellido_materno',
                'usuarios.username as matricula',
                'grupos.grupo',
                'grupos.semestre',
                'grupos.especialidad'
            )
            ->get();

        $resultados = [];
        foreach ($alumnos as $al) {
            $nom = $this->desencriptarDato($al->nombre);
            $pat = $this->desencriptarDato($al->apellido_paterno);
            $mat = $this->desencriptarDato($al->apellido_materno);
            $nombreCompleto = trim("{$pat} {$mat} {$nom}");

            if (
                stripos($nombreCompleto, $q) !== false || 
                stripos($al->matricula, $q) !== false
            ) {
                $resultados[] = [
                    'id'              => $al->id,
                    'matricula'       => $al->matricula,
                    'nombre_completo' => $nombreCompleto,
                    'grupo_desc'      => "{$al->semestre}° \"{$al->grupo}\" - {$al->especialidad}"
                ];
            }

            if (count($resultados) >= 8) break;
        }

        return response()->json($resultados);
    }

    public function store(Request $request)
    {
        $request->validate([
            'alumno_id'            => 'required|exists:alumnos,id',
            'concepto'             => 'required|string|max:150',
            'monto'                => 'required|numeric|min:1',
            'metodo_pago'          => 'required|string|in:Efectivo,Terminal Bancaria,Transferencia Inmediata',
            'referencia_operacion' => 'nullable|string|max:60',
            'observaciones'        => 'nullable|string|max:250',
        ]);

        $folioInterno = 'REC-' . strtoupper(Str::random(8));
        $now = Carbon::now();

        $pagoId = DB::table('pagos')->insertGetId([
            'alumno_id'           => $request->alumno_id,
            'concepto'            => trim($request->concepto),
            'monto'               => (float) $request->monto,
            'referencia_bancaria' => encrypt($request->referencia_operacion ?? $folioInterno),
            'estatus'             => 'Pagado',
            'fecha_pago'          => $now->toDateString(),
            'created_at'          => $now,
            'updated_at'          => $now,
        ]);

        return redirect()->route('contador.cobro-directo.comprobante', $pagoId)
            ->with('success', 'Cobro registrado y validado satisfactoriamente.');
    }

    public function imprimirComprobante($id)
    {
        $pago = DB::table('pagos')
            ->join('alumnos', 'pagos.alumno_id', '=', 'alumnos.id')
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->join('grupos', 'alumnos.grupo_id', '=', 'grupos.id')
            ->where('pagos.id', $id)
            ->select(
                'pagos.*',
                'alumnos.nombre',
                'alumnos.apellido_paterno',
                'alumnos.apellido_materno',
                'usuarios.username as matricula',
                'grupos.semestre',
                'grupos.grupo',
                'grupos.especialidad'
            )
            ->first();

        if (!$pago) {
            abort(404, 'Registro de pago no encontrado.');
        }

        $nom = $this->desencriptarDato($pago->nombre);
        $pat = $this->desencriptarDato($pago->apellido_paterno);
        $mat = $this->desencriptarDato($pago->apellido_materno);
        $pago->alumno_nombre = trim("{$pat} {$mat} {$nom}");

        $pago->monto = $this->desencriptarDato($pago->monto);
        $pago->referencia_bancaria = $this->desencriptarDato($pago->referencia_bancaria);

        return view('cpanel.Pagos.comprobante-doble', compact('pago'));
    }

    private function desencriptarDato(?string $val): string
    {
        if (empty($val)) return '';
        try {
            if (is_string($val) && (str_starts_with($val, 'ey') || strlen($val) > 50)) {
                return decrypt($val);
            }
        } catch (\Throwable $e) {}
        return str_replace([' (Plain)', ' (Legacy)'], '', $val);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AlumnoPagosController extends Controller
{
    /**
     * Muestra la tabla de pagos asignados y el formulario de captura.
     */
    public function index()
    {
        $userId = Auth::id();

        // 1. Buscamos al alumno uniendo la tabla grupos para traer semestre y grupo
        $infoAlumno = DB::table('alumnos')
            ->leftJoin('grupos', 'alumnos.grupo_id', '=', 'grupos.id')
            ->where('alumnos.usuario_id', $userId)
            ->select('alumnos.id', 'grupos.semestre', 'grupos.grupo') // Jalamos los campos necesarios
            ->first();

        // 2. Traemos sus pagos usando el ID del alumno
        $pagos = collect([]);
        if ($infoAlumno) {
            $pagos = DB::table('pagos')
                ->where('alumno_id', $infoAlumno->id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Tu lógica de descifrado...
            $pagos->transform(function ($pago) {
                try {
                    $pago->referencia_bancaria = decrypt($pago->referencia_bancaria);
                    $pago->monto = decrypt($pago->monto);
                } catch (\Exception $e) {
                    // Control de datos legacy
                }
                return $pago;
            });
        }

        // 3. ENVIAR AMBAS VARIABLES A LA VISTA
        // Es CRUCIAL que envíes 'infoAlumno' para que el layout pueda leerlo en la barra superior
        return view('cpanel/Pagos/AlumnoPago', compact('pagos', 'infoAlumno'));
    }

    /**
     * Guarda la captura del pago físico e inserta el registro en estatus 'Pendiente'.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();
        $alumno = DB::table('alumnos')->where('usuario_id', $userId)->first();

        if (!$alumno) {
            return redirect()->back()->withErrors(['error' => 'No cuentas con un perfil de alumno activo para reportar pagos.']);
        }

        $request->validate([
            'concepto'            => ['required', 'in:Colegiatura Ordinaria,Reinscripción,Derecho de Examen,Trámite de Titulación,Constancia'],
            'monto'               => ['required', 'numeric', 'min:1'],
            'referencia_bancaria' => ['required', 'string', 'max:50'],
            'comprobante'         => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'], // Máx 4MB
        ]);

        $comprobanteUrl = null;

        // Procesar la subida del documento digital en Laravel
        if ($request->hasFile('comprobante')) {
            $file = $request->file('comprobante');
            // Guardado organizado con matrícula del estudiante para auditoría limpia
            $filename = $alumno->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $comprobanteUrl = $file->storeAs('comprobantes', $filename, 'public');
        }

        // Insertar en la tabla relacional de pagos
        DB::table('pagos')->insert([
            'alumno_id'           => $alumno->id,
            'concepto'            => $request->input('concepto'),
            'monto'               => $request->input('monto'),
            'fecha_pago'          => now()->toDateString(), // Fecha en que captura el alumno
            'estatus'             => 'Pendiente',          // Entra a fila de validación de Control Escolar
            'referencia_bancaria' => strtoupper($request->input('referencia_bancaria')),
            'comprobante_url'     => $comprobanteUrl,
        ]);

        return redirect()
            ->route('alumnoPagos.index')
            ->with('success', 'Tu comprobante de pago ha sido enviado correctamente. Finanzas validará el folio en las próximas horas.');
    }
}
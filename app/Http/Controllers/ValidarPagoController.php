<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Storage;

class ValidarPagoController extends Controller
{
    /**
     * Muestra la tabla de pagos con soporte de descifrado dinámico y filtrado operativo.
     */
    public function index(Request $request)
    {
        $buscar  = $request->input('buscar');
        $estatus = $request->input('estatus');

        // Las métricas se agrupan por estatus (el estatus NO está cifrado, por lo que es rápido)
        $totalesRaw = DB::table('pagos')
            ->select('estatus', DB::raw('count(*) as total'))
            ->groupBy('estatus')
            ->pluck('total', 'estatus')
            ->toArray();
            
        $totales = array_merge(['Pendiente' => 0, 'Pagado' => 0, 'Vencido' => 0], $totalesRaw);

        // Traemos la información relacionada
        $query = DB::table('pagos')
            ->join('alumnos', 'pagos.alumno_id', '=', 'alumnos.id')
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->select(
                'pagos.*',
                'alumnos.nombre as alumno_nombre',
                'alumnos.apellido_paterno as alumno_paterno',  
                'alumnos.apellido_materno as alumno_materno',  
                'usuarios.username' // Matrícula o clave de empleado
            );

        if (!empty($estatus)) {
            $query->where('pagos.estatus', '=', $estatus);
        }

        // ATENCIÓN: El filtrado por LIKE sobre nombres o referencias encriptadas dará cero resultados.
        // Restringimos la barra de búsqueda exclusivamente para buscar por matrícula (username).
        if (!empty($buscar)) {
            $query->where('usuarios.username', 'LIKE', '%' . $buscar . '%');
        }

        $pagosPaginados = $query->orderByRaw("FIELD(pagos.estatus, 'Pendiente', 'Vencido', 'Pagado', 'Condonado') ASC")
            ->orderBy('pagos.created_at', 'desc')
            ->paginate(15);

        // Desciframos dinámicamente la colección antes de renderizar la tabla en el Blade
        $pagosPaginados->getCollection()->transform(function ($pago) {
            try {
                // Descifrado de datos personales del alumno
                $pago->alumno_nombre = decrypt($pago->alumno_nombre);
                $pago->alumno_paterno = decrypt($pago->alumno_paterno);
                $pago->alumno_materno = $pago->alumno_materno ? decrypt($pago->alumno_materno) : null;

                // Descifrado de información del pago
                $pago->monto = decrypt($pago->monto);
                $pago->referencia_bancaria = decrypt($pago->referencia_bancaria);
                // Si el comprobante URL fue guardado cifrado en el store del alumno:
                $pago->comprobante_url = $pago->comprobante_url ? decrypt($pago->comprobante_url) : null;

            } catch (DecryptException $e) {
                // Manejo de compatibilidad para registros previos que estaban en texto plano
                $pago->alumno_nombre = $pago->alumno_nombre . ' (Plain)';
                $pago->referencia_bancaria = $pago->referencia_bancaria . ' (Legacy)';
            }
            return $pago;
        });

        return view('cpanel.Pagos.indexpagos', ['pagos' => $pagosPaginados, 'totales' => $totales]);
    }

    /**
     * Muestra la pantalla de revisión descifrando la URL del PDF y la información del depósito.
     */
    public function revisar($id)
    {
        $pago = DB::table('pagos')
            ->join('alumnos', 'pagos.alumno_id', '=', 'alumnos.id')
            ->select('pagos.*', 'alumnos.nombre as alumno_nombre', 'alumnos.apellido_paterno', 'alumnos.apellido_materno')
            ->where('pagos.id', $id)
            ->first();

        if (!$pago) {
            return redirect()->back()->withErrors(['error' => 'El registro de pago no existe.']);
        }

        // Desciframos de manera segura para alimentar la vista dividida de validación
        try {
            $pago->alumno_nombre = decrypt($pago->alumno_nombre) . ' ' . decrypt($pago->apellido_paterno);
            $pago->monto = decrypt($pago->monto);
            $pago->referencia_bancaria = decrypt($pago->referencia_bancaria);
            $pago->comprobante_url = $pago->comprobante_url ? decrypt($pago->comprobante_url) : null;
        } catch (DecryptException $e) {
            // Manejo alternativo si el registro no estaba cifrado
        }

        return view('cpanel/Pagos/ValidarPago', compact('pago'));
    }

    /**
     * Procesa la validación y cambia el estatus a Pagado.
     */
    public function validar(Request $request, $id)
    {
        // El estatus permanece legible para agrupaciones rápidas e indexación
        DB::table('pagos')->where('id', $id)->update([
            'estatus'    => 'Pagado',
            'fecha_pago' => now()->toDateString(), 
            'updated_at' => now()
        ]);

        return redirect()
            ->route('contador.pagos.index')
            ->with('success', "El pago #{$id} ha sido aprobado con éxito.");
    }

    public function verComprobante($id)
    {
        $pago = DB::table('pagos')->where('id', $id)->first();

        if (!$pago || empty($pago->comprobante_url)) {
            abort(404, 'Comprobante no registrado.');
        }

        // 1. Desencriptar la ruta del archivo si está cifrada
        $rutaArchivo = $pago->comprobante_url;
        try {
            if (str_starts_with($rutaArchivo, 'ey') || strlen($rutaArchivo) > 100) {
                $rutaArchivo = decrypt($rutaArchivo);
            }
        } catch (DecryptException $e) {
            // Se mantiene el valor si estaba en texto plano
        }

        // Limpiar cualquier prefijo 'storage/' o 'public/' sobrante
        $rutaArchivo = ltrim(str_replace(['public/', 'storage/'], '', $rutaArchivo), '/');

        // 2. Comprobar existencia en el disco public
        if (!Storage::disk('public')->exists($rutaArchivo)) {
            abort(404, 'El archivo físico del comprobante no existe en el servidor.');
        }

        $pathFisico = Storage::disk('public')->path($rutaArchivo);
        $mimeType = Storage::disk('public')->mimeType($rutaArchivo) ?? 'application/pdf';

        // 3. Servir el archivo directamente con encabezados HTTP limpios
        return response()->file($pathFisico, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline; filename="comprobante_pago_' . $pago->id . '.pdf"'
        ]);
    }
}
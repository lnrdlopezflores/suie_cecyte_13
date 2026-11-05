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
     * Muestra la tabla de pagos con soporte de descifrado dinámico e independiente por campo.
     */
    public function index(Request $request)
    {
        $buscar  = $request->input('buscar');
        $estatus = $request->input('estatus');

        // Las métricas se agrupan por estatus
        $totalesRaw = DB::table('pagos')
            ->select('estatus', DB::raw('count(*) as total'))
            ->groupBy('estatus')
            ->pluck('total', 'estatus')
            ->toArray();
            
        $totales = array_merge(['Pendiente' => 0, 'Pagado' => 0, 'Vencido' => 0], $totalesRaw);

        // Consultar información de pagos
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

        // Búsqueda por matrícula
        if (!empty($buscar)) {
            $query->where('usuarios.username', 'LIKE', '%' . $buscar . '%');
        }

        $pagosPaginados = $query->orderByRaw("FIELD(pagos.estatus, 'Pendiente', 'Vencido', 'Pagado', 'Condonado') ASC")
            ->orderBy('pagos.created_at', 'desc')
            ->paginate(15);

        // Desciframos de forma granular e independiente cada campo
        $pagosPaginados->getCollection()->transform(function ($pago) {
            
            // 1. Descifrar Nombre del Alumno
            if (is_string($pago->alumno_nombre) && (str_starts_with($pago->alumno_nombre, 'ey') || strlen($pago->alumno_nombre) > 50)) {
                try {
                    $pago->alumno_nombre = decrypt($pago->alumno_nombre);
                } catch (\Throwable $e) {
                    $pago->alumno_nombre = str_replace(' (Plain)', '', $pago->alumno_nombre);
                }
            }

            // 2. Descifrar Apellido Paterno
            if (is_string($pago->alumno_paterno) && (str_starts_with($pago->alumno_paterno, 'ey') || strlen($pago->alumno_paterno) > 50)) {
                try {
                    $pago->alumno_paterno = decrypt($pago->alumno_paterno);
                } catch (\Throwable $e) {
                    $pago->alumno_paterno = str_replace(' (Plain)', '', $pago->alumno_paterno);
                }
            }

            // 3. Descifrar Apellido Materno
            if ($pago->alumno_materno && is_string($pago->alumno_materno) && (str_starts_with($pago->alumno_materno, 'ey') || strlen($pago->alumno_materno) > 50)) {
                try {
                    $pago->alumno_materno = decrypt($pago->alumno_materno);
                } catch (\Throwable $e) {
                    $pago->alumno_materno = str_replace(' (Plain)', '', $pago->alumno_materno);
                }
            }

            // 4. Descifrar Monto
            if (is_string($pago->monto) && (str_starts_with($pago->monto, 'ey') || strlen($pago->monto) > 50)) {
                try {
                    $pago->monto = decrypt($pago->monto);
                } catch (\Throwable $e) {
                    $pago->monto = str_replace(' (Legacy)', '', $pago->monto);
                }
            }

            // 5. Descifrar Referencia Bancaria
            if (is_string($pago->referencia_bancaria) && (str_starts_with($pago->referencia_bancaria, 'ey') || strlen($pago->referencia_bancaria) > 50)) {
                try {
                    $pago->referencia_bancaria = decrypt($pago->referencia_bancaria);
                } catch (\Throwable $e) {
                    // Mantiene el valor en texto plano sin alterar ni añadir etiquetas de error falso
                    $pago->referencia_bancaria = str_replace([' (LEGACY)', ' (Legacy)'], '', $pago->referencia_bancaria);
                }
            } else {
                $pago->referencia_bancaria = str_replace([' (LEGACY)', ' (Legacy)'], '', $pago->referencia_bancaria);
            }

            // 6. Descifrar Comprobante URL
            if ($pago->comprobante_url && is_string($pago->comprobante_url) && (str_starts_with($pago->comprobante_url, 'ey') || strlen($pago->comprobante_url) > 50)) {
                try {
                    $pago->comprobante_url = decrypt($pago->comprobante_url);
                } catch (\Throwable $e) {
                    // Conserva la URL si está en texto plano
                }
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

        // Descifrado seguro e individual por atributo
        try {
            $pago->alumno_nombre = (str_starts_with($pago->alumno_nombre, 'ey') || strlen($pago->alumno_nombre) > 50 ? decrypt($pago->alumno_nombre) : $pago->alumno_nombre) 
                . ' ' 
                . (str_starts_with($pago->apellido_paterno, 'ey') || strlen($pago->apellido_paterno) > 50 ? decrypt($pago->apellido_paterno) : $pago->apellido_paterno);
        } catch (\Throwable $e) {}

        try {
            if (str_starts_with($pago->monto, 'ey') || strlen($pago->monto) > 50) {
                $pago->monto = decrypt($pago->monto);
            }
        } catch (\Throwable $e) {}

        try {
            if (str_starts_with($pago->referencia_bancaria, 'ey') || strlen($pago->referencia_bancaria) > 50) {
                $pago->referencia_bancaria = decrypt($pago->referencia_bancaria);
            }
        } catch (\Throwable $e) {}

        try {
            if ($pago->comprobante_url && (str_starts_with($pago->comprobante_url, 'ey') || strlen($pago->comprobante_url) > 50)) {
                $pago->comprobante_url = decrypt($pago->comprobante_url);
            }
        } catch (\Throwable $e) {}

        return view('cpanel/Pagos/ValidarPago', compact('pago'));
    }

    /**
     * Procesa la validación y cambia el estatus a Pagado.
     */
    public function validar(Request $request, $id)
    {
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

        // Desencriptar la ruta del archivo si está cifrada
        $rutaArchivo = $pago->comprobante_url;
        try {
            if (str_starts_with($rutaArchivo, 'ey') || strlen($rutaArchivo) > 50) {
                $rutaArchivo = decrypt($rutaArchivo);
            }
        } catch (\Throwable $e) {
            // Se mantiene el valor si estaba en texto plano
        }

        // Limpiar cualquier prefijo 'storage/' o 'public/' sobrante
        $rutaArchivo = ltrim(str_replace(['public/', 'storage/'], '', $rutaArchivo), '/');

        // Comprobar existencia en el disco public
        if (!Storage::disk('public')->exists($rutaArchivo)) {
            abort(404, 'El archivo físico del comprobante no existe en el servidor.');
        }

        $pathFisico = Storage::disk('public')->path($rutaArchivo);
        $mimeType = mime_content_type($pathFisico) ?: 'application/pdf';

        return response()->file($pathFisico, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline; filename="comprobante_pago_' . $pago->id . '.pdf"'
        ]);
    }
}
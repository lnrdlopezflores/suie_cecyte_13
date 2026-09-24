<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AlumnoTitulacionDocumentoController extends Controller
{
    // Catálogo exacto del ENUM en la BD
    private $catalogoDocumentos = [
        'Certificado_Secundaria'     => ['nombre' => 'Certificado de Secundaria', 'icono' => 'school'],
        'Constancia_Liberacion_SS'  => ['nombre' => 'Constancia de Liberación de Servicio Social', 'icono' => 'assignment_turned_in'],
        'Acta_Nacimiento'           => ['nombre' => 'Acta de Nacimiento Actualizada', 'icono' => 'description'],
        'Cetificado_Bachillerato'   => ['nombre' => 'Certificado de Bachillerato', 'icono' => 'workspace_premium'],
        'Curp'                      => ['nombre' => 'Clave Única de Registro de Población (CURP)', 'icono' => 'badge'],
        'Acta_Recepcion_Profesional'=> ['nombre' => 'Acta de Recepción Profesional', 'icono' => 'history_edu'],
    ];

    public function index()
    {
        $alumno = DB::table('alumnos')->where('usuario_id', Auth::id())->first();

        if (!$alumno) {
            return redirect()->route('indexalumnos.index')->with('error', 'No se encontró tu expediente escolar.');
        }

        // Consultar los documentos subidos por el alumno
        $documentosSubidos = DB::table('documentos_titulacion')
            ->where('alumno_id', $alumno->id)
            ->get()
            ->keyBy('tipo_documento');

        $totalDocs = count($this->catalogoDocumentos);
        $aprobados = $documentosSubidos->where('estatus', 'Aprobado')->count();
        $subidos   = $documentosSubidos->count();
        $porcentaje = round(($aprobados / $totalDocs) * 100);

        return view('cpanel./titulacion/documentos-titulacion', [
            'catalogo'          => $this->catalogoDocumentos,
            'documentosSubidos' => $documentosSubidos,
            'totalDocs'         => $totalDocs,
            'aprobados'         => $aprobados,
            'subidos'           => $subidos,
            'porcentaje'        => $porcentaje,
        ]);
    }

    public function subirDocumento(Request $request)
    {
        $clavesValidas = implode(',', array_keys($this->catalogoDocumentos));

        $request->validate([
            'tipo_documento' => "required|in:{$clavesValidas}",
            'archivo'        => 'required|file|mimes:pdf|max:5120', // Máx 5MB en PDF
        ], [
            'archivo.required' => 'Debes adjuntar un archivo en formato PDF.',
            'archivo.mimes'    => 'El documento debe tener extensión .pdf obligatoriamente.',
            'archivo.max'      => 'El documento no puede superar los 5 MB.',
        ]);

        $alumno = DB::table('alumnos')->where('usuario_id', Auth::id())->first();

        if (!$alumno) {
            return back()->with('error', 'Expediente no localizado.');
        }

        $tipo = $request->input('tipo_documento');
        $archivo = $request->file('archivo');
        $nombreOriginal = $archivo->getClientOriginalName();

        // Carpeta destino dentro de storage/app/public/titulacion_docs/{alumno_id}
        $ruta = $archivo->store("titulacion_docs/{$alumno->id}", 'public');

        $docExistente = DB::table('documentos_titulacion')
            ->where('alumno_id', $alumno->id)
            ->where('tipo_documento', $tipo)
            ->first();

        if ($docExistente) {
            // Eliminar archivo físico anterior si existe
            if (!empty($docExistente->ruta_archivo)) {
                Storage::disk('public')->delete($docExistente->ruta_archivo);
            }

            // Actualizar versión e historial
            DB::table('documentos_titulacion')
                ->where('id', $docExistente->id)
                ->update([
                    'nombre_archivo' => $nombreOriginal,
                    'ruta_archivo'   => $ruta,
                    'version'        => $docExistente->version + 1,
                    'estatus'        => 'En_Revision',
                    'observaciones'  => null,
                    'updated_at'     => now(),
                ]);
        } else {
            DB::table('documentos_titulacion')->insert([
                'alumno_id'      => $alumno->id,
                'tipo_documento' => $tipo,
                'nombre_archivo' => $nombreOriginal,
                'ruta_archivo'   => $ruta,
                'version'        => 1,
                'estatus'        => 'En_Revision',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        return redirect()->route('proceso.titulacion.index')->with('success', 'Documento adjuntado exitosamente. Se encuentra listo para validación.');
    }
}

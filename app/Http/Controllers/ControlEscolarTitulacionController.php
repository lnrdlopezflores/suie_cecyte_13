<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ControlEscolarTitulacionController extends Controller
{
    private $catalogoDocumentos = [
        'Certificado_Secundaria'     => 'Certificado de Secundaria',
        'Constancia_Liberacion_SS'  => 'Constancia de Liberación de SS',
        'Acta_Nacimiento'           => 'Acta de Nacimiento',
        'Cetificado_Bachillerato'   => 'Certificado de Bachillerato',
        'Curp'                      => 'CURP',
        'Acta_Recepcion_Profesional'=> 'Acta de Recepción Profesional',
    ];

    public function index(Request $request)
    {
        // 1. Obtener alumnos que tengan al menos un documento cargado
        $alumnosQuery = DB::table('alumnos')
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->join('grupos', 'alumnos.grupo_id', '=', 'grupos.id')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('documentos_titulacion')
                      ->whereColumn('documentos_titulacion.alumno_id', 'alumnos.id');
            })
            ->select(
                'alumnos.id',
                'alumnos.nombre',
                'alumnos.apellido_paterno',
                'alumnos.apellido_materno',
                'usuarios.username as matricula',
                'grupos.grupo',
                'grupos.especialidad'
            );

        if ($request->filled('buscar')) {
            $termino = trim($request->input('buscar'));
            $alumnosQuery->where(function ($q) use ($termino) {
                $q->where('usuarios.username', 'like', "%{$termino}%");
            });
        }

        $alumnos = $alumnosQuery->orderBy('alumnos.id', 'desc')->get();

        // 2. Desencriptar nombres y calcular avances
        $alumnos = $alumnos->map(function ($alumno) {
            $nom = $this->desencriptarDato($alumno->nombre);
            $pat = $this->desencriptarDato($alumno->apellido_paterno);
            $mat = $this->desencriptarDato($alumno->apellido_materno);
            $alumno->nombre_completo = trim("{$pat} {$mat} {$nom}");

            $docs = DB::table('documentos_titulacion')->where('alumno_id', $alumno->id)->get();
            $alumno->docs_total = $docs->count();
            $alumno->docs_aprobados = $docs->where('estatus', 'Aprobado')->count();
            $alumno->docs_pendientes = $docs->whereIn('estatus', ['En_Revision', 'Pendiente'])->count();
            $alumno->docs_rechazados = $docs->where('estatus', 'Rechazado')->count();

            return $alumno;
        });

        // 3. Alumno y documento seleccionado para evaluar
        $alumnoSeleccionado = null;
        if ($request->has('alumno_id')) {
            $alumnoSeleccionado = $alumnos->firstWhere('id', $request->query('alumno_id'));
        }
        if (!$alumnoSeleccionado && $alumnos->isNotEmpty()) {
            $alumnoSeleccionado = $alumnos->first();
        }

        $documentosAlumno = collect();
        $docActivo = null;

        if ($alumnoSeleccionado) {
            $documentosAlumno = DB::table('documentos_titulacion')
                ->where('alumno_id', $alumnoSeleccionado->id)
                ->orderBy('id', 'asc')
                ->get();

            if ($request->has('doc_id')) {
                $docActivo = $documentosAlumno->firstWhere('id', $request->query('doc_id'));
            }
            if (!$docActivo && $documentosAlumno->isNotEmpty()) {
                $docActivo = $documentosAlumno->first();
            }
        }

        return view('cpanel/ConEscolar/revision-titulacion', [
            'alumnos'            => $alumnos,
            'alumnoSeleccionado' => $alumnoSeleccionado,
            'documentosAlumno'   => $documentosAlumno,
            'docActivo'          => $docActivo,
            'catalogo'           => $this->catalogoDocumentos,
        ]);
    }

    public function evaluarDocumento(Request $request, $documentoId)
    {
        $request->validate([
            'estatus'       => 'required|in:Aprobado,Rechazado',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        DB::table('documentos_titulacion')
            ->where('id', $documentoId)
            ->update([
                'estatus'        => $request->input('estatus'),
                'observaciones'  => $request->input('observaciones'),
                'revisado_por'   => Auth::id(),
                'fecha_revision' => now(),
                'updated_at'     => now(),
            ]);

        $doc = DB::table('documentos_titulacion')->where('id', $documentoId)->first();

        return redirect()->route('ce.titulacion.index', [
            'alumno_id' => $doc->alumno_id,
            'doc_id'    => $doc->id,
        ])->with('success', 'El estado del documento se actualizó correctamente.');
    }

    private function desencriptarDato(?string $val): string
    {
        if (empty($val)) return '';
        try {
            if (str_starts_with($val, 'ey') || strlen($val) > 50) return decrypt($val);
        } catch (\Throwable $e) {}
        return str_replace(' (Plain)', '', $val);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DocenteTitulacionController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $docente = DB::table('docentes')->where('usuario_id', $userId)->first();

        if (!$docente) {
            return redirect()->route('docente.dashboard')->with('error', 'No se encontró tu expediente docente.');
        }

        // Consultar proyectos donde el docente es Asesor o es Jurado
        $proyectosDB = DB::table('proyectos_titulacion')
            ->leftJoin('proyecto_jurados', 'proyectos_titulacion.id', '=', 'proyecto_jurados.proyecto_id')
            ->where('proyectos_titulacion.docente_asesor_id', $docente->id)
            ->orWhere('proyecto_jurados.docente_id', $docente->id)
            ->select('proyectos_titulacion.*')
            ->distinct()
            ->get();

        $proyectos = collect();

        foreach ($proyectosDB as $proyecto) {
            // Integrantes del equipo
            $integrantes = DB::table('proyecto_alumno')
                ->join('alumnos', 'proyecto_alumno.alumno_id', '=', 'alumnos.id')
                ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
                ->select('alumnos.id as alumno_id', 'alumnos.nombre', 'alumnos.apellido_paterno', 'usuarios.username')
                ->where('proyecto_alumno.proyecto_id', $proyecto->id)
                ->get();

            // Jurados asignados y votos
            $jurados = DB::table('proyecto_jurados')
                ->join('docentes', 'proyecto_jurados.docente_id', '=', 'docentes.id')
                ->join('usuarios', 'docentes.usuario_id', '=', 'usuarios.id')
                ->select('proyecto_jurados.*', 'docentes.nombre', 'docentes.apellido_paterno', 'usuarios.username')
                ->where('proyecto_jurados.proyecto_id', $proyecto->id)
                ->get();

            $votosAprobados = $jurados->where('voto', 'Aprobado')->count();

            // Validar roles del docente autenticado
            $miJurado = $jurados->firstWhere('docente_id', $docente->id);
            $esAsesor = ($proyecto->docente_asesor_id == $docente->id);

            $proyectos->push([
                'proyecto'       => $proyecto,
                'integrantes'    => $integrantes,
                'jurados'        => $jurados,
                'votosAprobados' => $votosAprobados,
                'esAsesor'       => $esAsesor,
                'miJurado'       => $miJurado,
            ]);
        }

        return view('cpanel.titulacion.titulacion_asesorados', compact('proyectos'));
    }

    /**
     * Dictamen del Asesor (Liberación para exposición o revisión)
     */
    public function evaluar(Request $request)
    {
        $request->validate([
            'proyecto_id' => ['required', 'integer', 'exists:proyectos_titulacion,id'],
            'estatus'     => ['required', 'string', 'in:Pendiente,En_Revision,Liberado_Exposicion,Rechazado'],
        ]);

        DB::table('proyectos_titulacion')
            ->where('id', $request->input('proyecto_id'))
            ->update([
                'estatus'    => $request->input('estatus'),
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'El estatus del proyecto fue actualizado correctamente.');
    }

    /**
     * Emisión de voto individual del Jurado (Requiere mínimo 2 de 3)
     */
    public function votarJurado(Request $request)
    {
        $request->validate([
            'proyecto_id'   => ['required', 'integer', 'exists:proyectos_titulacion,id'],
            'voto'          => ['required', 'string', 'in:Aprobado,Rechazado'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $userId = Auth::id();
        $docente = DB::table('docentes')->where('usuario_id', $userId)->first();

        // Actualizar voto del jurado
        DB::table('proyecto_jurados')
            ->where('proyecto_id', $request->input('proyecto_id'))
            ->where('docente_id', $docente->id)
            ->update([
                'voto'             => $request->input('voto'),
                'observaciones'    => $request->input('observaciones'),
                'fecha_evaluacion' => now(),
                'updated_at'       => now(),
            ]);

        // Contar votos aprobatorios actuales de los jurados
        $votosFavorables = DB::table('proyecto_jurados')
            ->where('proyecto_id', $request->input('proyecto_id'))
            ->where('voto', 'Aprobado')
            ->count();

        // Si obtiene al menos 2 de 3 votos, el proyecto queda formalmente Aprobado
        if ($votosFavorables >= 2) {
            DB::table('proyectos_titulacion')
                ->where('id', $request->input('proyecto_id'))
                ->update([
                    'estatus'    => 'Aprobado',
                    'updated_at' => now(),
                ]);
        }

        return redirect()->back()->with('success', 'Tu voto como jurado examinador ha sido registrado.');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JuradosController extends Controller
{
    public function carrera(Request $request, $carrera = 'animacion_digital')
    {
        $nombreEspecialidad = ($carrera === 'quimica_industrial') 
            ? 'Química Industrial' 
            : 'Animación Digital';

        // 1. Consulta con JOINS para obtener los proyectos según la especialidad del grupo del alumno
        $proyectos = DB::table('proyectos_titulacion')
            ->join('proyecto_alumno', 'proyectos_titulacion.id', '=', 'proyecto_alumno.proyecto_id')
            ->join('alumnos', 'proyecto_alumno.alumno_id', '=', 'alumnos.id')
            ->join('grupos', 'alumnos.grupo_id', '=', 'grupos.id')
            ->where('grupos.especialidad', $nombreEspecialidad)
            ->select('proyectos_titulacion.*', 'grupos.especialidad as especialidad')
            ->distinct()
            ->orderBy('proyectos_titulacion.id', 'desc')
            ->get();

        // 2. Obtener jurados de estos proyectos
        $proyectoIds = $proyectos->pluck('id')->toArray();
        
        $jurados = DB::table('proyecto_jurados')
            ->join('docentes', 'proyecto_jurados.docente_id', '=', 'docentes.id')
            ->join('usuarios', 'docentes.usuario_id', '=', 'usuarios.id')
            ->whereIn('proyecto_jurados.proyecto_id', $proyectoIds)
            ->select(
                'proyecto_jurados.*',
                'docentes.nombre',
                'docentes.apellido_paterno',
                'usuarios.username'
            )
            ->get()
            ->groupBy('proyecto_id');

        // 3. Vincular y descifrar nombres de jurados
        $proyectos->transform(function ($proy) use ($jurados) {
            $listaJurados = $jurados->get($proy->id, collect());
            $listaJurados->transform(function ($j) {
                $j->nombre           = $this->desencriptarSeguro($j->nombre);
                $j->apellido_paterno = $this->desencriptarSeguro($j->apellido_paterno);
                return $j;
            });
            $proy->jurados = $listaJurados;
            return $proy;
        });

        // 4. Catálogo de docentes activos para el modal (activo = 1)
        $docentesActivos = DB::table('docentes')
            ->join('usuarios', 'docentes.usuario_id', '=', 'usuarios.id')
            ->where('usuarios.activo', 1)
            ->select('docentes.id', 'docentes.nombre', 'docentes.apellido_paterno', 'usuarios.username')
            ->orderBy('usuarios.username', 'asc')
            ->get()
            ->map(function ($doc) {
                $doc->nombre           = $this->desencriptarSeguro($doc->nombre);
                $doc->apellido_paterno = $this->desencriptarSeguro($doc->apellido_paterno);
                return $doc;
            });

        $vista = ($carrera === 'quimica_industrial') 
            ? 'cpanel/titulacion/jurados_quimica' 
            : 'cpanel/titulacion/jurados_animacion';

        return view($vista, compact('proyectos', 'docentesActivos'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'proyecto_id'   => ['required', 'integer', 'exists:proyectos_titulacion,id'],
            'presidente_id' => ['required', 'integer', 'exists:docentes,id'],
            'secretario_id' => ['required', 'integer', 'exists:docentes,id'],
            'vocal_id'      => ['required', 'integer', 'exists:docentes,id'],
        ]);

        $pId = $request->input('presidente_id');
        $sId = $request->input('secretario_id');
        $vId = $request->input('vocal_id');

        if ($pId === $sId || $pId === $vId || $sId === $vId) {
            return redirect()->back()->with('error', 'Error: Los 3 cargos del sínodo deben ser asignados a profesores distintos.');
        }

        $proyectoId = $request->input('proyecto_id');

        DB::transaction(function () use ($proyectoId, $pId, $sId, $vId) {
            $cargos = [
                ['cargo' => 'Presidente', 'docente_id' => $pId],
                ['cargo' => 'Secretario', 'docente_id' => $sId],
                ['cargo' => 'Vocal',      'docente_id' => $vId],
            ];

            foreach ($cargos as $c) {
                DB::table('proyecto_jurados')->updateOrInsert(
                    ['proyecto_id' => $proyectoId, 'cargo' => $c['cargo']],
                    [
                        'docente_id' => $c['docente_id'],
                        'updated_at' => now(),
                    ]
                );
            }
        });

        return redirect()->back()->with('success', 'El tribunal examinador (3 jurados) fue asignado exitosamente.');
    }

    private function desencriptarSeguro($valor)
    {
        if (empty($valor)) return '';
        try {
            if (is_string($valor) && (str_starts_with($valor, 'ey') || strlen($valor) > 50)) {
                return decrypt($valor);
            }
            return str_replace(' (Plain)', '', $valor);
        } catch (\Throwable $e) {
            return str_replace(' (Plain)', '', $valor);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CoodinacionCargaController extends Controller
{
    public function index(Request $request)
    {
        // 1. Filtrar solo docentes cuyo usuario esté activo (activo = 1)
        $docentes = DB::table('docentes')
            ->join('usuarios', 'docentes.usuario_id', '=', 'usuarios.id')
            ->where('usuarios.activo', 1) // <-- Excluye usuarios suspendidos
            ->select(
                'docentes.id',
                'docentes.nombre',
                'docentes.apellido_paterno',
                'docentes.apellido_materno',
                'docentes.correo',
                'usuarios.username'
            )
            ->orderBy('usuarios.username', 'asc')
            ->get();

        // 2. Traer las cargas académicas existentes
        $todasLasCargas = DB::table('carga_academica')
            ->join('materias', 'carga_academica.materia_id', '=', 'materias.id')
            ->join('grupos', 'carga_academica.grupo_id', '=', 'grupos.id')
            ->select(
                'carga_academica.id',
                'carga_academica.docente_id',
                'carga_academica.aula',
                'carga_academica.horario',
                'materias.id as materia_id',
                'materias.nombre as materia_nombre',
                'materias.clave',
                'materias.horas_semanales',
                'grupos.id as grupo_id',
                'grupos.semestre',
                'grupos.grupo',
                'grupos.especialidad',
                'grupos.turno'
            )
            ->get()
            ->groupBy('docente_id');

        // 3. Descifrado de datos personales
        $docentesConCarga = $docentes->map(function ($docente) use ($todasLasCargas) {
            $docente->nombre           = $this->desencriptarSeguro($docente->nombre);
            $docente->apellido_paterno = $this->desencriptarSeguro($docente->apellido_paterno);
            $docente->apellido_materno = $this->desencriptarSeguro($docente->apellido_materno);
            $docente->correo           = $this->desencriptarSeguro($docente->correo);
            
            $docente->cargas = $todasLasCargas->get($docente->id, collect());

            return $docente;
        });

        // 4. Catálogos para el modal
        $materiasLista = DB::table('materias')->orderBy('nombre', 'asc')->get();
        $gruposLista   = DB::table('grupos')
            ->where('estatus_egreso', 'Regular')
            ->orderBy('semestre', 'asc')
            ->orderBy('grupo', 'asc')
            ->get();

        return view('cpanel.coordinacion.cargadocente', [
            'docentesConCarga' => $docentesConCarga,
            'docentesLista'    => $docentes,
            'materiasLista'    => $materiasLista,
            'gruposLista'      => $gruposLista
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            // Valida que el docente exista y que su usuario asociado esté activo
            'docente_id' => [
                'required',
                'integer',
                Rule::exists('docentes', 'id')->where(function ($query) {
                    $query->join('usuarios', 'docentes.usuario_id', '=', 'usuarios.id')
                          ->where('usuarios.activo', 1);
                }),
            ],
            'materia_id' => ['required', 'integer', 'exists:materias,id'],
            'grupo_id'   => [
                'required', 
                'integer', 
                'exists:grupos,id',
                Rule::unique('carga_academica')->where(function ($query) use ($request) {
                    return $query->where('docente_id', $request->input('docente_id'))
                                 ->where('materia_id', $request->input('materia_id'))
                                 ->where('grupo_id', $request->input('grupo_id'));
                })
            ],
            'aula'       => ['required', 'string', 'max:30'],
            'horario'    => ['required', 'string', 'max:150'],
        ], [
            'docente_id.exists' => 'El docente seleccionado no existe o su cuenta se encuentra suspendida.',
            'grupo_id.unique'   => 'Esta clase ya se encuentra asignada. El docente ya imparte esta materia al grupo seleccionado.'
        ]);

        DB::table('carga_academica')->insert([
            'docente_id' => $request->input('docente_id'),
            'materia_id' => $request->input('materia_id'),
            'grupo_id'   => $request->input('grupo_id'),
            'aula'       => $request->input('aula'),
            'horario'    => $request->input('horario'),
        ]);

        return redirect()
            ->route('coordinador.cargas.index')
            ->with('success', 'Asignatura agregada exitosamente a la carga académica.');
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
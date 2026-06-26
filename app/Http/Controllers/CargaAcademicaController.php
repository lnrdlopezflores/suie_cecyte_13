<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; // <-- AÑADE ESTA LÍNEA

class CargaAcademicaController extends Controller
{
    /**
     * Lista toda la planeación de la carga académica institucional.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        // Construcción de la query relacional unificada
        $query = DB::table('carga_academica')
            ->join('docentes', 'carga_academica.docente_id', '=', 'docentes.id')
            ->join('usuarios', 'docentes.usuario_id', '=', 'usuarios.id') // Para traer el username (clave de empleado)
            ->join('materias', 'carga_academica.materia_id', '=', 'materias.id')
            ->join('grupos', 'carga_academica.grupo_id', '=', 'grupos.id')
            ->select(
                'carga_academica.id as carga_id',
                'carga_academica.aula',
                'carga_academica.horario',
                'docentes.nombre as docente_nombre',
                'docentes.apellido_paterno as docente_apellido',
                'usuarios.username',
                'materias.nombre as materia_nombre',
                'materias.clave',
                'grupos.semestre',
                'grupos.grupo'
            );

        // Si se define un criterio en la barra de búsqueda
        if (!empty($buscar)) {
            $query->where(function ($q) use ($buscar) {
                $q->where('docentes.nombre', 'LIKE', '%' . $buscar . '%')
                  ->orWhere('docentes.apellido_paterno', 'LIKE', '%' . $buscar . '%')
                  ->orWhere('materias.nombre', 'LIKE', '%' . $buscar . '%')
                  ->orWhere('carga_academica.aula', 'LIKE', '%' . $buscar . '%');
            });
        }

        // Ordenamos por semestre del grupo y alfabéticamente por apellido del maestro
        $cargas = $query->orderBy('grupos.semestre', 'asc')
                        ->orderBy('docentes.apellido_paterno', 'asc')
                        ->paginate(15);

        return view('cpanel/ConEscolar/indexcarga', compact('cargas'));
    }

    public function create()
    {
        // 1. Catálogo de docentes con su matrícula
        $docentes = DB::table('docentes')
            ->join('usuarios', 'docentes.usuario_id', '=', 'usuarios.id')
            ->select('docentes.id', 'docentes.nombre', 'docentes.apellido_paterno', 'usuarios.username')
            ->orderBy('docentes.apellido_paterno', 'asc')
            ->get();

        // 2. Catálogo de materias activas
        $materias = DB::table('materias')->orderBy('nombre', 'asc')->get();

        // 3. Catálogo de grupos regulares vigentes
        $grupos = DB::table('grupos')
            ->where('estatus_egreso', 'Regular')
            ->orderBy('semestre', 'asc')
            ->orderBy('grupo', 'asc')
            ->get();

        return view('cpanel/ConEscolar/createcarga', compact('docentes', 'materias', 'grupos'));
    }

    /**
     * Valida y procesa la persistencia de la nueva carga en el sistema.
     */
    public function store(Request $request)
    {
        // Validación estricta cruzando la regla de unicidad combinada en base de datos
        $request->validate([
            'docente_id' => ['required', 'integer', 'exists:docentes,id'],
            'materia_id' => ['required', 'integer', 'exists:materias,id'],
            'grupo_id'   => [
                'required', 
                'integer', 
                'exists:grupos,id',
                // Evita que se repita la combinación docente-materia-grupo en la base de datos
                Rule::unique('carga_academica')->where(function ($query) use ($request) {
                    return $query->where('docente_id', $request->input('docente_id'))
                                 ->where('materia_id', $request->input('materia_id'))
                                 ->where('grupo_id', $request->input('grupo_id'));
                })
            ],
            'aula'       => ['required', 'string', 'max:30'],
            'horario'    => ['required', 'string'],
        ], [
            'grupo_id.unique' => 'Error: Esta clase ya se encuentra asignada. El docente ya imparte esta asignatura al grupo seleccionado.'
        ]);

        // Insertar registro mediante Query Builder
        DB::table('carga_academica')->insert([
            'docente_id' => $request->input('docente_id'),
            'materia_id' => $request->input('materia_id'),
            'grupo_id'   => $request->input('grupo_id'),
            'aula'       => $request->input('aula'),
            'horario'    => $request->input('horario'),
        ]);

        return redirect()
            ->route('cargas.index')
            ->with('success', 'La carga académica ha sido distribuida y guardada exitosamente.');
    }
}

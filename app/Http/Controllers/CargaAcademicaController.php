<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Encryption\DecryptException;

class CargaAcademicaController
{
    /**
     * Lista toda la planeación de la carga académica institucional (Descifrado de docentes).
     */
public function index(Request $request)
{
    $buscar = $request->input('buscar');

    // 1. Consulta base uniendo cargas, materias, grupos, docentes y usuarios
    $query = DB::table('carga_academica')
        ->join('docentes', 'carga_academica.docente_id', '=', 'docentes.id')
        ->join('usuarios', 'docentes.usuario_id', '=', 'usuarios.id')
        ->join('materias', 'carga_academica.materia_id', '=', 'materias.id')
        ->join('grupos', 'carga_academica.grupo_id', '=', 'grupos.id')
        ->where('usuarios.activo', 1) // SOLO DOCENTES ACTIVOS
        ->select(
            'carga_academica.*',
            'docentes.id as docente_id',
            'docentes.nombre as docente_nombre',
            'docentes.apellido_paterno as docente_apellido',
            'docentes.correo as docente_correo',
            'usuarios.username',
            'materias.nombre as materia_nombre',
            'materias.clave',
            'materias.horas_semanales',
            'grupos.semestre',
            'grupos.grupo',
            'grupos.especialidad'
        );

    // 2. Filtro de búsqueda
    if (!empty($buscar)) {
        $query->where(function($q) use ($buscar) {
            $q->where('materias.nombre', 'LIKE', "%{$buscar}%")
              ->orWhere('materias.clave', 'LIKE', "%{$buscar}%")
              ->orWhere('carga_academica.aula', 'LIKE', "%{$buscar}%")
              ->orWhere('usuarios.username', 'LIKE', "%{$buscar}%");
        });
    }

    $cargasRaw = $query->orderBy('grupos.semestre', 'asc')->get();

    // 3. Descifrar y agrupar por cada profesor
    $docentesConCarga = $cargasRaw->groupBy('docente_id')->map(function ($items) {
        $primerItem = $items->first();
        
        $nom = $primerItem->docente_nombre;
        $pat = $primerItem->docente_apellido;
        $cor = $primerItem->docente_correo;

        try {
            if (is_string($nom) && (str_starts_with($nom, 'ey') || strlen($nom) > 50)) $nom = decrypt($nom);
            if (is_string($pat) && (str_starts_with($pat, 'ey') || strlen($pat) > 50)) $pat = decrypt($pat);
            if (is_string($cor) && (str_starts_with($cor, 'ey') || strlen($cor) > 50)) $cor = decrypt($cor);
        } catch (\Throwable $e) {
            $nom = str_replace(' (Plain)', '', $nom);
            $pat = str_replace(' (Plain)', '', $pat);
            $cor = str_replace(' (Plain)', '', $cor);
        }

        return (object) [
            'docente_id'      => $primerItem->docente_id,
            'docente_nombre'  => $nom,
            'docente_apellido'=> $pat,
            'nombre_completo' => trim("$pat $nom"),
            'username'        => $primerItem->username,
            'correo'          => $cor,
            'total_materias'  => $items->count(),
            'total_horas'     => $items->sum('horas_semanales'),
            'materias'        => $items
        ];
    });

    $totalAsignaciones = $cargasRaw->count();

    return view('cpanel/ConEscolar/indexcarga', compact('docentesConCarga', 'totalAsignaciones'));
}

    public function create()
    {
        // 1. Catálogo de docentes uniendo credenciales
        $docentesRaw = DB::table('docentes')
            ->join('usuarios', 'docentes.usuario_id', '=', 'usuarios.id')
            ->select('docentes.id', 'docentes.nombre', 'docentes.apellido_paterno', 'usuarios.username')
            ->orderBy('usuarios.username', 'asc')
            ->get();

        // Desciframos la lista de docentes para que el Select/Dropdown del formulario sea legible
        $docentesRaw->transform(function ($docente) {
            try {
                $docente->nombre = decrypt($docente->nombre);
                $docente->apellido_paterno = decrypt($docente->apellido_paterno);
            } catch (DecryptException $e) {
                // Preservación si hay datos en texto plano
            }
            return $docente;
        });

        // 2. Catálogo de materias activas (No se cifran)
        $materias = DB::table('materias')->orderBy('nombre', 'asc')->get();

        // 3. Catálogo de grupos regulares vigentes (No se cifran)
        $grupos = DB::table('grupos')
            ->where('estatus_egreso', 'Regular')
            ->orderBy('semestre', 'asc')
            ->orderBy('grupo', 'asc')
            ->get();

        return view('cpanel/ConEscolar/createcarga', [
            'docentes' => $docentesRaw, 
            'materias' => $materias, 
            'grupos'   => $grupos
        ]);
    }

    /**
     * Valida y procesa la persistencia de la nueva carga en el sistema.
     */
    public function store(Request $request)
    {
        $request->validate([
            'docente_id' => ['required', 'integer', 'exists:docentes,id'],
            'materia_id' => ['required', 'integer', 'exists:materias,id'],
            'grupo_id'   => [
                'required', 
                'integer', 
                'exists:grupos,id',
                // Sigue operativo ya que evalúa IDs numéricos, no strings cifrados
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

        // Guardado de la relación (Las llaves foráneas y metadatos se quedan legibles para cruces rápidos)
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
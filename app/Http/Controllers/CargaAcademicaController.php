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

        // Construcción de la query relacional unificada
        $query = DB::table('carga_academica')
            ->join('docentes', 'carga_academica.docente_id', '=', 'docentes.id')
            ->join('usuarios', 'docentes.usuario_id', '=', 'usuarios.id') 
            ->join('materias', 'carga_academica.materia_id', '=', 'materias.id')
            ->join('grupos', 'carga_academica.grupo_id', '=', 'grupos.id')
            ->select(
                'carga_academica.id as carga_id',
                'carga_academica.aula',
                'carga_academica.horario',
                'docentes.nombre as docente_nombre',
                'docentes.apellido_paterno as docente_apellido',
                'usuarios.username', // Clave de empleado (Texto plano)
                'materias.nombre as materia_nombre',
                'materias.clave',
                'grupos.semestre',
                'grupos.grupo'
            );

        // Ajustamos la búsqueda: Se remueve el LIKE de campos de texto de docentes ya que están cifrados.
        // Ahora busca de forma indexada por Clave de Empleado, Materia o Aula.
        if (!empty($buscar)) {
            $query->where(function ($q) use ($buscar) {
                $q->where('usuarios.username', 'LIKE', '%' . $buscar . '%')
                  ->orWhere('materias.nombre', 'LIKE', '%' . $buscar . '%')
                  ->orWhere('carga_academica.aula', 'LIKE', '%' . $buscar . '%');
            });
        }

        // Paginación estable ordenando por ID o semestre
        $cargasPaginadas = $query->orderBy('grupos.semestre', 'asc')
                                 ->orderBy('carga_academica.id', 'desc')
                                 ->paginate(15);

        // Iteramos la colección para descifrar la identidad del docente en tiempo de ejecución
        $cargasPaginadas->getCollection()->transform(function ($carga) {
            try {
                $carga->docente_nombre = decrypt($carga->docente_nombre);
                $carga->docente_apellido = decrypt($carga->docente_apellido);
            } catch (DecryptException $e) {
                // Mitigación de errores: Soporte para registros creados antes del cifrado
                $carga->docente_nombre = $carga->docente_nombre . ' (Plain)';
            }
            return $carga;
        });

        return view('cpanel/ConEscolar/indexcarga', ['cargas' => $cargasPaginadas]);
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
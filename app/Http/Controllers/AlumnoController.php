<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Encryption\DecryptException;

class AlumnoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $estado = $request->input('estado', 'vigentes');

        // 1. Contadores generales (El rol y activo están en texto plano, es rápido)
        $totalVigentes = DB::table('usuarios')->where('rol', 'Estudiante')->where('activo', 1)->count();
        $totalBajas = DB::table('usuarios')->where('rol', 'Estudiante')->where('activo', 0)->count();

        // 2. Query de la tabla principal
        $query = DB::table('alumnos')
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->leftJoin('grupos', 'alumnos.grupo_id', '=', 'grupos.id')
            ->select(
                'alumnos.id', 'alumnos.nombre', 'alumnos.apellido_paterno', 'alumnos.apellido_materno',
                'alumnos.nombre_tutor', 'alumnos.telefono_tutor', 'usuarios.username', 'usuarios.activo',
                'grupos.semestre', 'grupos.grupo', 'grupos.especialidad'
            );

        if ($estado === 'bajas') {
            $query->where('usuarios.activo', 0);
        } else {
            $query->where('usuarios.activo', 1);
        }

        // ATENCIÓN: El filtrado por LIKE sobre nombres cifrados dará cero resultados.
        // Restringimos la búsqueda únicamente por Matrícula (username)
        if (!empty($buscar)) {
            $query->where('usuarios.username', 'LIKE', '%' . $buscar . '%');
        }

        // Paginamos por ID o por matrícula para mantener consistencia
        $alumnosPaginados = $query->orderBy('alumnos.id', 'desc')->paginate(15);

        // Desciframos dinámicamente la colección de la tabla principal antes de ir al Blade
        $alumnosPaginados->getCollection()->transform(function ($alumno) {
            try {
                $alumno->nombre = decrypt($alumno->nombre);
                $alumno->apellido_paterno = decrypt($alumno->apellido_paterno);
                $alumno->apellido_materno = $alumno->apellido_materno ? decrypt($alumno->apellido_materno) : null;
                $alumno->nombre_tutor = decrypt($alumno->nombre_tutor);
                $alumno->telefono_tutor = decrypt($alumno->telefono_tutor);
            } catch (DecryptException $e) {
                // Compatibilidad con registros legacy en texto plano
                $alumno->nombre = $alumno->nombre . ' (Plain)';
            }
            return $alumno;
        });

        // 3. Colecciones para el Modal
        $gruposActivos = DB::table('grupos')->orderBy('semestre', 'asc')->get();

        // Alumnos vigentes sin grupo para asignación masiva
        $alumnosDisponibles = DB::table('alumnos')
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->select(
                'alumnos.id', 
                'alumnos.nombre', 
                'alumnos.apellido_paterno', 
                'usuarios.username'
            )
            ->where('usuarios.activo', 1)
            ->whereNull('alumnos.grupo_id')
            ->orderBy('alumnos.id', 'desc')
            ->get();

        // Desciframos la colección secundaria destinada al select/checkboxes del modal
        $alumnosDisponibles->transform(function ($disponible) {
            try {
                $disponible->nombre = decrypt($disponible->nombre);
                $disponible->apellido_paterno = decrypt($disponible->apellido_paterno);
            } catch (DecryptException $e) {
                // Si el expediente no estaba encriptado
            }
            return $disponible;
        });

        return view('cpanel.ConEscolar.indexalumnos', [
            'alumnos'            => $alumnosPaginados, 
            'totalVigentes'      => $totalVigentes, 
            'totalBajas'         => $totalBajas, 
            'gruposActivos'      => $gruposActivos, 
            'alumnosDisponibles' => $alumnosDisponibles
        ]);
    }

    /**
     * Procesa la actualización masiva de los alumnos seleccionados en el grupo destino (Sin cambios).
     */
    public function asignarGrupo(Request $request)
    {
        $request->validate([
            'grupo_id'     => 'required|integer|exists:grupos,id',
            'alumno_ids'   => 'required|array|min:1',
            'alumno_ids.*' => 'integer'
        ], [
            'alumno_ids.required' => 'Debes marcar al menos un estudiante de la lista para proceder.'
        ]);

        $grupoId = $request->input('grupo_id');
        $alumnoIds = $request->input('alumno_ids');

        // La asignación masiva solo actualiza la llave foránea 'grupo_id', la cual no está cifrada
        DB::table('alumnos')
            ->whereIn('id', $alumnoIds)
            ->update([
                'grupo_id' => $grupoId
            ]);

        return redirect()->back()->with('success', 'Se han incorporado ' . count($alumnoIds) . ' estudiantes al grupo exitosamente.');
    }
}
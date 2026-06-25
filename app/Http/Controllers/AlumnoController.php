<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlumnoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $estado = $request->input('estado', 'vigentes');

        // 1. Contadores generales de la parte superior
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

        if (!empty($buscar)) {
            $query->where(function ($q) use ($buscar) {
                $q->where('alumnos.nombre', 'LIKE', '%' . $buscar . '%')
                  ->orWhere('alumnos.apellido_paterno', 'LIKE', '%' . $buscar . '%')
                  ->orWhere('usuarios.username', 'LIKE', '%' . $buscar . '%');
            });
        }

        $alumnos = $query->orderBy('alumnos.apellido_paterno', 'asc')->paginate(15);

        // 3. Colecciones requeridas para poblar los campos del Modal
        $gruposActivos = DB::table('grupos')->orderBy('semestre', 'asc')->get();

        // Alumnos vigentes elegibles para asignación masiva
        $alumnosDisponibles = DB::table('alumnos')
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->select(
                'alumnos.id', 
                'alumnos.nombre', 
                'alumnos.apellido_paterno', 
                'usuarios.username'
            )
            ->where('usuarios.activo', 1)       // Solo alumnos vigentes
            ->whereNull('alumnos.grupo_id')     // REQUISITO CLAVE: Únicamente los que no tienen grupo asignado
            ->orderBy('alumnos.apellido_paterno', 'asc')
            ->get();

        return view('cpanel.ConEscolar.indexalumnos', compact('alumnos', 'totalVigentes', 'totalBajas', 'gruposActivos', 'alumnosDisponibles'));
    }

    /**
     * Procesa la actualización masiva de los alumnos seleccionados en el grupo destino.
     */
    public function asignarGrupo(Request $request)
    {
        $request->validate([
            'grupo_id'   => 'required|integer|exists:grupos,id',
            'alumno_ids' => 'required|array|min:1',
            'alumno_ids.*' => 'integer'
        ], [
            'alumno_ids.required' => 'Debes marcar al menos un estudiante de la lista para proceder.'
        ]);

        $grupoId = $request->input('grupo_id');
        $alumnoIds = $request->input('alumno_ids');

        // Ejecutar actualización masiva (Lote) en lote atómico
        DB::table('alumnos')
            ->whereIn('id', $alumnoIds)
            ->update([
                'grupo_id' => $grupoId
            ]);

        return redirect()->back()->with('success', 'Se han incorporado ' . count($alumnoIds) . ' estudiantes al grupo exitosamente.');
    }
}
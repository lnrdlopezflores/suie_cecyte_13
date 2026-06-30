<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AlumnoPortalController extends Controller
{
    /**
     * Carga el tablero inicial del alumno mapeando el esquema real.
     */
    public function index()
    {
        $userId = Auth::id();

        $infoAlumno = DB::table('alumnos')
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->leftJoin('grupos', 'alumnos.grupo_id', '=', 'grupos.id')
            ->select(
                'alumnos.id',
                'alumnos.nombre',
                'grupos.semestre',
                'grupos.grupo',
                'grupos.estatus_egreso',
                // CORREGIDO: Apunta a 'proyectos_titulacion' y evalúa el ENUM 'Aprobado'
                DB::raw('(SELECT count(*) FROM proyectos_titulacion WHERE alumno_id = alumnos.id AND estatus = "Aprobado") as proyecto_aprobado')
            )
            ->where('alumnos.usuario_id', $userId)
            ->first();

        return view('cpanel/alumnos/dashboard', compact('infoAlumno'));
    }
}
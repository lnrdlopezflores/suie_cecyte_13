<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Encryption\DecryptException;

class AlumnoPortalController extends Controller
{
    /**
     * Carga el tablero inicial del alumno mapeando el esquema real y descifrando su identidad.
     */
    public function index()
    {
        $userId = Auth::id();

        $infoAlumno = DB::table('alumnos')
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->leftJoin('grupos', 'alumnos.grupo_id', '=', 'grupos.id')
            ->select(
                'alumnos.id',
                'alumnos.nombre', // Traemos el string cifrado Base64
                'grupos.semestre',
                'grupos.grupo',
                'grupos.estatus_egreso',
                // Evalúa el ENUM 'Aprobado' en la tabla relacional
                DB::raw('(SELECT count(*) FROM proyectos_titulacion WHERE alumno_id = alumnos.id AND estatus = "Aprobado") as proyecto_aprobado')
            )
            ->where('alumnos.usuario_id', $userId)
            ->first();

        // Si se encontró el registro del estudiante, desciframos sus datos en caliente
        if ($infoAlumno) {
            try {
                $infoAlumno->nombre = decrypt($infoAlumno->nombre);
            } catch (DecryptException $e) {
                // Mitigación: Soporte y tolerancia para registros previos en texto plano (Legacy)
                $infoAlumno->nombre = $infoAlumno->nombre . ' (Plain)';
            }
        }

        return view('cpanel/alumnos/dashboard', compact('infoAlumno'));
    }
}
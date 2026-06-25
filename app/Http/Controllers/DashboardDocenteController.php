<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardDocenteController extends Controller
{
    public function index()
    {
  // 1. Obtener el ID del docente vinculado al usuario logueado
        $docenteId = auth()->user()->docente?->id;

        if (!$docenteId) {
            return redirect()->to('/')->with('error', 'Usuario no identificado como docente.');
        }

        // 2. CORRECCIÓN DE LA CONSULTA: Traer la carga académica con sus relaciones unidas
        // Corrección de la consulta en DashboardDocenteController.php (Línea 34)
        $materias = DB::table('carga_academica')
            ->join('materias', 'carga_academica.materia_id', '=', 'materias.id')
            ->join('grupos', 'carga_academica.grupo_id', '=', 'grupos.id')
            ->select(
                'carga_academica.id',          // Cambiado '->' por '.'
                'materias.nombre',
                'grupos.semestre',
                'grupos.grupo',
                'grupos.especialidad',
                'carga_academica.horario',
                'carga_academica.aula'
            )
            ->where('carga_academica.docente_id', $docenteId)
            ->get();

        // 3. Simulación o cálculo de variables adicionales (opcional por ahora)
        $clasesHoy = 3; 

        // Inyectamos a cada materia la propiedad de alumnos críticos en 0 de forma temporal 
        // para evitar que arroje error la vista mientras desarrollas el módulo de orientación
        foreach ($materias as $materia) {
            $materia->alumnos_criticos_count = 0; 
        } 

        // Ojo: Asegúrate de que esta ruta coincida con tu vista real
        return view('cpanel.orientacion.indexdocente', compact('materias', 'clasesHoy'));
    }
}

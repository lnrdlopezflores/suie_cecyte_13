<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MateriaController extends Controller
{
    /**
     * Desplegar el mapa curricular con filtros de búsqueda y paginación.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        // Inicializar consulta en la tabla materias
        $query = DB::table('materias');

        // Si el usuario escribe un término en el input de búsqueda
        if (!empty($buscar)) {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'LIKE', '%' . $buscar . '%')
                  ->orWhere('clave', 'LIKE', '%' . $buscar . '%');
            });
        }

        // Ordenamos por clave oficial para mantener el orden secuencial del mapa curricular
        $materias = $query->orderBy('clave', 'asc')->paginate(15);

        return view('cpanel/ConEscolar/indexmaterias', compact('materias'));
    }

    public function create()
    {
        return view('cpanel/ConEscolar/createmateria');
    }

    /**
     * Almacena y valida el nuevo registro en el mapa curricular.
     */
    public function store(Request $request)
    {
        // Forzamos a que la clave se evalúe en mayúsculas antes de validar
        $request->merge(['clave' => strtoupper($request->input('clave'))]);

        $request->validate([
            'clave'           => ['required', 'string', 'max:20', 'unique:materias,clave'],
            'nombre'          => ['required', 'string', 'max:100'],
            'horas_semanales' => ['required', 'integer', 'min:1', 'max:20'],
        ], [
            'clave.unique' => 'La clave asignada ya le pertenece a otra materia del catálogo.',
            'clave.max'    => 'La clave de la asignatura no debe exceder los 20 caracteres.'
        ]);

        // Insertar en la base de datos usando Query Builder
        DB::table('materias')->insert([
            'clave'           => $request->input('clave'),
            'nombre'          => $request->input('nombre'),
            'horas_semanales' => $request->input('horas_semanales'),
        ]);

        return redirect()
            ->route('materias.index')
            ->with('success', 'La asignatura "' . $request->input('nombre') . '" se ha incorporado al plan de estudios de forma exitosa.');
    }
}

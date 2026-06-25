<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrupoController extends Controller
{
    /**
     * Muestra el catálogo de grupos de la institución.
     */
    public function index(Request $request)
    {
        $semestre = $request->input('semestre');
        $turno = $request->input('turno');

        // Inicializar consulta sobre la tabla grupos
        $query = DB::table('grupos');

        // Filtro por semestre si está definido
        if (!empty($semestre)) {
            $query->where('semestre', $semestre);
        }

        // Filtro por turno si está definido
        if (!empty($turno)) {
            $query->where('turno', $turno);
        }

        // Ordenamos por semestre ascendente y grupo alfabéticamente (Ej: 1A, 1B, 2A...)
        $grupos = $query->orderBy('semestre', 'asc')
                        ->orderBy('grupo', 'asc')
                        ->paginate(15);

        return view('cpanel/ConEscolar/indexgrupos', compact('grupos'));
    }
}
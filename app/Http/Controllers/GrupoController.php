<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class GrupoController extends Controller
{
    /**
     * Muestra el catálogo de grupos de la institución.
     */
    public function index(Request $request)
    {
        $semestre = $request->input('semestre');
        $turno = $request->input('turno');

        $query = DB::table('grupos');

        if (!empty($semestre)) {
            $query->where('semestre', $semestre);
        }

        if (!empty($turno)) {
            $query->where('turno', $turno);
        }

        $grupos = $query->orderBy('semestre', 'asc')
                        ->orderBy('grupo', 'asc')
                        ->paginate(15);

        return view('cpanel/ConEscolar/indexgrupos', compact('grupos'));
    }

    public function create()
    {
        return view('cpanel/ConEscolar/creategrupos');
    }

    /**
     * Guarda el grupo en la base de datos aplicando las validaciones pertinentes.
     */
    public function store(Request $request)
    {
        $request->merge(['grupo' => strtoupper($request->input('grupo'))]);

        $request->validate([
            'semestre'       => ['required', 'in:1,2,3,4,5,6'],
            'grupo'          => [
                'required', 
                'string', 
                'max:1',
                // Validación para evitar registros duplicados de grupo
                Rule::unique('grupos')->where(function ($query) use ($request) {
                    return $query->where('semestre', $request->input('semestre'))
                                 ->where('grupo', $request->input('grupo'))
                                 ->where('especialidad', $request->input('especialidad'))
                                 ->where('turno', $request->input('turno'))
                                 ->where('ciclo_escolar', $request->input('ciclo_escolar'));
                })
            ],
            'especialidad'   => ['required', 'string', 'max:100'],
            'turno'          => ['required', 'in:Matutino,Vespertino'],
            'ciclo_escolar'  => ['required', 'string', 'max:15'],
            'estatus_egreso' => ['required', 'in:Regular,Egresado'],
        ], [
            'grupo.unique'   => 'Ya existe un grupo registrado con el mismo semestre, letra, especialidad, turno y ciclo escolar.',
        ]);

        DB::table('grupos')->insert([
            'semestre'       => $request->input('semestre'),
            'grupo'          => $request->input('grupo'),
            'especialidad'   => $request->input('especialidad'),
            'turno'          => $request->input('turno'),
            'ciclo_escolar'  => strtoupper($request->input('ciclo_escolar')),
            'estatus_egreso' => $request->input('estatus_egreso'),
        ]);

        return redirect()
            ->route('grupos.index')
            ->with('success', 'El grupo se ha configurado e incorporado exitosamente al SUIE.');
    }

    public function edit($id)
    {
        $grupo = DB::table('grupos')->where('id', $id)->first();

        if (!$grupo) {
            return redirect()->route('grupos.index')->with('error', 'El grupo solicitado no existe.');
        }

        return view('cpanel/ConEscolar/editgrupos', compact('grupo'));
    }

    /**
     * Actualiza únicamente el campo semestre y parámetros en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'semestre'       => ['required', 'in:1,2,3,4,5,6'],
            'ciclo_escolar'  => ['required', 'string', 'max:15'],
            'estatus_egreso' => ['required', 'in:Regular,Egresado'],
        ]);

        DB::table('grupos')
            ->where('id', $id)
            ->update([
                'semestre'       => $request->input('semestre'),
                'ciclo_escolar'  => strtoupper($request->input('ciclo_escolar')),
                'estatus_egreso' => $request->input('estatus_egreso'),
            ]);

        return redirect()
            ->route('grupos.index')
            ->with('success', 'Los parámetros de la sección se han actualizado correctamente.');
    }
}
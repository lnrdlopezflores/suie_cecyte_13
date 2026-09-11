<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DocenteJuradoController extends Controller
{
    public function index(Request $request)
{
    $docente = DB::table('docentes')->where('usuario_id', Auth::id())->first();

    if (!$docente) {
        return redirect()->back()->with('error', 'No se encontró un expediente docente asociado a tu cuenta.');
    }

    // 1. Obtener registros planos
    $registros = DB::table('proyecto_jurados')
        ->join('proyectos_titulacion', 'proyecto_jurados.proyecto_id', '=', 'proyectos_titulacion.id')
        ->join('proyecto_alumno', 'proyectos_titulacion.id', '=', 'proyecto_alumno.proyecto_id')
        ->join('alumnos', 'proyecto_alumno.alumno_id', '=', 'alumnos.id')
        ->join('grupos', 'alumnos.grupo_id', '=', 'grupos.id')
        ->where('proyecto_jurados.docente_id', $docente->id)
        ->select(
            'proyectos_titulacion.id',
            'proyectos_titulacion.titulo',
            'proyectos_titulacion.documento_url',
            'proyectos_titulacion.estatus as estatus_global',
            'proyectos_titulacion.created_at',
            'alumnos.nombre as alumno_nombre',
            'alumnos.apellido_paterno as alumno_paterno',
            'alumnos.apellido_materno as alumno_materno',
            'grupos.grupo',
            'grupos.especialidad',
            'proyecto_jurados.voto as mi_voto',
            'proyecto_jurados.observaciones as mis_comentarios'
        )
        ->orderBy('proyectos_titulacion.id', 'desc')
        ->get();

    // 2. Agrupar por ID de proyecto para unificar a los integrantes
    $proyectos = $registros->groupBy('id')->map(function ($items) {
        $primerItem = $items->first();

        // Desencriptar y juntar todos los integrantes en una lista separada por comas
        $integrantes = $items->map(function ($item) {
            $nom = $this->desencriptarDato($item->alumno_nombre);
            $pat = $this->desencriptarDato($item->alumno_paterno);
            $mat = $this->desencriptarDato($item->alumno_materno);
            return trim("{$pat} {$mat} {$nom}");
        })->filter()->unique()->implode(', ');

        $primerItem->integrantes = $integrantes ?: 'Sin integrantes asignados';
        return $primerItem;
    })->values();

    // 3. Proyecto activo a revisar
    $proyectoSeleccionado = null;
    if ($request->has('proyecto_id')) {
        $proyectoSeleccionado = $proyectos->firstWhere('id', $request->query('proyecto_id'));
    }
    if (!$proyectoSeleccionado && $proyectos->isNotEmpty()) {
        $proyectoSeleccionado = $proyectos->first();
    }

    return view('cpanel/titulacion/jurado-proyectos', compact('proyectos', 'proyectoSeleccionado'));
}

    public function dictaminar(Request $request, $proyectoId)
    {
        $request->validate([
            'voto'        => 'required|in:Aprobado,Rechazado',
            'comentarios' => 'nullable|string|max:1000',
        ]);

        $docente = DB::table('docentes')->where('usuario_id', Auth::id())->first();

        // Actualizar el dictamen individual del jurado
        DB::table('proyecto_jurados')
            ->where('proyecto_id', $proyectoId)
            ->where('docente_id', $docente->id)
            ->update([
                'voto'        => $request->input('voto'),
                'observaciones' => $request->input('observaciones'),
                'updated_at'  => now(),
            ]);

        // Verificar si se alcanzó la mayoría (2 o más aprobaciones)
        $votosFavorables = DB::table('proyecto_jurados')
            ->where('proyecto_id', $proyectoId)
            ->where('voto', 'Aprobado')
            ->count();

        if ($votosFavorables >= 2) {
            DB::table('proyectos_titulacion')
                ->where('id', $proyectoId)
                ->update(['estatus' => 'Aprobado', 'updated_at' => now()]);
        }

        return redirect()
            ->route('docente.jurado.index', ['proyecto_id' => $proyectoId])
            ->with('success', 'Dictamen y observaciones registradas exitosamente.');
    }

    private function desencriptarDato(?string $val): string
    {
        if (empty($val)) return '';
        try {
            if (str_starts_with($val, 'ey') || strlen($val) > 50) return decrypt($val);
        } catch (\Throwable $e) {}
        return str_replace(' (Plain)', '', $val);
    }
}
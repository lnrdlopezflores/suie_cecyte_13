<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class titulacionController extends Controller
{
    /**
     * Muestra la vista principal del trámite de titulación.
     */
    // En titulacionController.php

public function index()
{
    $userId = Auth::id();
    $alumno = DB::table('alumnos')->where('usuario_id', $userId)->first();

    if (!$alumno) {
        return redirect()->route('indexalumnos.index')->with('error', 'No se encontró tu expediente estudiantil.');
    }

    $proyecto = DB::table('proyectos_titulacion')
        ->join('proyecto_alumno', 'proyectos_titulacion.id', '=', 'proyecto_alumno.proyecto_id')
        ->select('proyectos_titulacion.*')
        ->where('proyecto_alumno.alumno_id', $alumno->id)
        ->first();

    $integrantes = collect();
    $asesor = null;

    if ($proyecto) {
        $integrantes = DB::table('proyecto_alumno')
            ->join('alumnos', 'proyecto_alumno.alumno_id', '=', 'alumnos.id')
            ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
            ->select('alumnos.id as alumno_id', 'alumnos.nombre', 'alumnos.apellido_paterno', 'usuarios.username')
            ->where('proyecto_alumno.proyecto_id', $proyecto->id)
            ->get();

        // CÓDIGO CORREGIDO:
        $asesorId = $proyecto->docente_asesor_id ?? $proyecto->asesor_id ?? null;

        if ($asesorId) {
            $asesor = DB::table('docentes')
                ->where('id', $asesorId)
                ->first();
        }
    }

    // Cargar docentes activos para el selector de asesor
    $docentes = DB::table('docentes')
        ->join('usuarios', 'docentes.usuario_id', '=', 'usuarios.id')
        ->select('docentes.id', 'docentes.nombre', 'docentes.apellido_paterno', 'usuarios.username')
        ->where('usuarios.activo', 1)
        ->get();

    return view('cpanel.titulacion.indextitulacion', compact('proyecto', 'integrantes', 'asesor', 'docentes'));
}

public function asignarAsesor(Request $request)
{
    $request->validate([
        'proyecto_id' => ['required', 'integer', 'exists:proyectos_titulacion,id'],
        'docente_id'  => ['required', 'integer', 'exists:docentes,id'],
    ]);

    DB::table('proyectos_titulacion')
        ->where('id', $request->input('proyecto_id'))
        ->update([
            'docente_asesor_id' => $request->input('docente_id'),
            'updated_at'        => now(),
        ]);

    return redirect()->back()->with('success', 'Docente asesor asignado al proyecto de titulación.');
}
    /**
     * Registra el proyecto e integra al estudiante como creador.
     */
   public function store(Request $request)
    {
        $request->validate([
            'titulo'    => ['required', 'string', 'max:500'],
            'modalidad' => ['nullable', 'string', 'max:255'],
            'resumen'   => ['nullable', 'string'],
        ]);

        $userId = Auth::id();

        // 1. Obtener al alumno junto con la especialidad de su grupo asignado
        $alumno = DB::table('alumnos')
            ->leftJoin('grupos', 'alumnos.grupo_id', '=', 'grupos.id')
            ->select('alumnos.id', 'grupos.especialidad')
            ->where('alumnos.usuario_id', $userId)
            ->first();

        if (!$alumno) {
            return redirect()->back()->withErrors(['error' => 'No se encontró tu expediente estudiantil.']);
        }

        // 2. Ejecutar la inserción incluyendo 'especialidad_historica'
// app/Http/Controllers/titulacionController.php -> store()

        DB::transaction(function () use ($request, $alumno) {
            $proyectoId = DB::table('proyectos_titulacion')->insertGetId([
                'alumno_id'             => $alumno->id,
                'titulo'                => $request->input('titulo'),
                'modalidad'             => $request->input('modalidad', 'Proyecto de Titulación'),
                'resumen'               => $request->input('resumen'),
                'especialidad_historica' => $alumno->especialidad ?? 'General',
                'documento_url'         => null, // O pasa '' si la columna no acepta NULL aún
                'estatus'               => 'Pendiente',
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);

            // Registro del creador en la tabla de integrantes
            DB::table('proyecto_alumno')->insert([
                'proyecto_id' => $proyectoId,
                'alumno_id'   => $alumno->id,
                'created_at'  => now(),
            ]);
        });

        return redirect()->route('titulacion.index')->with('success', 'Proyecto de titulación registrado correctamente.');
    }

    /**
     * Permite agregar un compañero al equipo mediante su matrícula.
     */
    public function agregarCompanero(Request $request)
    {
        $request->validate([
            'proyecto_id' => ['required', 'integer', 'exists:proyectos_titulacion,id'],
            'username'    => ['required', 'string', 'exists:usuarios,username'],
        ]);

        // 1. Buscar usuario y alumno mediante la matrícula
        $usuarioCompanero = DB::table('usuarios')->where('username', $request->input('username'))->first();
        $alumnoCompanero  = DB::table('alumnos')->where('usuario_id', $usuarioCompanero->id)->first();

        if (!$alumnoCompanero) {
            return redirect()->back()->withErrors(['username' => 'La matrícula no pertenece a un estudiante registrado.']);
        }

        // 2. Verificar si el compañero ya tiene un proyecto asignado
        $yaTieneProyecto = DB::table('proyecto_alumno')->where('alumno_id', $alumnoCompanero->id)->exists();

        if ($yaTieneProyecto) {
            return redirect()->back()->withErrors(['username' => 'El estudiante especificado ya está vinculado a un proyecto de titulación.']);
        }

        // 3. Vincular al compañero
        DB::table('proyecto_alumno')->insert([
            'proyecto_id' => $request->input('proyecto_id'),
            'alumno_id'   => $alumnoCompanero->id,
            'created_at'  => now(),
        ]);

        return redirect()->route('titulacion.index')->with('success', 'Compañero agregado exitosamente al equipo.');
    }

    public function repositorio($proyectoId)
    {
        $proyecto = DB::table('proyectos_titulacion')->where('id', $proyectoId)->first();

        if (!$proyecto) {
            return redirect()->route('titulacion.index')->with('error', 'El proyecto especificado no existe.');
        }

        // Obtener asesor si está asignado
        $asesor = null;
        $asesorId = $proyecto->docente_asesor_id ?? $proyecto->asesor_id ?? null;
        if ($asesorId) {
            $asesor = DB::table('docentes')->where('id', $asesorId)->first();
        }

        // Cargar documentos entregados mapeados por tipo
        $documentosDB = DB::table('documentos_titulacion')
            ->where('alumno_id', $proyecto->alumno_id)
            ->get();

        $documentos = [];
        foreach ($documentosDB as $doc) {
            $documentos[$doc->tipo_documento] = $doc;
        }

        return view('cpanel.titulacion.repositorio', compact('proyecto', 'asesor', 'documentos'));
    }

/**
 * Guarda o actualiza los archivos PDF/PPTX del entregable.
 */
    public function guardarEntregable(Request $request)
    {
        $request->validate([
            'proyecto_id' => ['required', 'integer', 'exists:proyectos_titulacion,id'],
            'tipo'        => ['required', 'string', 'in:Reporte,Presentacion'],
            'archivo'     => ['required', 'file', 'mimes:pdf,pptx,ppt', 'max:30720'], // Máx 30MB
        ]);

        $proyecto = DB::table('proyectos_titulacion')->where('id', $request->input('proyecto_id'))->first();

        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $nombreOriginal = $file->getClientOriginalName();
            // Guardar archivo en el disco público dentro de la carpeta entregables
            $path = $file->store('entregables_titulacion', 'public');

            DB::table('documentos_titulacion')->updateOrInsert(
                [
                    'alumno_id'      => $proyecto->alumno_id,
                    'tipo_documento' => $request->input('tipo')
                ],
                [
                    'nombre_archivo' => $nombreOriginal,
                    'ruta_archivo'   => $path,
                    'version'        => DB::raw('version + 1'),
                    'estatus'        => 'En_Revision',
                    'updated_at'     => now(),
                    'created_at'     => now(),
                ]
            );
        }

        return redirect()->back()->with('success', 'El entregable fue cargado correctamente en el repositorio.');
    }

/**
 * Guarda el enlace del video demostrativo.
 */
    public function guardarVideo(Request $request)
    {
        $request->validate([
            'proyecto_id' => ['required', 'integer', 'exists:proyectos_titulacion,id'],
            'video_url'   => ['required', 'url', 'max:500'],
        ]);

        DB::table('proyectos_titulacion')
            ->where('id', $request->input('proyecto_id'))
            ->update([
                'video_url'  => $request->input('video_url'),
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Enlace del video demostrativo guardado con éxito.');
    }
}

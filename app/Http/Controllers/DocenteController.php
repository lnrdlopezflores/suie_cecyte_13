<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class DocenteController extends Controller
{
    /**
     * Muestra el catálogo completo de personal docente con filtros (Descifrado dinámico).
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $query = DB::table('docentes')
            ->join('usuarios', 'docentes.usuario_id', '=', 'usuarios.id')
            ->select(
                'docentes.id as docente_id',
                'docentes.nombre',
                'docentes.apellido_paterno',
                'docentes.apellido_materno',
                'docentes.correo',
                'docentes.telefono',
                'usuarios.username',  // Clave de empleado (No se cifra para permitir búsquedas directas)
                'usuarios.activo'     
            );
        // Filtramos de forma exacta y ultra rápida usando el username/clave de nómina.
        if (!empty($buscar)) {
            $query->where('usuarios.username', 'LIKE', '%' . $buscar . '%');
        }

        // Ordenamos por ID de forma descendente o por el username
        $docentesPaginados = $query->orderBy('usuarios.username', 'asc')->paginate(15);

        // Iteramos sobre la colección del paginador para descifrar en tiempo de ejecución
        // En DocenteController.php -> index()

$docentesPaginados->getCollection()->transform(function ($docente) {
    try {
        // Verificamos si el string tiene la estructura de un JSON cifrado de Laravel (empieza con 'ey' y tiene longitud)
        if (is_string($docente->nombre) && (strpos($docente->nombre, 'ey') === 0 || strlen($docente->nombre) > 50)) {
            $docente->nombre           = decrypt($docente->nombre);
            $docente->apellido_paterno = decrypt($docente->apellido_paterno);
            $docente->apellido_materno = $docente->apellido_materno ? decrypt($docente->apellido_materno) : null;
            
            if ($docente->telefono) {
                $docente->telefono = decrypt($docente->telefono);
            }
        } else {
            // Si es un dato plano (Legacy), no llamamos a decrypt() para evitar el crash
            $docente->nombre = $docente->nombre . ' (Plain)';
        }
    } catch (\Throwable $e) {
        // Capturamos Throwable para evitar la excepción nativa de unserialize() en PHP 8.3
        $docente->nombre = $docente->nombre . ' (Plain)';
    }

    return $docente;
});

        return view('cpanel/docentes/indexdocente', ['docentes' => $docentesPaginados]);
    }

    public function create()
    {
        $usuariosDisponibles = DB::table('usuarios')
            ->leftJoin('docentes', 'usuarios.id', '=', 'docentes.usuario_id')
            ->where('usuarios.rol', '=', 'Docente')
            ->whereNull('docentes.usuario_id') 
            ->select('usuarios.id', 'usuarios.username', 'usuarios.created_at')
            ->orderBy('usuarios.username', 'asc')
            ->get();

        return view('cpanel/docentes/createdocente', compact('usuariosDisponibles'));
    }

    /**
     * Almacena los datos personales aplicando cifrado criptográfico robusto.
     */
    public function store(Request $request)
    {
        $request->validate([
            'usuario_id'       => ['required', 'integer', 'unique:docentes,usuario_id'],
            'nombre'           => ['required', 'string', 'max:50'],
            'apellido_paterno' => ['required', 'string', 'max:50'],
            'apellido_materno' => ['nullable', 'string', 'max:50'],
            'correo'           => ['nullable', 'email', 'max:100'], // Quitamos unique:docentes,correo temporalmente ya que el hash encriptado variará siempre
            'telefono'         => ['nullable', 'string', 'max:15'],
        ], [
            'usuario_id.unique' => 'Esta cuenta de usuario ya ha sido asignada a otro maestro.',
        ]);

        // Insertar los registros aplicando el helper encrypt()
        DB::table('docentes')->insert([
            'usuario_id'       => $request->input('usuario_id'),
            'nombre'           => encrypt($request->input('nombre')),
            'apellido_paterno' => encrypt($request->input('apellido_paterno')),
            'apellido_materno' => $request->input('apellido_materno') ? encrypt($request->input('apellido_materno')) : null,
            'correo'           => $request->input('correo') ? encrypt($request->input('correo')) : null,
            'telefono'         => $request->input('telefono') ? encrypt($request->input('telefono')) : null,
        ]);

        return redirect()
            ->route('docentes.index')
            ->with('success', 'El perfil docente se ha guardado y encriptado en la matriz del SUIE de forma exitosa.');
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'nombre'           => ['required', 'string', 'max:255'],
        'apellido_paterno' => ['required', 'string', 'max:255'],
        'apellido_materno' => ['nullable', 'string', 'max:255'],
        'correo'           => ['nullable', 'email', 'max:255'],
        'telefono'         => ['nullable', 'string', 'max:20'],
        'activo'           => ['required', 'boolean'],
    ]);

    $docente = DB::table('docentes')->where('id', $id)->first();

    if (!$docente) {
        return redirect()->back()->withErrors(['docente' => 'El docente especificado no existe.']);
    }

    // Actualización de la información del docente con cifrado
    DB::table('docentes')->where('id', $id)->update([
        'nombre'           => encrypt($request->input('nombre')),
        'apellido_paterno' => encrypt($request->input('apellido_paterno')),
        'apellido_materno' => $request->filled('apellido_materno') ? encrypt($request->input('apellido_materno')) : null,
        'correo'           => $request->input('correo'),
        'telefono'         => $request->filled('telefono') ? encrypt($request->input('telefono')) : null,
    ]);

    // Actualización del estatus del usuario en la tabla relacionada
    DB::table('usuarios')->where('id', $docente->usuario_id)->update([
        'activo'     => $request->input('activo'),
        'updated_at' => now(),
    ]);

    return redirect()->route('docentes.index')->with('success', 'Información del docente actualizada correctamente.');
}
}
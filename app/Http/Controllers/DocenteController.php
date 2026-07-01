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
        $docentesPaginados->getCollection()->transform(function ($docente) {
            try {
                $docente->nombre = decrypt($docente->nombre);
                $docente->apellido_paterno = decrypt($docente->apellido_paterno);
                $docente->apellido_materno = $docente->apellido_materno ? decrypt($docente->apellido_materno) : null;
                $docente->correo = $docente->correo ? decrypt($docente->correo) : null;
                $docente->telefono = $docente->telefono ? decrypt($docente->telefono) : null;
            } catch (DecryptException $e) {
                // Soporte de compatibilidad: si hay datos viejos en la BD, no rompe la vista
                $docente->nombre = $docente->nombre . ' (Sin Cifrar)';
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
}
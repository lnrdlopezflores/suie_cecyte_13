<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocenteController extends Controller
{
/**
     * Muestra el catálogo completo de personal docente con filtros de búsqueda.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        // Construimos la query uniendo docentes con su respectivo usuario credentials
        $query = DB::table('docentes')
            ->join('usuarios', 'docentes.usuario_id', '=', 'usuarios.id')
            ->select(
                'docentes.id as docente_id',
                'docentes.nombre',
                'docentes.apellido_paterno',
                'docentes.apellido_materno',
                'docentes.correo',
                'docentes.telefono',
                'usuarios.username',  // Clave de empleado
                'usuarios.activo'     // Estado de la cuenta
            );

        // Si el administrador filtra por barra de búsqueda
        if (!empty($buscar)) {
            $query->where(function ($q) use ($buscar) {
                $q->where('docentes.nombre', 'LIKE', '%' . $buscar . '%')
                  ->orWhere('docentes.apellido_paterno', 'LIKE', '%' . $buscar . '%')
                  ->orWhere('docentes.apellido_materno', 'LIKE', '%' . $buscar . '%')
                  ->orWhere('usuarios.username', 'LIKE', '%' . $buscar . '%');
            });
        }

        // Ordenamos alfabéticamente por apellido paterno
        $docentes = $query->orderBy('docentes.apellido_paterno', 'asc')->paginate(15);

        return view('cpanel/docentes/indexdocente', compact('docentes'));
    }

    public function create()
    {
        // Extrae usuarios de rol 'Docente' que NO están registrados en la tabla 'docentes'
        $usuariosDisponibles = DB::table('usuarios')
            ->leftJoin('docentes', 'usuarios.id', '=', 'docentes.usuario_id')
            ->where('usuarios.rol', '=', 'Docente')
            ->whereNull('docentes.usuario_id') // Asegura que no tengan relación previa
            ->select('usuarios.id', 'usuarios.username', 'usuarios.created_at')
            ->orderBy('usuarios.username', 'asc')
            ->get();

        return view('cpanel/docentes/createdocente', compact('usuariosDisponibles'));
    }

    /**
     * Almacena los datos personales del docente en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'usuario_id'       => ['required', 'integer', 'unique:docentes,usuario_id'],
            'nombre'           => ['required', 'string', 'max:50'],
            'apellido_paterno' => ['required', 'string', 'max:50'],
            'apellido_materno' => ['nullable', 'string', 'max:50'],
            'correo'           => ['nullable', 'email', 'max:100', 'unique:docentes,correo'],
            'telefono'         => ['nullable', 'string', 'max:15'],
        ], [
            'usuario_id.unique' => 'Esta cuenta de usuario ya ha sido asignada a otro maestro.',
            'correo.unique'     => 'El correo electrónico ya se encuentra registrado por otro docente.'
        ]);

        // Insertar registro del maestro
        DB::table('docentes')->insert([
            'usuario_id'       => $request->input('usuario_id'),
            'nombre'           => $request->input('nombre'),
            'apellido_paterno' => $request->input('apellido_paterno'),
            'apellido_materno' => $request->input('apellido_materno'),
            'correo'           => $request->input('correo'),
            'telefono'         => $request->input('telefono'),
        ]);

        return redirect()
            ->route('docentes.index')
            ->with('success', 'El perfil docente de ' . $request->input('nombre') . ' se ha enlazado de forma exitosa.');
    }
}
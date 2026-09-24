<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonalController extends Controller
{
    public function index(Request $request)
    {
        $rolesPermitidos = ['Coordinador', 'Control Escolar', 'Orientador', 'administrador'];

        // Consulta unificada haciendo JOIN con las 4 tablas operativas
        $query = DB::table('usuarios')
            ->leftJoin('administrador', 'usuarios.id', '=', 'administrador.usuario_id')
            ->leftJoin('coordinador', 'usuarios.id', '=', 'coordinador.usuario_id')
            ->leftJoin('orientador', 'usuarios.id', '=', 'orientador.usuario_id')
            ->leftJoin('control_escolar', 'usuarios.id', '=', 'control_escolar.usuario_id')
            ->whereIn('usuarios.rol', $rolesPermitidos)
            ->select(
                'usuarios.id',
                'usuarios.username',
                'usuarios.email',
                'usuarios.rol',
                'usuarios.activo',
                'usuarios.google2fa_enabled',
                // Unificación de nombres y apellidos de las 4 tablas
                DB::raw('COALESCE(administrador.nombre, coordinador.nombre, orientador.nombre, control_escolar.nombre) as per_nombre'),
                DB::raw('COALESCE(administrador.apaterno, coordinador.apaterno, orientador.apaterno, control_escolar.apaterno) as per_apaterno'),
                'administrador.amaterno as per_amaterno',
                // Unificación de teléfono (presente en coordinador, orientador y control_escolar)
                DB::raw('COALESCE(coordinador.telefono, orientador.telefono, control_escolar.telefono) as per_telefono')
            );

        // Filtro por Rol
        if ($request->filled('rol') && in_array($request->input('rol'), $rolesPermitidos)) {
            $query->where('usuarios.rol', $request->input('rol'));
        }

        // Búsqueda segura con sentencias preparadas
        if ($request->filled('buscar')) {
            $termino = '%' . trim($request->input('buscar')) . '%';
            $query->where(function ($q) use ($termino) {
                $q->where('usuarios.username', 'LIKE', $termino)
                  ->orWhere('usuarios.email', 'LIKE', $termino)
                  ->orWhere('coordinador.telefono', 'LIKE', $termino)
                  ->orWhere('orientador.telefono', 'LIKE', $termino)
                  ->orWhere('control_escolar.telefono', 'LIKE', $termino);
            });
        }

        $registros = $query->orderBy('usuarios.rol', 'asc')
                           ->orderBy('usuarios.username', 'asc')
                           ->get();

        // Descifrado seguro de campos personales si están encriptados
        $personal = $registros->map(function ($p) {
            $nom = $this->desencriptarDato($p->per_nombre);
            $pat = $this->desencriptarDato($p->per_apaterno);
            $mat = $this->desencriptarDato($p->per_amaterno);
            $tel = $this->desencriptarDato($p->per_telefono);

            $nombreCompleto = trim("{$nom} {$pat} {$mat}");
            $p->nombre_completo = !empty($nombreCompleto) ? $nombreCompleto : 'Sin perfil asignado';
            $p->telefono = !empty($tel) ? $tel : 'No registrado';

            return $p;
        });

        // Conteo por áreas
        $conteoRoles = DB::table('usuarios')
            ->whereIn('rol', $rolesPermitidos)
            ->select('rol', DB::raw('count(*) as total'))
            ->groupBy('rol')
            ->pluck('total', 'rol');

        return view('cpanel/personal/indexpersonal', [
            'personal'    => $personal,
            'conteoRoles' => $conteoRoles,
            'rolFiltro'   => $request->input('rol'),
            'buscar'      => $request->input('buscar')
        ]);
    }

    private function desencriptarDato(?string $val): string
    {
        if (empty($val)) return '';
        try {
            if (is_string($val) && (str_starts_with($val, 'ey') || strlen($val) > 50)) {
                return decrypt($val);
            }
        } catch (\Throwable $e) {}
        return str_replace(' (Plain)', '', $val);
    }
}

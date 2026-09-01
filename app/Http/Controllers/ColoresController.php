<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ColoresController extends Controller
{
    public function index()
    {
        $configs = [];

        if (Schema::hasTable('configuraciones_sistema')) {
            $configs = DB::table('configuraciones_sistema')->pluck('valor', 'clave')->toArray();
        }

        return view('cpanel/configuracion_colores', [
            'colorPrimario'    => $configs['color_primario'] ?? '#841B44',
            'colorHover'       => $configs['color_hover'] ?? '#681535',
            'colorLight'       => $configs['color_light'] ?? '#fdf2f4',
            'mostrarAvisoVeda' => ($configs['mostrar_aviso_veda'] ?? '0') === '1',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'color_primario' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'color_hover'    => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'color_light'    => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $colores = [
            'color_primario'     => $request->input('color_primario'),
            'color_hover'        => $request->input('color_hover'),
            'color_light'        => $request->input('color_light') ?: '#fdf2f4',
            'mostrar_aviso_veda' => $request->has('mostrar_aviso_veda') ? '1' : '0',
        ];

        foreach ($colores as $clave => $valor) {
            DB::table('configuraciones_sistema')->updateOrInsert(
                ['clave' => $clave],
                ['valor' => $valor, 'updated_at' => now()]
            );
        }

        return redirect()->back()->with('success', 'La configuración de apariencia y normativa electoral se actualizó globalmente.');
    }
}
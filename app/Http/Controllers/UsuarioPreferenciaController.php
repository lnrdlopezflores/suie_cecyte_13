<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UsuarioPreferenciaController extends Controller
{
    public function actualizarTema(Request $request)
    {
        $request->validate([
            'tema' => ['required', 'in:light,dark']
        ]);

        DB::table('usuarios')
            ->where('id', Auth::id())
            ->update([
                'tema' => $request->input('tema'),
                'updated_at' => now()
            ]);

        return response()->json(['success' => true]);
    }
}
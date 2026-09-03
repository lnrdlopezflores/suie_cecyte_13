<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoogleAuthConfigController extends Controller
{
    public function index()
    {
        // 1. Obtener valores guardados en la tabla 'configuraciones_sistema' o fallback a env()
        $configs = DB::table('configuraciones_sistema')->pluck('valor', 'clave')->toArray();

        $clientId = $configs['google_client_id'] ?? env('GOOGLE_CLIENT_ID', '');
        $clientSecret = $configs['google_client_secret'] ?? env('GOOGLE_CLIENT_SECRET', '');
        $modoAcceso = $configs['google_modo_acceso'] ?? 'hibrido'; // 'solo_institucional', 'hibrido'
        $dominioPermitido = $configs['google_dominio_permitido'] ?? 'cecytlax.edu.mx';
        $activo = ($configs['google_auth_activo'] ?? '1') === '1';

        $redirectUri = url('/auth/google/callback');

        return view('cpanel/auth/config-google', compact(
            'clientId',
            'clientSecret',
            'modoAcceso',
            'dominioPermitido',
            'activo',
            'redirectUri'
        ));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'client_id'          => 'nullable|string',
            'client_secret'      => 'nullable|string',
            'modo_acceso'        => 'required|in:solo_institucional,hibrido',
            'dominio_permitido'  => 'required|string',
            'activo'             => 'nullable|boolean',
        ]);

        $settings = [
            'google_client_id'         => trim($data['client_id'] ?? ''),
            'google_client_secret'     => trim($data['client_secret'] ?? ''),
            'google_modo_acceso'       => $data['modo_acceso'],
            'google_dominio_permitido' => strtolower(trim($data['dominio_permitido'])),
            'google_auth_activo'       => $request->has('activo') ? '1' : '0',
        ];

        foreach ($settings as $clave => $valor) {
            DB::table('configuraciones_sistema')->updateOrInsert(
                ['clave' => $clave],
                ['valor' => $valor, 'updated_at' => now()]
            );
        }

        return redirect()->route('cpanel/auth/config-google')->with('success', 'Configuración de Google OAuth actualizada correctamente.');
    }
}

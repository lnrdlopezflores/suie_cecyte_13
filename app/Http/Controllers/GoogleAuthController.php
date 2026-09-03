<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Asegúrate de apuntar a tu modelo de usuarios (Usuario o User)
use App\Models\Docente;
use Illuminate\Support\Facades\DB;
use Exception;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Exception $e) {
            return redirect('/')->withErrors([
                'username' => 'No se pudo conectar con el servicio de Google: ' . $e->getMessage()
            ]);
        }

        $email = strtolower(trim($googleUser->getEmail()));
        $googleId = $googleUser->getId();
        $googleName = trim($googleUser->getName() ?? '');
        $googleGivenName = trim($googleUser->user['given_name'] ?? '');
        $googleFamilyName = trim($googleUser->user['family_name'] ?? '');

        $usuario = null;
        $expedienteTipo = null; // 'docente', 'alumno', 'personal'
        $expedienteId = null;

        // =========================================================================
        // 1. BUSCAR EN LA TABLA USUARIOS DIRECTAMENTE
        // =========================================================================
        $usuario = User::where('google_id', $googleId)
            ->orWhere('email', $email)
            ->first();

        // =========================================================================
        // 2. BUSCAR POR PREFIJO DE CORREO (MATRÍCULA / IDENTIFICADOR)
        // =========================================================================
        if (!$usuario) {
            $prefijo = explode('@', $email)[0];
            $usuario = User::where('username', $prefijo)->first();
        }

        // =========================================================================
        // 3. BUSCAR EN EXPEDIENTE DE DOCENTES (CORREO O NOMBRE)
        // =========================================================================
        if (!$usuario && DB::getSchemaBuilder()->hasTable('docentes')) {
            $docentes = DB::table('docentes')->get();

            foreach ($docentes as $doc) {
                $correoDoc = $this->desencriptarDato($doc->correo ?? '');
                $nombreDoc = $this->desencriptarDato($doc->nombre ?? '');
                $paternoDoc = $this->desencriptarDato($doc->apellido_paterno ?? '');

                // Coincidencia por correo
                if (!empty($correoDoc) && strtolower(trim($correoDoc)) === $email) {
                    $usuario = User::find($doc->usuario_id);
                    $expedienteTipo = 'docente';
                    $expedienteId = $doc->id;
                    break;
                }

                // Coincidencia por nombre completo de Google
                $nombreCompletoDoc = strtolower(trim("$nombreDoc $paternoDoc"));
                $nombreGoogle = strtolower(trim($googleName));

                if (!empty($nombreDoc) && !empty($googleName) && 
                    (str_contains($nombreGoogle, strtolower($nombreDoc)) && str_contains($nombreGoogle, strtolower($paternoDoc)))) {
                    $usuario = User::find($doc->usuario_id);
                    $expedienteTipo = 'docente';
                    $expedienteId = $doc->id;
                    break;
                }
            }
        }

        // =========================================================================
        // 4. BUSCAR EN EXPEDIENTE DE ALUMNOS (CORREO, MATRÍCULA O NOMBRE)
        // =========================================================================
        if (!$usuario && DB::getSchemaBuilder()->hasTable('alumnos')) {
            $alumnos = DB::table('alumnos')
                ->join('usuarios', 'alumnos.usuario_id', '=', 'usuarios.id')
                ->select('alumnos.*', 'usuarios.username')
                ->get();

            foreach ($alumnos as $alm) {
                $nombreAlm = $this->desencriptarDato($alm->nombre ?? '');
                $paternoAlm = $this->desencriptarDato($alm->apellido_paterno ?? '');
                $correoAlm = property_exists($alm, 'correo') ? $this->desencriptarDato($alm->correo ?? '') : null;

                // Coincidencia por correo guardado
                if (!empty($correoAlm) && strtolower(trim($correoAlm)) === $email) {
                    $usuario = User::find($alm->usuario_id);
                    $expedienteTipo = 'alumno';
                    $expedienteId = $alm->id;
                    break;
                }

                // Coincidencia por nombre completo
                $nombreGoogle = strtolower(trim($googleName));
                if (!empty($nombreAlm) && !empty($googleName) && 
                    (str_contains($nombreGoogle, strtolower($nombreAlm)) && str_contains($nombreGoogle, strtolower($paternoAlm)))) {
                    $usuario = User::find($alm->usuario_id);
                    $expedienteTipo = 'alumno';
                    $expedienteId = $alm->id;
                    break;
                }
            }
        }

        // =========================================================================
        // 5. VALIDACIÓN DE EXISTENCIA Y ESTATUS
        // =========================================================================
        if (!$usuario) {
            return redirect('/')->withErrors([
                'username' => "La cuenta ({$email}) no se encuentra registrada ni vinculada en el SUIE. Solicita tu alta en Control Escolar."
            ]);
        }

        if (!$usuario->activo) {
            return redirect('/')->withErrors([
                'username' => 'Tu usuario se encuentra temporalmente suspendido. Contacta a la coordinación.'
            ]);
        }

        // =========================================================================
        // 6. ACTUALIZAR CORREO Y GOOGLE_ID EN LA TABLA 'USUARIOS'
        // =========================================================================
        $usuario->google_id = $googleId;
        $usuario->email = $email;
        $usuario->save();

        // =========================================================================
        // 7. ACTUALIZAR EL CORREO EN LA TABLA DE SU EXPEDIENTE ESPECÍFICO
        // =========================================================================
        $rolLimpio = strtolower($usuario->rol);

        if ($rolLimpio === 'docente' && DB::getSchemaBuilder()->hasTable('docentes')) {
            DB::table('docentes')
                ->where('usuario_id', $usuario->id)
                ->update(['correo' => $email]);
        } elseif ($rolLimpio === 'estudiante' && DB::getSchemaBuilder()->hasTable('alumnos')) {
            if (DB::getSchemaBuilder()->hasColumn('alumnos', 'correo')) {
                DB::table('alumnos')
                    ->where('usuario_id', $usuario->id)
                    ->update(['correo' => $email]);
            }
        }

        // =========================================================================
        // 8. AUTENTICACIÓN ELOQUENT Y REGENERACIÓN DE SESIÓN
        // =========================================================================
        if ($usuario->google2fa_enabled) {
            session(['2fa_user_id' => $usuario->id]);
            return redirect()->route('2fa.challenge');
        }

        session(['2fa_passed' => true]);
        Auth::login($usuario);
        request()->session()->regenerate();

        // =========================================================================
        // 9. REDIRECCIÓN SEGÚN EL ROL EXACTO DEL SISTEMA
        // =========================================================================
        return match ($rolLimpio) {
            'administrador'   => redirect()->route('usuarios.index'),
            'coordinador'     => redirect()->route('cargas.index'),
            'control escolar' => redirect()->route('cargas.index'),
            'docente'         => redirect()->route('docentes.index'),
            'estudiante'      => redirect()->route('indexalumnos.index'),
            'orientador'      => redirect()->route('orientador.index'),
            default           => redirect('/'),
        };
    }

    /**
     * Auxiliar defensivo para desencriptar datos si están cifrados con encrypt()
     */
    private function desencriptarDato(?string $valor): string
    {
        if (empty($valor)) return '';
        
        try {
            if (is_string($valor) && (str_starts_with($valor, 'ey') || strlen($valor) > 50)) {
                return decrypt($valor);
            }
        } catch (\Throwable $e) {}

        return str_replace(' (Plain)', '', $valor);
    }
}
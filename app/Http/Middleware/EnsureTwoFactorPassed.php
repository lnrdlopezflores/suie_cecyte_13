<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureTwoFactorPassed
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Si tiene 2FA habilitado pero no ha superado el desafío en esta sesión
        if ($user && $user->google2fa_enabled && !session('2fa_passed')) {
            Auth::logout();
            session(['2fa_user_id' => $user->id]);
            return redirect()->route('2fa.challenge');
        }

        return $next($request);
    }
}

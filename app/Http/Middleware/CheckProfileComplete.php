<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if ($user && (is_null($user->nimd) || is_null($user->telepon))) {
            return redirect()->route('settings.profile')
                ->with('warning', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        return $next($request);
    }
}
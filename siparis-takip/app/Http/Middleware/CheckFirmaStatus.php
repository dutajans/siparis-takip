<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckFirmaStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $kullanici = Auth::user();

            // Kullanıcı aktif değilse
            if (!$kullanici->aktif) {
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Hesabınız devre dışı bırakılmış.');
            }

            // Firma aktif değilse
            if (!$kullanici->firma || !$kullanici->firma->aktif) {
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Firmanız devre dışı bırakılmış.');
            }

            // Firmanın abonelik süresi dolmuş mu?
            if ($kullanici->firma->bitis_tarihi && $kullanici->firma->bitis_tarihi < now()) {
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Firmanızın abonelik süresi dolmuştur.');
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Checkrole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role == 'admin' ||
            Auth::user()->role == 'koordinatorakreditasifikes' ||
            Auth::user()->role == 'koordinatorprodis1kesling' ||
            Auth::user()->role == 'timpenyusun' ||
            Auth::user()->role == 'gpmfikes' ||
            Auth::user()->role == 'timlpm' ||
            Auth::user()->role == 'dekan'
        ) {
            return $next($request);
        }
        return redirect('login');
    }
}

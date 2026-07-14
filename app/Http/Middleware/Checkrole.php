<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Checkrole
{
    /**
     * Daftar semua role yang diizinkan akses ke sistem.
     *
     * Role sesuai struktur FIKES UIS:
     * - admin                     → Admin Sistem
     * - koordinatorakreditasifikes → Koordinator Akreditasi FIKes
     * - koordinatorprodi           → Koordinator Prodi
     * - timpenyusun                → Tim Penyusun (Dosen)
     * - gpmfikes                   → GPM FIKes
     * - timlpmrektorat             → Tim LPM/Rektorat
     * - dekan                      → Pimpinan (Dekan)
     */
    public const ROLES = [
        'admin'                       => 'Admin Sistem',
        'koordinatorakreditasifikes'  => 'Koordinator Akreditasi FIKes',
        'koordinatorprodi'            => 'Koordinator Prodi',
        'timpenyusun'                 => 'Tim Penyusun (Dosen)',
        'gpmfikes'                    => 'GPM FIKes',
        'timlpmrektorat'              => 'Tim LPM/Rektorat',
        'dekan'                       => 'Pimpinan (Dekan)',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && array_key_exists(Auth::user()->role, self::ROLES)) {
            return $next($request);
        }

        return redirect()->route('login');
    }
}

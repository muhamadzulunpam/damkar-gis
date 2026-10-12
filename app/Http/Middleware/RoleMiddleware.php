<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Contoh pemakaian di route:
     *   ->middleware('role:admin')
     *   ->middleware('role:admin,petugas')
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            abort(401, 'Unauthorized.');
        }

        // Ambil role user
        $userRole = auth()->user()->role;

        // Cek apakah role user termasuk yang diizinkan
        if (!in_array($userRole, $roles, true)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}
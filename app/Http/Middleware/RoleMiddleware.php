<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    // Tanda ...$roles artinya dia bisa menerima lebih dari 1 role (contoh: Admin, Produksi)
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Cek apakah role user saat ini ada di dalam daftar role yang diizinkan
        if (in_array(Auth::user()->role, $roles)) {
            return $next($request);
        }

        // Kalau tidak ada, tendang dengan 403!
        abort(403, 'MAAF, ANDA TIDAK MEMILIKI HAK AKSES KE HALAMAN INI!');
    }
}
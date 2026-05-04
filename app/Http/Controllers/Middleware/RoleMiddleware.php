<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah pengguna sudah login
        if (!Auth::check()) {
            // Jika belum, arahkan ke halaman login
            return redirect()->route('login');
        }

        // Cek apakah role pengguna sesuai dengan yang dibutuhkan
        if (Auth::user()->role !== $role) {
            // Jika tidak sesuai, hentikan akses dan tampilkan error 403
            abort(403, 'Unauthorized access.');
        }

        // Jika lolos pengecekan, lanjutkan ke halaman yang diminta
        return $next($request);
    }
}
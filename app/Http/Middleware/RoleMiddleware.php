<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Belum login → redirect ke halaman login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        // Jika role user tidak ada di daftar role yang diizinkan
        if (!in_array($userRole, $roles)) {

            // Jika request dari AJAX / API → kembalikan JSON 403
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Akses ditolak. Anda tidak memiliki izin.',
                ], 403);
            }

            // Redirect ke dashboard sesuai role masing-masing
            $message = 'Anda tidak memiliki akses ke halaman tersebut.';

            if ($userRole === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', $message);
            }

            if ($userRole === 'petugas') {
                return redirect()->route('petugas.dashboard')->with('error', $message);
            }

            if ($userRole === 'peminjam') {
                return redirect()->route('peminjam.dashboard')->with('error', $message);
            }

            // Fallback jika role tidak dikenali
            return redirect()->route('login');
        }

        return $next($request);
    }
}
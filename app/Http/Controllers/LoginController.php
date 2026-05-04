<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Ambil kredensial
        $credentials = $request->only('email', 'password');

        // Coba login
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Regenerasi session biar aman
            $request->session()->regenerate();

            // Ambil user yang login
            $user = Auth::user();

            // Redirect berdasarkan role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'petugas':
                    return redirect()->route('petugas.dashboard');
                case 'peminjam':
                    return redirect()->route('peminjam.dashboard');
                default:
                    // Role tidak dikenal -> logout dan error
                    Auth::logout();
                    return back()->withErrors(['email' => 'Role tidak valid. Hubungi admin.']);
            }
        }

        // Jika login gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Logout
     */
public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login'); // ← pakai route name, bukan URL manual
}
}
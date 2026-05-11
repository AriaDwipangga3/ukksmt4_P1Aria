<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ActivityLogger;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Catat log login
            ActivityLogger::log('login', 'auth', 'User login', ['email' => $user->email]);

            switch ($user->role) {
                case 'admin': return redirect()->route('admin.dashboard');
                case 'petugas': return redirect()->route('petugas.dashboard');
                case 'peminjam': return redirect()->route('peminjam.dashboard');
                default:
                    Auth::logout();
                    return back()->withErrors(['email' => 'Role tidak valid.']);
            }
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Catat log logout sebelum logout
        if (Auth::check()) {
            ActivityLogger::log('logout', 'auth', 'User logout');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
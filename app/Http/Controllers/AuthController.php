<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman autentikasi
    public function index()
    {
        return view('Auth.AuthController');
    }

    // Memproses autentikasi
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect Manajer
            if ($user->role === 'manajer') {
                return redirect()->route('manajer.dashboard');
            }

            // Redirect Kasir
            if ($user->role === 'kasir') {
                return redirect()->route('kasir.dashboard');
            }

            // Jika role tidak memiliki akses website
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors([
                    'username' => 'Akun tidak memiliki akses website.',
                ]);
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
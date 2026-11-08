<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status_aktif' => 1])) {
            
            AuditLog::record('Login', 'Autentikasi', 'User berhasil masuk ke sistem ERP OVOEGG');

            $request->session()->regenerate();
            return redirect()->intended('/'); 
        }

        return back()->withErrors([
            'email' => 'Email atau password salah, atau akun Anda sedang dinonaktifkan.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            AuditLog::record('Logout', 'Autentikasi', 'User telah keluar dari sistem');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}
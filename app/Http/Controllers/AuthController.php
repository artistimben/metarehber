<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Login sayfasını göster
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Login işlemini gerçekleştir
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Kullanıcının rolüne göre yönlendir
            if ($user->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->isCoach()) {
                return redirect()->intended('/coach/dashboard');
            } elseif ($user->isStudent()) {
                return redirect()->intended('/student/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Giriş bilgileri hatalı.',
        ])->onlyInput('email');
    }

    /**
     * Logout işlemini gerçekleştir
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}

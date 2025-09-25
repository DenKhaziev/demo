<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StudentLoginController extends Controller
{
    public function show()
    {
        return Inertia::render('Auth/LoginPage');
    }

    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

     if (auth('child')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('student.index');
        }

        return back()->withErrors([
            'email' => 'Неверный логин или пароль',
        ]);
    }

    public function logout(Request $request)
    {
        auth('child')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('student.login');
    }
}


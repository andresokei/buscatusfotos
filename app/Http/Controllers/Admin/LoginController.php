<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        // Credenciales básicas (en producción usar hash)
        if ($username === 'andresokei' && $password === 'admin2020!') {
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.index');
        }

        return back()->with('error', 'Credenciales incorrectas');
    }

    public function logout()
    {
        session()->forget('admin_logged_in');
        return redirect()->route('admin.login');
    }
}
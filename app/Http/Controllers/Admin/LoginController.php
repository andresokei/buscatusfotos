<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        $adminUsername = config('admin.username');
        $adminPasswordHash = config('admin.password_hash');

        if (! $adminUsername || ! $adminPasswordHash) {
            Log::error('Admin login is not configured');

            return back()->with('error', 'El acceso de administracion no esta configurado');
        }

        $validUsername = hash_equals($adminUsername, $credentials['username']);
        $validPassword = Hash::check($credentials['password'], $adminPasswordHash);

        if ($validUsername && $validPassword) {
            $request->session()->regenerate();
            session(['admin_logged_in' => true]);

            return redirect()->route('admin.index');
        }

        return back()->with('error', 'Credenciales incorrectas');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

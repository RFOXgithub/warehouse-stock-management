<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $token = bin2hex(random_bytes(40));
            session(['auth_token' => $token]);

            return response()->json([
                'message' => 'Login berhasil',
                'token' => $token,
                'user' => Auth::user(),
            ]);
        }

        return response()->json(['message' => 'Login gagal'], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logout berhasil']);
    }
}

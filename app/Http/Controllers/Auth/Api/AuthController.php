<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // API login: validasi credential, buat token, kirim token ke client
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cek user berdasar email
        $user = User::where('email', $credentials['email'])->first();

        // Cek password
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(
                [
                    'message' => 'Email atau password salah.',
                ],
                401,
            );
        }

        // Jika valid, buat personal access token (sanctum)
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(
            [
                'message' => 'Login berhasil',
                'user' => $user,
                'token' => $token,
            ],
            200,
        );
    }

    // API register: buat user baru
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(
            [
                'message' => 'Registrasi berhasil',
                'user' => $user,
            ],
            201,
        );
    }

    // API logout: hapus token yang sedang dipakai
    public function logout(Request $request)
    {
        // Pastikan user sudah login dan memiliki token yang valid
        if ($request->user()) {
            // Hapus semua token user yang login ini
            $request->user()->tokens()->delete();

            return response()->json(
                [
                    'message' => 'Logout berhasil.',
                ],
                200,
            );
        }

        return response()->json(
            [
                'message' => 'Tidak ada user yang sedang login.',
            ],
            401,
        );
    }
}

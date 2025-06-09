<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    // Tampilkan form edit profil
    public function edit()
    {
        $user = Auth::user();

        // Ensure $user is an instance of App\Models\User
        if (! ($user instanceof \App\Models\User)) {
            $user = \App\Models\User::find(Auth::id());
        }

        return view('admin.profiles.edit', compact('user'));
    }

    // Update profil pengguna
    public function update(Request $request)
    {
        $user = Auth::user();

        // Ensure $user is an instance of App\Models\User
        if (! ($user instanceof \App\Models\User)) {
            $user = \App\Models\User::find(Auth::id());
        }

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        // Update nama dan email
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password jika ada
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}

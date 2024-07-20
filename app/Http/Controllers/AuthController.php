<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // proses login
    public function login(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Credentials are incorrect.'], 401);
        }

        $user->tokens()->delete(); // menghapus token sebelumnya
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'level' => $user->role_id,
                'tgl_buat' =>date('Y-m-d H:i:s', strtotime($user->timestamps)),
            ],
            'message' => 'login berhasil',
            'token' => $token,
        ], 200);

    }

    // fungsi current
    public function me(Request $request) {
        $user = auth()->user();

        if ($user) {
            return response()->json([
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'message' => 'Informasi pengguna saat ini',
            ], 200);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }
}

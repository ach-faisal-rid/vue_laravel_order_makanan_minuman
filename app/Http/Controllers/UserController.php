<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // function create user
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role_id' => ['required', Rule::in([1, 2, 3, 4])]
        ]);

        $validated['password'] = Hash::make($request->password);
        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'level' => $user->role_id, // or $user->level if mapped
                'tgl_buat' => date('Y-m-d H:i:s', strtotime($user->created_at)),
            ],
            'message' => 'user berhasil dibuat',
        ], 201);
    }

}

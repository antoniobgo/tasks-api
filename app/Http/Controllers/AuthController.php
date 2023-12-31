<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(! Auth::attempt($validated)) {
            return response()->json([
                'message' => 'Invalid login credentials'
            ],401);
        }

        $user = User::where('email', $validated['email'])->first();

        return response()->json([
            'access_token' => $user->createToken('api_token')->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }

    public function register(Request $request) {

        $validated = $request->validate([
            'name' => 'required|max:255',
            'email'=> 'required|max:255|unique:users,email',
            'password'=> 'required|confirmed|max:255'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json([
            'data' => $user,
            'access_token' => $user->createToken('api_token')->plainTextToken,
            'token_type' => 'Bearer',
        ],201);
    }
}

// {#7261
//     name: "Josiane Pouros Sr.",
//     email: "jaylon.hudson@example.org",
//     email_verified_at: "2023-09-06 13:25:45",
//     #password: "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi",
//     #remember_token: "CFvMeBLXOO",
//     updated_at: "2023-09-06 13:25:45",
//     created_at: "2023-09-06 13:25:45",
//     id: 1,
//   }

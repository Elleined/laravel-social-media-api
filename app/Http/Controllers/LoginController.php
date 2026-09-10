<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Hash;

class LoginController
{
    public function login(LoginRequest $loginRequest)
    {
        $validated = $loginRequest->validated();

        $email = $validated['email'];
        $password = $validated['password'];

        $hashedPassword = User::query()->where('email', '=', $email)->value('password');
        if (! Hash::check($password, $hashedPassword)) {
            return response()->json([
                'message' => 'The provided credentials do not match our records.',
            ], 404);
        }

        return response()->json([
            'message' => 'Login successful.',
            'requires_password_rehash' => Hash::needsRehash($hashedPassword),
        ]);
    }
}

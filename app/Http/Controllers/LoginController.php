<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Hash;

class LoginController
{
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $email = $validated['email'];
        $password = $validated['password'];

        $user = User::withTrashed()
            ->where('email', '=', $email)
            ->first();

        if ($user->isDeleted()) {
            return response()->json([
                'message' => 'This account is inactive. Please contact support.',
            ], 403);
        }

        $hashedPassword = $user->password;
        if (! Hash::check($password, $hashedPassword)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
            ], 404);
        }

        $deviceName = $request->userAgent() ?? 'login';
        $token = $user->createToken(name: $deviceName, expiresAt: now()
            ->plus(weeks: 1))
            ->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
            'requires_password_rehash' => Hash::needsRehash($hashedPassword),
        ]);
    }
}

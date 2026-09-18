<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;

class RegisterController
{
    public function register(UserRequest $request)
    {
        $user = User::query()
            ->create($request->validated());

        // send welcome email

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user,
        ], 201);
    }
}

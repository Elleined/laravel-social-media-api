<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Mail\WelcomeMail;
use App\Models\User;
use Mail;

class RegisterController
{
    public function register(UserRequest $request)
    {
        // Validate
        $requestBody = $request->validated();

        // Store the attachment
        $path = null;
        if ($request->filled('attachment')) {
            $path = $request
                ->file('attachment')
                ->storePublicly('profiles', 'public');
        }

        // Save the user
        $user = User::create([
            ...$requestBody,
            'attachment' => $path,
        ]);

        // Send welcome email
        Mail::to($user->email)
            ->send(new WelcomeMail($user->fullName()));

        return response()->json([
            'message' => 'User created successfully',
            'data' => UserResource::make($user),
        ], 201);
    }
}

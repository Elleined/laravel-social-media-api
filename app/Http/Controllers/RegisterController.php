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
        $user = User::create($request->validated());
        $fullName = $user->first_name.' '.$user->last_name;

        Mail::to($user->email)
            ->send(new WelcomeMail($fullName));

        return response()->json([
            'message' => 'User created successfully',
            'data' => UserResource::make($user),
        ], 201);
    }
}

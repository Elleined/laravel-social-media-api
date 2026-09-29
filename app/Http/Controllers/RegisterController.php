<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Mail\WelcomeMail;
use App\Models\User;
use DB;
use Exception;
use Mail;
use Storage;

class RegisterController
{
    public function register(UserRequest $request)
    {

        $path = null;

        try {
            // Store the attachment if present only
            if ($request->hasFile('attachment')) {
                $path = $request
                    ->file('attachment')
                    ->storePublicly('profiles', 'public');
            }

            // Validate the request
            $requestBody = $request->validated();

            // Save the user
            $user = DB::transaction(function () use ($requestBody, $path) {
                return User::create([
                    ...$requestBody,
                    'attachment' => $path,
                ]);
            });

            // Send welcome email
            Mail::to($user->email)
                ->send(new WelcomeMail($user->fullName()));

            return response()->json([
                'message' => 'User created successfully',
                'data' => UserResource::make($user),
            ], 201);
        } catch (Exception $e) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return response()->json([
                'message' => 'Upload failed. Changes were reverted.',
                'error' => $e->getMessage(),
            ], 500);
        }

    }
}

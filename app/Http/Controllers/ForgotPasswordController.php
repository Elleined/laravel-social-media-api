<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordChangeRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ForgotPasswordVerifyRequest;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Str;

class ForgotPasswordController
{
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        // 1. Validate email input & check if user exists
        // Security practice: Return a generic message if user doesn't exist. to prevent email enumeration/probing attacks.
        $requestBody = $request->validated();
        $email = $requestBody['email'];

        // 2. Check if the user has record in the password reset tokens table
        $existing = DB::table('password_reset_tokens')
            ->where('email', '=', $email)
            ->first();

        // 2.1 Theres a record and NOT EXPIRED token
        if ($existing && Carbon::parse($existing->created_at)
            ->addMinutes(10)
            ->isFuture()) {
            return response()->json([
                'message' => 'An reset password link has already been sent. Please check your email or wait before requesting a new code.',
            ], 429);
        }

        // 3. Theres no record or EXPIRED token
        // Store hashed token with a 1-minute validity window
        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => hash('sha256', $token),
                'created_at' => Carbon::now(),
            ]
        );

        // 4. Send email
        // TODO: implementation. Generate a front-end url with the token

        return response()->json([
            'message' => 'If an account is associated with that email address, a password reset link has been sent. Please check your inbox.',
            'tokenWillBeRemove' => $token,
        ]);
    }

    public function verifyResetToken(ForgotPasswordVerifyRequest $request)
    {
        // 1. Validate email input & check if user exists
        // Security practice: Return a generic message if user doesn't exist. to prevent email enumeration/probing attacks.
        $requestBody = $request->validated();
        $token = $requestBody['token'];
        $email = $requestBody['email'];

        // 2. Check if the user has record in the password reset tokens table
        $existing = DB::table('password_reset_tokens')
            ->where('email', '=', $email)
            ->where('token', '=', hash('sha256', $token))
            ->first();

        if (! $existing) {
            return response()->json([
                'message' => 'The token provided is invalid or has expired. Please check your email and try again.',
            ], 422);
        }

        // 3. Check if token is EXPIRED
        if (Carbon::parse($existing->created_at)
            ->addMinutes(10)
            ->isPast()) {
            // 3.1 token is EXPIRED hence we delete the record
            DB::table('password_reset_tokens')
                ->where('email', '=', $email)
                ->delete();

            return response()->json([
                'message' => 'This verification code has expired. Please request a new code.',
            ], 422);
        }

        // 4. token is NOT EXPIRED
        return response()->noContent();
    }

    public function resetPassword(ForgotPasswordChangeRequest $request)
    {
        $requestBody = $request->validated();
        $token = $requestBody['token'];
        $email = $requestBody['email'];
        $newPassword = $requestBody['new_password'];

        $user = User::query()
            ->where('email', '=', $email)
            ->first();

        DB::transaction(function () use ($user, $email, $newPassword) {
            // Update the password
            $user->update([
                'password' => $newPassword,
            ]);

            // Reset all sessions across all logged in devices
            $user->tokens()->delete();

            // Delete the used token upon successful verification
            DB::table('password_reset_tokens')
                ->where('email', '=', $email)
                ->delete();
        });

    }
}

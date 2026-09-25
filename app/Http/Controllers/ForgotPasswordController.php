<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordChangeRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ForgotPasswordVerifyRequest;
use App\Mail\ForgotPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Str;
use Mail;

class ForgotPasswordController
{
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        // 1. Validate email input & check if user exists
        // Security practice: Return a generic message if user doesn't exist. to prevent email enumeration/probing attacks.
        [
            'email' => $email
        ] = $request->validated();

        // 2. Check if the user has record in the password reset tokens table
        $existing = DB::table('password_reset_tokens')
            ->where('email', '=', $email)
            ->first();

        // 2.1 Theres a record and NOT EXPIRED token
        if ($existing && Carbon::now()->lessThanOrEqualTo($existing->expires_at)) {
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
                'expires_at' => Carbon::now()->addMinutes(10),
            ]
        );

        // 4. Send email
        Mail::to($email)
            ->send(new ForgotPasswordMail($email, $token));
        // TODO: implementation. Generate a front-end url with the token

        return response()->json([
            'message' => 'If an account is associated with that email address, a password reset link has been sent. Please check your inbox.',
        ]);
    }

    public function verifyResetToken(ForgotPasswordVerifyRequest $request)
    {
        // 1. Validate email input & check if user exists
        // Security practice: Return a generic message if user doesn't exist. to prevent email enumeration/probing attacks.
        [
            'token' => $token,
            'email' => $email
        ] = $request->validated();

        // Validate the token
        [
            'valid' => $valid,
            'message' => $message
        ] = $this->validateToken($email, $token);

        if (! $valid) {
            return response()->json([
                'message' => $message,
            ], 422);
        }

        // 4. token is NOT EXPIRED
        return response()->noContent();
    }

    public function resetPassword(ForgotPasswordChangeRequest $request)
    {
        [
            'token' => $token,
            'email' => $email,
            'new_password' => $newPassword,
        ] = $request->validated();

        // Validate the token
        [
            'valid' => $valid,
            'message' => $message
        ] = $this->validateToken($email, $token);

        if (! $valid) {
            return response()->json([
                'message' => $message,
            ], 422);
        }

        $user = User::query()
            ->where('email', '=', $email)
            ->first();

        DB::transaction(function () use ($user, $email, $newPassword) {
            // 4. Update the password
            $user->update([
                'password' => $newPassword,
            ]);

            // 5. Reset all sessions across all logged in devices
            $user->tokens()->delete();

            // 6. Delete the used token upon successful verification
            DB::table('password_reset_tokens')
                ->where('email', '=', $email)
                ->delete();
        });
    }

    private function validateToken(string $email, string $token)
    {
        // 2. Check if the user has record in the password reset tokens table
        $existing = DB::table('password_reset_tokens')
            ->where('email', '=', $email)
            ->where('token', '=', hash('sha256', $token))
            ->first();

        if (! $existing) {
            return [
                'valid' => false,
                'message' => 'The token provided is invalid or has expired. Please check your email and try again.',
            ];
        }

        // 3. Check if token is EXPIRED
        if ($existing && Carbon::now()->greaterThanOrEqualTo($existing->expires_at)) {
            // 3.1 token is EXPIRED hence we delete the record
            DB::table('password_reset_tokens')
                ->where('email', '=', $email)
                ->delete();

            return [
                'valid' => false,
                'message' => 'This token has expired. Please request a new token.',
            ];
        }

        return ['valid' => true, 'message' => 'valid'];
    }
}

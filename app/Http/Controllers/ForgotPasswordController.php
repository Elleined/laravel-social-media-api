<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController
{
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        // 1. Validate email input & check if user exists
        // Security practice: Return a generic message if user doesn't exist. to prevent email enumeration/probing attacks.
        $requestBody = $request->validated();
        $email = $requestBody['email'];

        // 2. Check in OTP table if theres a record
        $existingOTP = DB::table('password_reset_tokens')
            ->where('email', '=', $email)
            ->first();

        // 2.1 Theres a record and the OTP IS NOT EXPIRED
        if ($existingOTP && Carbon::now()->lessThanOrEqualTo($existingOTP->expires_at)) {
            return response()->json([
                'message' => 'An active OTP has already been sent. Please check your email or wait before requesting a new code.',
            ], 429);
        }

        $plainOtp = (string) random_int(100000, 999999);

        // 3. Store hashed OTP with a 10-minute validity window
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'otp' => Hash::make($plainOtp),
                'expires_at' => Carbon::now()->addMinutes(10),
                'created_at' => Carbon::now(),
            ]
        );

        // 4. Send email
        // TODO: implementation

        return response()->json([
            'message' => 'If an account is associated with that email address, a password reset link has been sent. Please check your inbox.',
        ]);
    }

    public function verifyResetToken(Request $request) {}

    public function resetPassword(Request $request)
    {
        // reset all tokens
    }
}

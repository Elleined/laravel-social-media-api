<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;

class ForgotPasswordController
{
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $validated = $request->validated();

        // 1. Validate email input

        // 2. Find user by email

        // 3. CHECK FOR ACTIVE UNEXPIRED OTP:
        //    Query DB for an existing OTP for this email that hasn't expired yet.
        //    IF an active OTP exists:
        //        Return 422/429 error ("An active OTP was already sent. Please check your email or wait X minutes.")

        // 4. Generate new OTP & expiration timestamp

        // 5. Store OTP in DB

        // 6. Send Email Notification

        // check also if theres a active otp (token?)
        // request and send email
        // delete all tokens

        // resend email is just reuse the 3 above
        return $validated;
    }
}

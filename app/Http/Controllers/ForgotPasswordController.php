<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;

class ForgotPasswordController
{
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $validated = $request->validated();

        // expire token here
        return $validated;
    }
}

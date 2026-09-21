<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoutController
{
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers;

use App\ProviderType;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController
{
    public function redirect(ProviderType $providerType)
    {
        return Socialite::driver($providerType->value)
            ->stateless()
            ->redirect();
    }

    public function callback(ProviderType $providerType)
    {
        $user = Socialite::driver($providerType->value)
            ->stateless()
            ->user();

        return response()->json($user);
    }
}

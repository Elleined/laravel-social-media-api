<?php

namespace App\Http\Controllers;

use App\Models\Emoji;
use App\Models\ProviderType;
use Illuminate\Http\Request;

class ReferenceController
{
    public function emojis() {
        return Emoji::all();
    }

    public function providerTypes() {
        return ProviderType::all();
    }
}

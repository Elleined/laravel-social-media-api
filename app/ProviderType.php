<?php

namespace App;

enum ProviderType: string
{
    case GITHUB = 'github';
    case GOOGLE = 'google';
    case FACEBOOK = 'facebook';
}

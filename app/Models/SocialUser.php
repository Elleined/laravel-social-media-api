<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property \Illuminate\Support\Carbon $created_at
 * @property string $provider_type_id
 * @property string $provider_id
 * @property string $user_id
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\SocialUserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser whereProviderTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser whereUserId($value)
 * @mixin \Eloquent
 */
class SocialUser extends Model
{
    /** @use HasFactory<\Database\Factories\SocialUserFactory> */
    use HasFactory, HasUuids;

    public $timestamps = false;
}

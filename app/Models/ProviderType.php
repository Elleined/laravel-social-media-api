<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoji newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoji newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoji query()
 * @property string $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProviderType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProviderType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProviderType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProviderType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProviderType extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'ref_provider_types';
}

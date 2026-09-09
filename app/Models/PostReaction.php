<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $id
 * @property \Illuminate\Support\Carbon $created_at
 * @property string $emoji_id
 * @property string $reactor_id
 * @property string $post_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $reactions
 * @property-read int|null $reactions_count
 * @method static \Database\Factories\PostReactionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction whereEmojiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction whereReactorId($value)
 * @mixin \Eloquent
 */
class PostReaction extends Model
{
    /** @use HasFactory<\Database\Factories\PostReactionFactory> */
    use HasFactory, HasUuids;

    public $timestamps = false;

    protected $hidden = ['created_at'];

    protected $fillable = ['emoji_id', 'reactor_id', 'post_id'];    
}

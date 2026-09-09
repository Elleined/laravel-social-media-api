<?php

namespace App\Models;

use Dom\Comment;
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
 * @property string $comment_id
 * @method static \Database\Factories\CommentReactionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReaction whereCommentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReaction whereEmojiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReaction whereReactorId($value)
 * @mixin \Eloquent
 */
class CommentReaction extends Model
{
    /** @use HasFactory<\Database\Factories\CommentReactionFactory> */
    use HasFactory, HasUuids;

    public $timestamps = false;

    protected $hidden = ['created_at'];

    protected $fillable = ['emoji_id', 'reactor_id', 'comment_id'];
}

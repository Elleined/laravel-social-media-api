<?php

namespace App\Models;

use Dom\Comment;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CommentReaction extends Model
{
    /** @use HasFactory<\Database\Factories\CommentReactionFactory> */
    use HasFactory, HasUuids;

    public function reactions(): BelongsToMany {
        return $this->belongsToMany(Comment::class, 'comment_id');
    }
}

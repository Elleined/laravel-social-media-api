<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PostReaction extends Model
{
    /** @use HasFactory<\Database\Factories\PostReactionFactory> */
    use HasFactory, HasUuids;


    public function reactions(): BelongsToMany {
        return $this->belongsToMany(Post::class, 'comment_id');
    }
}

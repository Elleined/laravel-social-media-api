<?php

namespace App\Models;

use Dom\Comment;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    public function author() : BelongsTo {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments(): HasMany {
        return $this->hasMany(Comment::class, 'post_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory, HasUuids, SoftDeletes;


    public function post(): BelongsTo {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function author(): BelongsTo {
        return $this->belongsTo(User::class, 'author_id');
    }
}

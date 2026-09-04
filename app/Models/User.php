<?php

namespace App\Models;

use Dom\Comment;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasUuids, Notifiable, SoftDeletes;

    protected $table = 'users';

    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'attachment'];

    protected $hidden = ['password', 'created_at', 'updated_at', 'deleted_ata'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id')->chaperone();
    }

    public function socials(): HasMany
    {
        return $this->hasMany(SocialUser::class, 'user_id')->chaperone();
    }

    public function comments(): HasManyThrough
    {
        return $this->hasManyThrough(Comment::class, 'author_id');
    }
}

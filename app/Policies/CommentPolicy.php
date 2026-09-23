<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        return $user->isActive() &&
        $post->isActive();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Post $post): bool
    {
        return $this->view($user, $post);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post, Comment $comment): bool
    {
        return ($user->id === $comment->author_id) &&
        $user->isActive() &&
        $post->isActive() &&
        $comment->isActive();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post, Comment $comment): bool
    {
        return $this->update($user, $post, $comment);
    }
}

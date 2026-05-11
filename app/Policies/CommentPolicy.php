<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function updateOrDelete(User $user, Comment $comment): Response
    {
        return $user->id === $comment->user_id ? Response::allow() : Response::denyAsNotFound();
    }

}

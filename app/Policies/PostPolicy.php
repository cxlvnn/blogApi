<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    public function viewAny(): Response
    {
        return Response::allow();
    }

    public function view(): Response
    {
        return Response::allow();
    }

    public function updateOrDelete(User $user, Post $post): Response
    {
        return $user->id === $post->user_id ? Response::allow() : Response::denyAsNotFound();
    }
}

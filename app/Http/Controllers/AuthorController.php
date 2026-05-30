<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthorResource;
use App\Models\User;

class AuthorController extends Controller
{
    public function getAuthor($name)
    {
        $author = User::where('name', $name)->firstOrFail();

        return new AuthorResource($author);
    }
}

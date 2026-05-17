<?php

namespace App\Http\Controllers;

use App\Http\Resources\LikeResource;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function index(Post $post)
    {
        $likes = $post->likes;

        return response()->json([
            'data' => [
                'count' => count($likes),
            ],
        ]);
    }

    public function like(Post $post)
    {
        $like = Auth::user()->likes()->create([
            'post_id' => $post->id,
        ]);

        return 'hello';
        /* return new LikeResource($like); */
    }
}

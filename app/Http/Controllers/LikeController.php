<?php

namespace App\Http\Controllers;

use App\Models\Like;
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

    public function likeOrUnlike(Post $post)
    {
        $like = $post->likes()->where('user_id', Auth::user()->id)->first();

        if (! $like) {
            Like::create([
                'post_id' => $post->id,
                'user_id' => Auth::user()->id,
            ]);

            return response()->json([
                'liked' => true,
            ]);
        }

        $like->delete();

        return response()->json([
            'liked' => false,
        ], 200);
    }
}

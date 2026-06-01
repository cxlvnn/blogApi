<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookmarkResource;
use App\Models\Bookmark;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function indexForThree()
    {
        $user = Auth::user();
        $bookmarks = Bookmark::where('user_id', $user->id)->limit(3)->get();

        return BookmarkResource::collection($bookmarks);
    }

    public function saveOrUnsave(Post $post)
    {
        $user = Auth::user();
        $bookmark = Bookmark::where('post_id', $post->id)->where('user_id', $user->id)->first();

        if (! $bookmark) {
            Bookmark::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);

            return response()->json(['bookmarked' => true], 200);
        }

        $bookmark->delete();

        return response()->json(['bookmarked' => false], 200);
    }
}

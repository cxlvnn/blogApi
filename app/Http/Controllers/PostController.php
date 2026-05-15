<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Auth::user()->posts()->with('comments')->paginate();

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $post_data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'max:1000'],
        ]);

        $post = Auth::user()->posts()->create($post_data);

        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        Gate::authorize('view', $post);

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('updateOrDelete', $post);
        $update_post_data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'max:1000'],
        ]);

        $post->update($update_post_data);

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('updateOrDelete', $post);
        $post->deleteOrFail();

        return response()->noContent();
    }
}

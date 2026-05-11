<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Post $post)
    {
        $comments = $post->comments()->paginate();

        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Post $post)
    {
        $comment = $post->comments()->create([
            'content' => $request->content,
            'user_id' => Auth::user()->id,
        ]);

        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post, Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Post $post, Comment $comment)
    {
        Gate::authorize('updateOrDelete', $comment);
        $comment->update($request->validated());

        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Comment $comment)
    {
        Gate::authorize('updateOrDelete', $comment);
        $comment->delete();

        return response()->noContent();
    }
}

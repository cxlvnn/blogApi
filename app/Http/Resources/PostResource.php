<?php

namespace App\Http\Resources;

use App\Models\Bookmark;
use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'post',
            'id' => $this->id,
            'title' => $this->title,
            /* 'body' => $this->when( */
            /*     ! $request->routeIs('posts.index'), */
            /*     $this->body */
            /* ), */
            'body' => $this->body,
            'likeCount' => count($this->likes),
            'userLiked' => Like::where('user_id', Auth::user()->id)->where('post_id', $this->id)->exists() ? 'true' : 'false',
            'userSaved' => Bookmark::where('user_id', Auth::user()->id)->where('post_id', $this->id)->exists() ? true : false,
            'createdAt' => $this->created_at->format('M d, Y'),
            'updatedAt' => $this->updated_at->format('M d, Y'),
            'relationships' => [
                'author' => new UserResource(User::findOrFail($this->user_id)),
                'comments' => CommentResource::collection($this->comments),
            ],
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'body' => $this->when(
                ! $request->routeIs('posts.index'),
                $this->body
            ),
            'createdAt' => $this->created_at->diffForHumans(),
            'updatedAt' => $this->updated_at->diffForHumans(),
            'relationships' => [
                'author' => new UserResource(User::findOrFail($this->user_id)),
                'comments' => CommentResource::collection($this->comments),
            ],
        ];
    }
}

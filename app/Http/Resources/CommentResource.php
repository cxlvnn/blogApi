<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'comment',
            'id' => $this->id,
            'postTitle' => $this->when(
                $request->routeIs('comments.show') || $request->routeIs('comments.index'),
                $this->post->title,
            ),
            'text' => $this->content,
            'relationships' => [
                'authorName' => $this->user->name,
            ],
        ];
    }
}

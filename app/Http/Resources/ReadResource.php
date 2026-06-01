<?php

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'postId' => Post::where('id', $this->id)->value('id'),
            'postTitle' => Post::where('id', $this->id)->value('title'),
            'readAt' => $this->created_at->diffForHumans(),
        ];
    }
}

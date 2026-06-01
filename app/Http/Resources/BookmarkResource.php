<?php

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkResource extends JsonResource
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
            'bookmarkedPostId' => Post::where('id', $this->post_id)->value('id'),
            'bookmarkedPostTitle' => Post::where('id', $this->post_id)->value('title'),
            'bookmarkedAt' => $this->created_at->diffForHumans(),
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Models\Read;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'joinedAt' => $this->created_at->format('Y'),
            'type' => $this->type,
            'readCount' => Read::where('user_id', $this->id)->count(),
            'bookmarkCount' => $this->bookmarks()->count(),
            'streak' => $this->streak,
            'relationships' => [
                'bookmarks' => BookmarkResource::collection($this->bookmarks),
            ],
        ];
    }
}

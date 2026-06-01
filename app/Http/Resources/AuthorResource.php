<?php

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
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
            'postCount' => Post::where('user_id', $this->id)->count(),
            'readCount' => $this->reads()->count(),
            'bio' => $this->bio,
            'address' => $this->address,
            'website' => $this->website,
            'joinedAt' => $this->created_at->format('Y'),
        ];
    }
}

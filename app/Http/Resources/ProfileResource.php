<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'postCount' => $this->postCount,
            'bio' => $this->bio,
            'address' => $this->address,
            'readCount' => $this->read_count,
        ];
    }
}

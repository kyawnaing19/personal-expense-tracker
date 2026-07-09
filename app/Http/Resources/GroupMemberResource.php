<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupMemberResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar ?? 'https://ui-avatars.com' . urlencode($this->name),
            'role' => $this->pivot->role ?? 'member',
            'joined_at' => $this->pivot->joined_at ?? null,
        ];
    }
}

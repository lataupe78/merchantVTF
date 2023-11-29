<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInvitationResource extends JsonResource
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
            'email' => $this->email,
            'invitation_token' => $this->invitation_token,
            'registered_at' => $this->registered_at,
            'created_at' => $this->created_at,
            'link' => $this->getLink(),
        ];
    }
}

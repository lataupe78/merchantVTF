<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'phone' => $this->phone, 
            'is_active' => $this->is_active,
            'password' => $this->password, 
            'email_verified_at' => $this->email_verified_at, 
            'last_connected_at' => $this->last_connected_at, 
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'invitation' => new UserInvitationResource($this->whenLoaded('invitation')),
            'roles' => $this->roles,
            'sale_points' => $this->sale_points,
        ];
    }
}

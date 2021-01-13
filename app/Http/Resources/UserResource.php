<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[
            'user_id' => $this->user_id,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'verified' => $this->verified,
            'provider' => $this->provider,
            'status' => $this->status,
        ];
    }
}

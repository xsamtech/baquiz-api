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
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'surname' => $this->surname,
            'organization_name' => $this->organization_name,
            'gender' => $this->gender,
            'birthdate' => $this->birthdate,
            'country' => $this->country,
            'city' => $this->city,
            'address_1' => $this->address_1,
            'address_2' => $this->address_2,
            'p_o_box' => $this->p_o_box,
            'currency' => $this->currency,
            'email' => $this->email,
            'phone' => $this->phone,
            'email_verified_at' => $this->email_verified_at,
            'phone_verfied_at' => $this->phone_verfied_at,
            'username' => $this->username,
            'api_key' => $this->api_key,
            'avatar_url' => $this->avatar_url,
            'cover_url' => $this->cover_url,
            'belongs_to' => $this->belongs_to,
            'promo_code' => $this->promo_code,
            'two_factor_email_confirmed_at' => $this->two_factor_email_confirmed_at,
            'two_factor_phone_confirmed_at' => $this->two_factor_phone_confirmed_at,
            'tips_at_every_login' => $this->tips_at_every_login,
            'is_online' => $this->is_online,
            'status' => $this->status,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'owner' => new UserResource($this->whenLoaded('owner')),
        ];
    }
}

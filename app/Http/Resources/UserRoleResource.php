<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserRoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {

        $result = [
            'id' => $this->id,
            'username' => $this->username,
            'image' => '/storage/user/'.$this->getImage(),
            'name' => $this->name(),
        ];

        $ru = $this->getRoleUser();
        if ($ru) {
            $result['role_id'] = $ru->role_id;
            if ($ru->role_id == 3 or $ru->role_id == 4) {
                $result['publisher_id'] = $ru->publisher_id;
            }   
        } else {
            $result['role_id'] = 0;
        }

        return $result;
    }
}

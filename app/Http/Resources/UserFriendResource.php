<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserFriendResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user) {
            $status = $user->friendshipStatus($this->id);
        } else {
            $status = 0;
        }

        $result = [
            'id' => $this->id,
            'username' => $this->username,
            'image' => '/storage/user/'.$this->getImage(),
            'name' => $this->name(),
            'status' => $status,
        ];

        return $result;
    }
}

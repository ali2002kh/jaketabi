<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPrivateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $friend_requests = $this->getFriendRequests();
        $friends = $this->getFriends();

        $result = [
            'id' => $this->id,
            'username' => $this->username,
            'image' => '/storage/user/'.$this->getImage(),
            'name' => $this->name(),
            'email' => $this->email,
            'number' => $this->number,
            'birth_date' => $this->getProfile()->birth_date,
            'want_to_read' => BookPublicResource::collection($this->getWantToReadBooks()),
            'reading' => BookPublicResource::collection($this->getCurrentlyReadingBooks()),
            'already_read' => BookPublicResource::collection($this->getAlreadyReadBooks()),
            'shelves' => ShelfResource::collection($this->getShelves()),
            'friends' => UserPreviewResource::collection($friends),
            'friends_count' => $friends->count(),
            'friend_requests' => UserPreviewResource::collection($friend_requests),
            'friend_requests_count' => $friend_requests->count(),
        ];

        return $result;
    }
}

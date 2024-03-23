<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FriendBookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::find(1);

        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'image' => '/storage/book/'.$this->image,
            'preview_friends' => UserPreviewResource::collection($user->getFriendsReadingBookPreview($this->id)),
            'friends_who_already_read_this_book' => UserPreviewResource::collection($user->getFriendsWhoAlreadyReadThisBook($this->id)),
            'friends_who_are_reading_this_book' => UserPreviewResource::collection($user->getFriendsWhoAreReadingThisBook($this->id)),
        ];

        return $result;
    }
}

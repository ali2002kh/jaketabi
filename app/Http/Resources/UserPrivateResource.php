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
        $preview_book_number = 7;
        $preview_shelf_number = 3;
        $friend_requests = $this->getFriendRequests();
        $friends = $this->getFriends();

        $want_to_read = $this->getWantToReadBooks();
        $want_to_read_more_count = $want_to_read->count() - $preview_book_number;
        $reading = $this->getCurrentlyReadingBooks();
        $reading_more_count = $reading->count() - $preview_book_number;
        $already_read = $this->getAlreadyReadBooks();
        $already_read_more_count = $already_read->count() - $preview_book_number;

        $shelves = $this->getShelves();
        $shelves_more_count = $shelves->count() - $preview_shelf_number;

        $roleUser = $this->getRoleUser();

        $result = [
            'id' => $this->id,
            'username' => $this->username,
            'image' => '/storage/user/'.$this->getImage(),
            'name' => $this->name(),
            'email' => $this->email,
            'number' => $this->number,
            'want_to_read' => BookPreviewResource::collection($want_to_read->take($preview_book_number)),
            'reading' => BookPreviewResource::collection($reading->take($preview_book_number)),
            'already_read' => BookPreviewResource::collection($already_read->take($preview_book_number)),
            'shelves' => ShelfPreviewResource::collection($shelves->take($preview_shelf_number)),
            'friends' => UserPreviewResource::collection($friends),
            'friends_count' => $friends->count(),
            'friend_requests' => UserPreviewResource::collection($friend_requests),
            'friend_requests_count' => $friend_requests->count(),
            'is_private' => true,
            'role' => $roleUser,
        ];

        if ($roleUser && ($roleUser->role_id == 3 || $roleUser->role_id == 4)) {
            $result['publisher'] = new PublisherResource($this->getPublisher());
        }

        if ($this->getProfile()) {
            $result['birth_date'] = $this->getProfile()->birth_date;
            $result['fname'] = $this->getProfile()->first_name;
            $result['lname'] = $this->getProfile()->last_name;
        }

        if ($want_to_read_more_count > 0) {
            $result['has_more_want_to_read'] = true;
            $result['want_to_read_more_count'] = $want_to_read_more_count;
        } else {
            $result['has_more_want_to_read'] = false;
        }

        if ($reading_more_count > 0) {
            $result['has_more_reading'] = true;
            $result['reading_more_count'] = $reading_more_count;
        } else {
            $result['has_more_reading'] = false;
        }

        if ($already_read_more_count > 0) {
            $result['has_more_already_read'] = true;
            $result['already_read_more_count'] = $already_read_more_count;
        } else {
            $result['has_more_already_read'] = false;
        }

        if ($shelves_more_count > 0) {
            $result['has_more_shelves'] = true;
            $result['shelves_more_count'] = $shelves_more_count;
        } else {
            $result['has_more_shelves'] = false;
        }

        return $result;
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPublicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $preview_book_number = 5;

        $result = [
            'id' => $this->id,
            'username' => $this->username,
            'image' => '/storage/user/'.$this->getImage(),
            'name' => $this->name(),
            'want_to_read' => BookPreviewResource::collection($this->getWantToReadBooks()->take($preview_book_number)),
            'reading' => BookPreviewResource::collection($this->getCurrentlyReadingBooks()->take($preview_book_number)),
            'already_read' => BookPreviewResource::collection($this->getAlreadyReadBooks()->take($preview_book_number)),
            'shelves' => ShelfResource::collection($this->getShelves()),
        ];

        return $result;
    }
}

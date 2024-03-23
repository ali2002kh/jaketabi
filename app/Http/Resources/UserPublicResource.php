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
        $result = [
            'id' => $this->id,
            'username' => $this->username,
            'image' => '/storage/user/'.$this->getImage(),
            'name' => $this->name(),
            'want_to_read' => BookPublicResource::collection($this->getWantToReadBooks()),
            'reading' => BookPublicResource::collection($this->getCurrentlyReadingBooks()),
            'already_read' => BookPublicResource::collection($this->getAlreadyReadBooks()),
            'shelves' => ShelfResource::collection($this->getShelves()),
        ];

        return $result;
    }
}

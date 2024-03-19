<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this->getUser();

        $result = [
            'id' => $this->id,
            'username' => $user->username,
            'image' => $user->getImage(),
            'message' => $this->message,
            // 'book' => new BookPublicResource($this->getBook()),
        ];

        return $result;
    }
}

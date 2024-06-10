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
            'user' => new UserPreviewResource($user),
            'message' => $this->message,
            // 'book' => new BookPreviewResource($this->getBook()),
        ];

        return $result;
    }
}

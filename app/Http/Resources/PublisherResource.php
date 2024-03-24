<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublisherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $books = $this->getBooks();

        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'book_count' => $books->count(),
            'books' => BookPreviewResource::collection($books),
        ];

        return $result;
    }
}

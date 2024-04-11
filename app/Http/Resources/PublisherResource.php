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
        $preview_books_count = 5;

        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'book_count' => $books->count(),
            'preview_books' => BookPreviewResource::collection($books->take($preview_books_count)),
        ];

        return $result;
    }
}

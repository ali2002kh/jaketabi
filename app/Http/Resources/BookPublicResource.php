<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookPublicResource extends JsonResource
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
            'name' => $this->name,
            'image' => '/storage/book/'.$this->image,
            'author' => $this->author,
            'genres' => GenreResource::collection($this->getGenres()),
            'category' => $this->getCategory()->name,
            'category_id' => $this->category_id,
            'release_date' => $this->release_date,
            'publisher' => $this->getPublisher()->name,
            'publisher_id' => $this->publisher_id,
            'description' => $this->description,
            'translator' => $this->translator,
            'page_count' => $this->page_count,
            'related_books' => BookPreviewResource::collection($this->getRelatedBooks()),
            'want_to_read_count' => $this->getWantToReadCount(),
            'reading_count' => $this->getReadingCount(),
            'already_read_count' => $this->getAlreadyReadCount(),
        ];

        return $result;
    }
}

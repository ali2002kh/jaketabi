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
            'publisher' => new PublisherResource($this->getPublisher()),
            'description' => $this->description,
            'translator' => $this->translator,
            'page_count' => $this->page_count,
            'related_books' => BookPreviewResource::collection($this->getRelatedBooks()),
            'want_to_read_count' => $this->getWantToReadCount(),
            'reading_count' => $this->getReadingCount(),
            'already_read_count' => $this->getAlreadyReadCount(),
            'related_books' => BookPreviewResource::collection($this->getRelatedBooks()->take(5)),
        ];

        return $result;
    }
}

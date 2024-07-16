<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookPrivateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $genres = [];
        foreach ($this->getGenres() as $g) {
           $genres[] = $g->id;
        }

        $result = [
            'id' => $this->id,
            'isbn' => $this->ISBN,
            'name' => $this->name,
            // 'image' => '/storage/book/'.$this->image,
            'author' => $this->author,
            'category_id' => $this->category_id,
            'release_date' => $this->release_date,
            'publisher_id' => $this->getPublisher()->id,
            'description' => $this->description,
            'translator' => $this->translator,
            'page_count' => $this->page_count,
            'lcc' => $this->LCC,
            'ddc' => $this->DDC,
            'isbn_period' => $this->ISBN_period,
            'genres' => $genres,
        ];

        

        return $result;
    }
}

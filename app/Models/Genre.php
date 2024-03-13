<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function getBooks() {
        
        $books = collect();
        $records = GenreBook::where('genre_id', $this->id)->get();

        foreach($records as $r) {
            $b = $r->getBook();
            if ($b->isActive()) {
                $books->add($b);
            }
        }

        return $books;
    }
}

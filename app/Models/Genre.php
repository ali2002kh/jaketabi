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
        
        $books = array();
        $tmps = GenreBook::all()->where('genre_id', $this->id);

        foreach($tmps as $tmp) {
            $b = Book::find($tmp->book_id);
            if ($b->isActive()) {
                $books[] = $b;
            }
        }

        return $books;
    }
}

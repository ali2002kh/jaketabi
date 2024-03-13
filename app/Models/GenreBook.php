<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenreBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'genre_id',
        'book_id',
    ];

    public function getGenre() {
        return Genre::find($this->genre_id);
    }

    public function getBook() {
        return Book::find($this->book_id);
    }
}

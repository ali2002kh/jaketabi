<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookRelation extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'related_books_id',
    ];

    public function getBook() {
        return Book::find($this->book_id);
    }

    public function geRelatedBooks() {
        $books = collect();
        foreach (explode(',', $this->related_books_id) as $book_id) {
            $books->add(Book::find($book_id));
        }
        return $books;
    }
}

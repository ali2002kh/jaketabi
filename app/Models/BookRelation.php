<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookRelation extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'related_books',
    ];

    public function getBook() {
        return Book::find($this->book_id);
    }

    public function getRelatedBooks() {
        $relatedBooksJson = $this->related_books;
        $relatedBooks = json_decode($relatedBooksJson, true);
        $books = collect();
        foreach ($relatedBooks as $bookId => $relevance) {
            $book = Book::find($bookId);
            if ($book) {
                $books->add($book);
            }
        }
        return $books;
    }
}

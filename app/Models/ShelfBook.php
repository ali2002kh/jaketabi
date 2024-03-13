<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShelfBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'shelf_id',
        'book_id',
    ];

    public function getShelf() {
        return Shelf::find($this->shelf_id);
    }

    public function getBook() {
        return Book::find($this->book_id);
    }
}

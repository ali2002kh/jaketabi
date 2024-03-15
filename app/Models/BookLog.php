<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'want_to_read',
        'reading',
        'already_read',
    ];

    public function getBook() {
        return Book::find($this->book_id);
    }
}

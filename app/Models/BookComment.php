<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'message',
    ];

    public function getUser() {
        return User::find($this->user_id);
    }

    public function getBook() {
        return Book::find($this->book_id);
    }
}

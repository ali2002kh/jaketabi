<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'current_page',
        'status',
        'last_read_at',
        'started_at',
        'finished_at',
    ];

    public function getUser() {
        return User::find($this->user_id);
    }

    public function getBook() {
        return Book::find($this->book_id);
    }

    public function getProgression() {

        return $this->current_page / $this->getBook()->page_count;
    }

    
}

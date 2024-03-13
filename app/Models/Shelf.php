<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'user_id',
    ];

    public function getUser() {

        return User::find($this->user_id);
    }

    public function getBooks() {
        
        $books = array();
        $tmps = ShelfBook::all()->where('shelf_id', $this->id);

        foreach($tmps as $tmp) {
            $b = Book::find($tmp->book_id);
            if ($b->isActive()) {
                $books[] = $b;
            }
        }

        return $books;
    }
}


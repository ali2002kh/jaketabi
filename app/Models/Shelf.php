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
        
        $books = collect();
        $records = ShelfBook::where('shelf_id', $this->id)->get();

        foreach($records as $r) {
            $b = $r->getBook();
            if ($b->isActive()) {
                $books->add($b);
            }
        }

        return $books;
    }

    public function getPreviewBooks() {

        $books = collect();
        $records = ShelfBook::where('shelf_id', $this->id)->take(3)->get();

        foreach($records as $r) {
            $b = $r->getBook();
            if ($b->isActive()) {
                $books->add($b);
            }
        }

        return $books;
    }
}


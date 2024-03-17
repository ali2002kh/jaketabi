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

    public function updateShelf($name=null, $description=null) {

        if ($name) {
            $this->name = $name;
        }
        if ($description) {
            $this->description = $description;
        }
        $this->save();
    }

    public function addBook($book_id) {

        if (ShelfBook::where(['shelf_id' => $this->id, 'book_id' => $book_id])->count()) {
            return 1;
        }

        $record = new ShelfBook([
            'book_id' => $book_id,
            'shelf_id' => $this->id
        ]);

        $record->save();
    }

    public function removeBook($book_id) {

        $record = ShelfBook::where(['shelf_id' => $this->id, 'book_id' => $book_id]);
        if ($record) {
            $record->delete();
        }
    }

}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id'
    ];

    public function getParent() {

        return BookCategory::find($this->parent_id);
    }

    public function getSiblings() {

        if ($this->parent_id) {
            $categories = BookCategory::
            where('parent_id', $this->parent_id)
            ->where('id', '!=', $this->id)
            ->get();

            return $categories;
        } else {
            return collect();
        }
    }

    public function getChildren() {

        $categories = BookCategory::
        where('parent_id', $this->id)
        ->get();

        return $categories;
    }

    public function getBooks() {

        $books = Book::
        where('category_id', $this->id)
        ->where('status', 1)
        ->get();

        return $books;
    }

    public function getPreviewBooks() {

        $books = Book::
        where('category_id', $this->id)
        ->where('status', 1)
        ->take(5)
        ->get();

        return $books;
    }

    public function isLeaf() {
        return $this->getChildren()->count() == 0;
    }

}

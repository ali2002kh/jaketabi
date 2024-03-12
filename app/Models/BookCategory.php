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

        $categories = BookCategory::
        where('parent_id', $this->parent_id)
        ->where('id', '!=', $this->id)
        ->get();

        return $categories;
    }

    public function getChildren() {

        $categories = BookCategory::
        where('parent_id', $this->id)
        ->get();

        return $categories;
    }

}

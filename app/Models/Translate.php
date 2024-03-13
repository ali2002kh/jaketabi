<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translate extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_id',
        'translated_id',
    ];

    public function getOriginalBook() {

        return Book::find($this->original_id);
    }

    public function getTranslatedBook() {
        return Book::find($this->translated_id);
    }

}

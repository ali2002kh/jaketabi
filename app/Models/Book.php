<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'ISBN',
        'name',
        'image',
        'author',
        'category_id',
        'release_date',
        'publisher_id',
        'description',
        'translator',
        'page_count',
        'status',
        'LCC',
        'DDC',
        'ISBN_period',
    ];

    public function getCategory() {

        return BookCategory::find($this->category_id);
    }

    public function getPublisher() {

        return Publisher::find($this->publisher_id);
    }

    public function isTranslated() {

        return Translate::all()->where('translated_id', $this->id)->count();
    }

    public function isActive() {

        if ($this->status == 1) {
            return true;
        }
        return false;
    }

    public function getGenres() {
        
        $genres = collect();
        $records = GenreBook::where('book_id', $this->id)->get();

        foreach($records as $r) {
            
            $genres->add(Genre::find($r->genre_id));
        }

        return $genres;
        
    }
}

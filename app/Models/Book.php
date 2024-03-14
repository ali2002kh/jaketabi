<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
            
            $genres->add($r->getGenre());
        }

        return $genres;
        
    }

    public function getRelatedBooks() {

        $books = collect();
        $index = [];
    
        $genres = $this->getGenres();
        $category = $this->getCategory();
    
        foreach ($genres as $g) {
    
            foreach ($g->getBooks() as $b) {

                if ($b->id != $this->id) {
                    $books->add($b);
                    $index[$b->id] = isset($index[$b->id]) ? $index[$b->id] + 1 : 1;
                }
            }
        }
    
        foreach ($category->getBooks() as $b) {
    
            if ($b->id != $this->id) {
                $books->add($b);
                $index[$b->id] = isset($index[$b->id]) ? $index[$b->id] + 2 : 2;
            }
        }
    
        $groupedBooks = $books->groupBy('id');
        $sortedBooks = $groupedBooks->map(function (Collection $books, $key) use ($index) {
            return ['count' => $index[$key], 'book' => $books->first()];
        })->sortByDesc('count');
    
    
        $books = collect();
        foreach ($sortedBooks as $sb) {
            $books->add($sb['book']);
        }

        return $books;
    }
    
}

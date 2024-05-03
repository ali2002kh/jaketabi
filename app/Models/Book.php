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
        $sibling_categories = $category->getSiblings();
        $publisher = $this->getPublisher();
    
        foreach ($genres as $g) {
    
            foreach ($g->getBooks() as $b) {

                if ($b->id != $this->id) {
                    $books->add($b);
                    $index[$b->id] = isset($index[$b->id]) ? $index[$b->id] + 2 : 2;
                }
            }
        }
    
        foreach ($category->getBooks() as $b) {
    
            if ($b->id != $this->id) {
                $books->add($b);
                $index[$b->id] = isset($index[$b->id]) ? $index[$b->id] + 3 : 3;
            }
        }

        foreach ($sibling_categories as $category) {
    
            foreach ($category->getBooks() as $b) {
    
                if ($b->id != $this->id) {
                    $books->add($b);
                    $index[$b->id] = isset($index[$b->id]) ? $index[$b->id] + 1 : 1;
                }
            }
        }

        foreach ($publisher->getBooks() as $b) {
    
            if ($b->id != $this->id) {
                $books->add($b);
                $index[$b->id] = isset($index[$b->id]) ? $index[$b->id] + 1 : 1;
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

    public function getLog() {

        return BookLog::where('book_id', $this->id)->get()->first();
    }
    
    public function getWantToReadCount() {
        if ($this->getLog()) {
            return $this->getLog()->want_to_read;
        } 
        return 0;
    }

    public function getReadingCount() {
        
        if ($this->getLog()) {
            return $this->getLog()->reading;
        } 
        return 0;
    }

    public function getAlreadyReadCount() {
        
        if ($this->getLog()) {
            return $this->getLog()->already_read;
        } 
        return 0;
    }

    public function updateLog($field, $change) {

        $log = $this->getLog();

        if ($change == 'inc') {
            if ($field == 1) {
                $log->want_to_read++;
            } else if ($field == 2) {
                $log->reading++;
            } else {
                $log->already_read++;
            }
        } else if ($change == 'dec') {
            if ($field == 1) {
                $log->want_to_read--;
            } else if ($field == 2) {
                $log->reading--;
            } else {
                $log->already_read--;
            }
        }

        $log->save();
    }

    public function getComments() {

        return BookComment::where('book_id', $this->id)->get();
    }
}

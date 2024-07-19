<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Concerns\HasEvents;

class Book extends Model
{
    use HasFactory, HasEvents;

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

    public function getGenreBooks() {

        return GenreBook::where('book_id', $this->id)->get();
    }

    // public function getRelatedBooks() {

    //     $books = collect();
    //     $index = [];
    
    //     $genres = $this->getGenres();
    //     $category = $this->getCategory();
    //     $sibling_categories = $category->getSiblings();
    //     $publisher = $this->getPublisher();
    
    //     foreach ($genres as $g) {
    
    //         foreach ($g->getBooks() as $b) {

    //             if ($b->id != $this->id) {
    //                 $books->add($b);
    //                 $index[$b->id] = isset($index[$b->id]) ? $index[$b->id] + 2 : 2;
    //             }
    //         }
    //     }
    
    //     foreach ($category->getBooks() as $b) {
    
    //         if ($b->id != $this->id) {
    //             $books->add($b);
    //             $index[$b->id] = isset($index[$b->id]) ? $index[$b->id] + 3 : 3;
    //         }
    //     }

    //     foreach ($sibling_categories as $category) {
    
    //         foreach ($category->getBooks() as $b) {
    
    //             if ($b->id != $this->id) {
    //                 $books->add($b);
    //                 $index[$b->id] = isset($index[$b->id]) ? $index[$b->id] + 1 : 1;
    //             }
    //         }
    //     }

    //     foreach ($publisher->getBooks() as $b) {
    
    //         if ($b->id != $this->id) {
    //             $books->add($b);
    //             $index[$b->id] = isset($index[$b->id]) ? $index[$b->id] + 1 : 1;
    //         }
    //     }
    
    //     $groupedBooks = $books->groupBy('id');
    //     $sortedBooks = $groupedBooks->map(function (Collection $books, $key) use ($index) {
    //         return ['count' => $index[$key], 'book' => $books->first()];
    //     })->sortByDesc('count');
    
    
    //     $books = collect();
    //     foreach ($sortedBooks as $sb) {
    //         $books->add($sb['book']);
    //     }

    //     return $books;
    // }

    // public function getRelatedBooks() {

    //     $genres = $this->getGenres()->pluck('id')->all();
    //     $category = $this->getCategory();
    //     $sibling_categories = $category->getSiblings()->pluck('id')->all();
    //     $publisher = $this->getPublisher()->id;

    //     $genreQuery = Book::select('books.id', 'books.name', 'books.image')
    //         ->join('genre_books', 'books.id', '=', 'genre_books.book_id')
    //         ->whereNotIn('books.id', [$this->id])
    //         ->whereIn('genre_books.genre_id', $genres)
    //         ->selectRaw('
    //             (COUNT(DISTINCT genre_books.genre_id) * 2) as genre_relevance')
    //         ->groupBy('books.id', 'books.name', 'books.image')
    //         ->orderBy('genre_relevance', 'desc')
    //         ->get()
    //         ;

    //     $categoryPublisherQuery = Book::select('books.id', 'books.name', 'books.image')
    //         ->whereNotIn('books.id', [$this->id])
    //         ->where(function ($query) use ($category, $sibling_categories, $publisher) {
    //             $query->where('books.category_id', $category->id)
    //                 ->orWhereIn('books.category_id', $sibling_categories)
    //                 ->orWhere('books.publisher_id', $publisher);
    //         })
    //         ->selectRaw('
    //             (CASE 
    //                 WHEN books.category_id = '. $category->id. ' THEN 3 
    //                 WHEN books.category_id IN ('. implode(',', $sibling_categories). ') THEN 1 
    //                 WHEN books.publisher_id = '. $publisher. ' THEN 1 
    //             END) as category_publisher_relevance')
    //         ->orderBy('category_publisher_relevance', 'desc')
    //         ->get()
    //         ;

    //     $results = $genreQuery->merge($categoryPublisherQuery);
    //     $results->transform(function($book) {
    //         $book->relevance = $book->genre_relevance + $book->category_publisher_relevance;
    //         return $book;
    //     });

    //     return $results->sortByDesc('relevance')->take(20);
    // }

    public function relevantTo($book_id) {
        $genres = $this->getGenres()->pluck('id')->all();
        $category = $this->getCategory();
        $sibling_categories = $category->getSiblings()->pluck('id')->all();
        $publisher = $this->getPublisher()->id;
    
        $genreQuery = Book::select('books.id')
            ->leftJoin('genre_books', 'books.id', '=', 'genre_books.book_id')
            ->whereIn('books.id', [$book_id])
            ->whereIn('genre_books.genre_id', $genres)
            ->selectRaw('
                COALESCE(COUNT(DISTINCT genre_books.genre_id), 0) * 2 as genre_relevance')
            ->groupBy('books.id')
            ->get()
            ;
    
        $categoryPublisherQuery = Book::select('books.id')
            ->whereIn('books.id', [$book_id])
            ->selectRaw('
                (CASE 
                    WHEN books.category_id = '. $category->id. ' THEN 3 
                    WHEN books.category_id IN ('. implode(',', $sibling_categories). ') THEN 1 
                    WHEN books.publisher_id = '. $publisher. ' THEN 1 
                    ELSE 0
                END) as category_publisher_relevance')
            ->get()
            ;
    
        $results = $genreQuery->union($categoryPublisherQuery);
        $results->transform(function($book) {
            $book->relevance = isset($book->genre_relevance)? $book->genre_relevance : 0;
            $book->relevance += isset($book->category_publisher_relevance)? $book->category_publisher_relevance : 0;
            return $book;
        });
    
        return $results->first()->relevance;
    }

    public function setRelatedBooks() {

        $genres = $this->getGenres()->pluck('id')->all();
        $category = $this->getCategory();
        $sibling_categories = $category->getSiblings()->pluck('id')->all();
        $publisher = $this->getPublisher()->id;

        $genreQuery = Book::select('books.id')
            ->join('genre_books', 'books.id', '=', 'genre_books.book_id')
            ->whereNotIn('books.id', [$this->id])
            ->whereIn('genre_books.genre_id', $genres)
            ->selectRaw('
                (COUNT(DISTINCT genre_books.genre_id) * 2) as genre_relevance')
            ->groupBy('books.id')
            ->orderBy('genre_relevance', 'desc')
            ->get()
            ;

        $categoryPublisherQuery = Book::select('books.id')
            ->whereNotIn('books.id', [$this->id])
            ->where(function ($query) use ($category, $sibling_categories, $publisher) {
                $query->where('books.category_id', $category->id)
                    ->orWhereIn('books.category_id', $sibling_categories)
                    ->orWhere('books.publisher_id', $publisher);
            })
            ->selectRaw('
                (CASE 
                    WHEN books.category_id = '. $category->id. ' THEN 3 
                    WHEN books.category_id IN ('. implode(',', $sibling_categories). ') THEN 1 
                    WHEN books.publisher_id = '. $publisher. ' THEN 1 
                END) as category_publisher_relevance')
            ->orderBy('category_publisher_relevance', 'desc')
            ->get()
            ;

        $results = $genreQuery->merge($categoryPublisherQuery);
        $results->transform(function($book) {
            $book->relevance = $book->genre_relevance + $book->category_publisher_relevance;
            return $book;
        });

        $results = $results->sortByDesc('relevance')->take(20);
        $related_books_id = "";
        foreach($results as $b) {
            $related_books_id.=$b->id.",";
        }
        $related_books_id = substr($related_books_id, 0, -1);

        $relationRecord = BookRelation::where('book_id', $this->id)->first();
        if (!$relationRecord) {
            $relationRecord = new BookRelation();
            $relationRecord->book_id = $this->id;
        }
        $relationRecord->related_books_id = $related_books_id;
        $relationRecord->save();

    }

    public function getRelatedBooks() {

        $relationRecord = BookRelation::where('book_id', $this->id)->first();
        return $relationRecord->geRelatedBooks();
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

    public function remove() {
        
        // $this->getLog()->delete();
        $this->status = 0;
        $this->save();
        abort(200);
    }

    public function updateBook (
        $isbn = null, 
        $name = null,
        $image = null,
        $author = null,
        $category_id = null,
        $release_date = null,
        $publisher_id = null,
        $description = null,
        $translator = null,
        $page_count = null,
        $lcc = null,
        $ddc = null,
        $isbn_period = null,
    ) {

        if ($isbn) {
            $this->ISBN = $isbn;
        }

        if ($name) {
            $this->name = $name;
        }

        if ($image) {
            $this->image = $image;
        }

        if ($author) {
            $this->author = $author;
        }

        if ($category_id) {
            $this->category_id = $category_id;
        }

        if ($release_date) {
            $this->release_date = $release_date;
        }

        if ($publisher_id) {
            $this->publisher_id = $publisher_id;
        }

        if ($description) {
            $this->description = $description;
        }

        if ($translator) {
            $this->translator = $translator;
        }

        if ($page_count) {
            $this->page_count = $page_count;
        }

        if ($lcc) {
            $this->LCC = $lcc;
        }
        if ($ddc) {
            $this->DDC = $ddc;
        }
        if ($isbn_period) {
            $this->ISBN_period = $isbn_period;
        }

        $this->save();

    }
}



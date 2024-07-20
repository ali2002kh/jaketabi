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
        'updated_at',
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

    public function relevantTo($book_id) {
        $genres = $this->getGenres()->pluck('id')->all();
        $category = $this->getCategory();
        $sibling_categories = $category->getSiblings()->pluck('id')->all();
        $publisher = $this->getPublisher()->id;

        // dd($sibling_categories);

        $genreQuery = Book::select('books.id')
            ->join('genre_books', 'books.id', '=', 'genre_books.book_id')
            ->whereIn('books.id', [$book_id])
            ->whereIn('genre_books.genre_id', $genres)
            ->selectRaw('
                (COUNT(DISTINCT genre_books.genre_id) * 2) as genre_relevance')
            ->groupBy('books.id')
            ->orderBy('genre_relevance', 'desc')
            ->get()
            ;

        if ($sibling_categories) {
            $categoryPublisherQuery = Book::select('books.id')
            ->whereIn('books.id', [$book_id])
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
        } else {
            $categoryPublisherQuery = Book::select('books.id')
            ->whereIn('books.id', [$book_id])
            ->where(function ($query) use ($category, $publisher) {
                $query->where('books.category_id', $category->id)
                    ->orWhere('books.publisher_id', $publisher);
            })
            ->selectRaw('
                (CASE 
                    WHEN books.category_id = '. $category->id. ' THEN 3  
                    WHEN books.publisher_id = '. $publisher. ' THEN 1 
                END) as category_publisher_relevance')
            ->orderBy('category_publisher_relevance', 'desc')
            ->get()
            ;
        }
        

        // dd($genreQuery);
        // dd($categoryPublisherQuery);

        $results = $genreQuery->merge($categoryPublisherQuery);
        $results->transform(function($book) use ($genreQuery, $categoryPublisherQuery) {
            $genreBook = $genreQuery->firstWhere('id', $book->id);
            $categoryPublisherBook = $categoryPublisherQuery->firstWhere('id', $book->id);
            $genreRelevance = $genreBook ? $genreBook->genre_relevance : 0;
            $categoryPublisherRelevance = $categoryPublisherBook ? $categoryPublisherBook->category_publisher_relevance : 0;
            $book->relevance = $genreRelevance + $categoryPublisherRelevance;
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

            if ($sibling_categories) {
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
            } else {
                $categoryPublisherQuery = Book::select('books.id')
                ->whereNotIn('books.id', [$this->id])
                ->where(function ($query) use ($category, $publisher) {
                    $query->where('books.category_id', $category->id)
                        ->orWhere('books.publisher_id', $publisher);
                })
                ->selectRaw('
                    (CASE 
                        WHEN books.category_id = '. $category->id. ' THEN 3  
                        WHEN books.publisher_id = '. $publisher. ' THEN 1 
                    END) as category_publisher_relevance')
                ->orderBy('category_publisher_relevance', 'desc')
                ->get()
                ;
            }

        $results = $genreQuery->merge($categoryPublisherQuery);
        $results->transform(function($book) use ($genreQuery, $categoryPublisherQuery) {
            $genreBook = $genreQuery->firstWhere('id', $book->id);
            $categoryPublisherBook = $categoryPublisherQuery->firstWhere('id', $book->id);
            $genreRelevance = $genreBook ? $genreBook->genre_relevance : 0;
            $categoryPublisherRelevance = $categoryPublisherBook ? $categoryPublisherBook->category_publisher_relevance : 0;
            $book->relevance = $genreRelevance + $categoryPublisherRelevance;
            return $book;
        });

        $all = $results->sortByDesc('relevance');

        $relatedBooks = [];
        foreach ($all->take(20) as $book) {
            $relatedBooks[$book->id] = $book->relevance;
        }

        $relationRecord = BookRelation::where('book_id', $this->id)->first();
        if (!$relationRecord) {
            $relationRecord = new BookRelation();
            $relationRecord->book_id = $this->id;
        }
        $relationRecord->related_books = json_encode($relatedBooks);
        $relationRecord->save();

        return $all;
    }

    public function getRelatedBooks() {

        $relationRecord = BookRelation::where('book_id', $this->id)->first();
        return $relationRecord->getRelatedBooks();
    }

    public function getRelatedBookIdsWithRelevance() {
        return BookRelation::where('book_id', $this->id)->first()->related_books;
    }

    public function getBookRelation() {

        return BookRelation::where('book_id', $this->id)->first();
    }

    public function updateBookRelations() {

        $allRelatedBooks = $this->setRelatedBooks();
    
        if ($allRelatedBooks->count() > 100) {
            $allRelatedBooks = $allRelatedBooks->take(100);
        }
        
        foreach ($allRelatedBooks as $item) {
            $book = Book::find($item->id);
            $relatedBooks = json_decode($book->getRelatedBookIdsWithRelevance(), true);
    
            $relatedBooks[$this->id] = $item->relevance;
    
            uasort($relatedBooks, function($a, $b) {
                return $b <=> $a;
            });
    
            $relatedBooks = array_slice($relatedBooks, 0, 20, true);
            $relatedBooks = array_combine(array_keys($relatedBooks), array_values($relatedBooks));
    
            $relation = $book->getBookRelation();
            $relation->related_books = json_encode($relatedBooks);
            $relation->save();
        }
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



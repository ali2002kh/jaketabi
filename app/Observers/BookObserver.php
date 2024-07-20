<?php

namespace App\Observers;

use App\Models\Book;

class BookObserver {

    /**
     * Handle the Book "created" event.
     */
    public function created(Book $newBook): void {
        
    }

    /**
     * Handle the Book "updated" event.
     */
    public function updated(Book $newBook): void {

        if ($newBook->ISBN != 'initial') {
            
            $allRelatedBooks = $newBook->setRelatedBooks();
    
            if ($allRelatedBooks->count() > 100) {
                $allRelatedBooks = $allRelatedBooks->take(100);
            }
            
            foreach ($allRelatedBooks as $item) {
                $book = Book::find($item->id);
                $relatedBooks = json_decode($book->getRelatedBookIdsWithRelevance(), true);
                $newRelevance = $item->relevance;
                $inserted = false;
        
                foreach ($relatedBooks as $key => $relevance) {
                    if ($key == $newBook->id) {
                        $inserted = true;
                        break;
                    }
                    if ($newRelevance > $relevance) {
                        $relatedBooks[$newBook->id] = $newRelevance;
                        uasort($relatedBooks, function($a, $b) {
                            return $b <=> $a;
                        });
                        $inserted = true;
                        break;
                    }
                }
                
                if (!$inserted && count($relatedBooks) < 20) {
                    $relatedBooks[$newBook->id] = $newRelevance;
                    uasort($relatedBooks, function($a, $b) {
                        return $b <=> $a;
                    });
                }
                
                if (count($relatedBooks) > 20) {
                    $relatedBooks = array_slice($relatedBooks, 0, 20, true);
                }
                
                $relation = $book->getBookRelation();
                $relation->related_books = json_encode($relatedBooks);
                $relation->save();
            }
        }
    }

    /**
     * Handle the Book "deleted" event.
     */
    public function deleted(Book $book): void
    {
        //
    }

    /**
     * Handle the Book "restored" event.
     */
    public function restored(Book $book): void
    {
        //
    }

    /**
     * Handle the Book "force deleted" event.
     */
    public function forceDeleted(Book $book): void
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookCategoryPreviewResource;
use App\Http\Resources\BookCategoryResource;
use App\Http\Resources\BookCommentResource;
use App\Http\Resources\BookPreviewResource;
use App\Http\Resources\BookPublicResource;
use App\Http\Resources\FriendBookResource;
use App\Http\Resources\GenreResource;
use App\Http\Resources\PublisherResource;
use App\Http\Resources\UserBookResource;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Http\Request;

class BookController extends Controller {
    
    public function show($id) {

        return new BookPublicResource(Book::find($id));
    }

    public function record($user_id, $book_id) {

        $user = User::find($user_id);
        $record = $user->getBookRecord($book_id);

        return new UserBookResource($record);
    }

    public function friend($book_id) {
        
        return new FriendBookResource(Book::find($book_id));
    }

    public function updateStatus($book_id, $status) {

        /** @var User $user */ 
        $user = auth()->user();
        $user->updateBookStatus($book_id, $status);

        return abort(200);
    }

    public function updateCurrentPage($book_id, $page) {

        /** @var User $user */ 
        $user = auth()->user();
        $user->updateCurrentPage($book_id, $page);

        return abort(200);
    }

    public function category($id, $page) {

        $bookCategory = BookCategory::find($id);
        $pageSize = 7;

        if ($page == 0) {
            return response()->json([
                'bookCategory' => new BookCategoryResource($bookCategory),
                'pageSize' => $pageSize,
            ]);
        }

        $books = $bookCategory->getBooks()->slice(($page - 1) * $pageSize)->take($pageSize);

        return BookPreviewResource::collection($books);
    }

    public function categoriesAndGenres() {

        $allCategories = BookCategory::all();
        $categories = collect();
        foreach ($allCategories as $c) {
            if ($c->isLeaf()) {
                $categories->add($c);
            }
        }

        return response()->json([
            'data' => [
                'categories' => BookCategoryPreviewResource::collection($categories),
                'genres' => GenreResource::collection(Genre::all()),
            ]
        ]);
        
    }

    public function genre($id, $page) {

        $genre = Genre::find($id);
        $pageSize = 8;

        if ($page == 0) {
            return response()->json([
                'genre' => new GenreResource($genre),
                'pageSize' => $pageSize,
            ]);
        }

        $books = $genre->getBooks()->slice(($page - 1) * $pageSize)->take($pageSize);

        return BookPreviewResource::collection($books);
    }

    public function publisher($id, $page) {

        $publisher = Publisher::find($id);
        $pageSize = 8;

        if ($page == 0) {
            return response()->json([
                'publisher' => new PublisherResource($publisher),
                'pageSize' => $pageSize,
            ]);
        }

        $books = $publisher->getBooks()->slice(($page - 1) * $pageSize)->take($pageSize);

        return BookPreviewResource::collection($books);

    }

    public function publishers() {

        return PublisherResource::collection(Publisher::all());
    }

    public function addComment($book_id, Request $request) {

        /** @var User $user */ 
        $user = auth()->user();

        $this->validate($request, [
            'commentMessage' => 'required',
        ]); 

        $comment = $user->addComment($book_id, $request->get('commentMessage'));

        return response()->json([
            'data' => [
                'comment' => new BookCommentResource($comment),
            ],
            'message' => 'نظر ارسال شد'
        ], 200);
    }

    public function comments($book_id) {

        $book = Book::find($book_id);

        return BookCommentResource::collection($book->getComments());
    }

    public function relevance($book_id1, $book_id2) {

        $book = Book::find($book_id1);
        dd ($book->relevantTo($book_id2));
    }

    public function refreshRelevance() {
        foreach(Book::all() as $b) {
            $b->setRelatedBooks();
        }
    }
}

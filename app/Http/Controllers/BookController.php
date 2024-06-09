<?php

namespace App\Http\Controllers;

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

        $user = auth()->user();
        $user->updateBookStatus($book_id, $status);

        return abort(200);
    }

    public function updateCurrentPage($book_id, $page) {

        $user = auth()->user();
        $user->updateCurrentPage($book_id, $page);

        return abort(200);
    }

    public function category($id) {

        $bookCategory = BookCategory::find($id);

        return response()->json([
            'data' => [
                'books' => BookPreviewResource::collection($bookCategory->getBooks()),
                'bookCategory' => new BookCategoryResource($bookCategory),
            ]
        ]);
    }

    public function genre($id) {

        $genre = Genre::find($id);

        return response()->json([
            'data' => [
                'books' => BookPreviewResource::collection($genre->getBooks()),
                'genre' => new GenreResource($genre),
            ]
        ]);
    }

    public function publisher($id) {

        $publisher = Publisher::find($id);

        return response()->json([
            'data' => [
                'books' => BookPreviewResource::collection($publisher->getBooks()),
                'publisher' => new PublisherResource($publisher),
            ]
        ]);
    }

    public function addComment($book_id, Request $request) {

        $user = auth()->user();

        $this->validate($request, [
            'message' => 'required',
        ]); 

        $user->addComment($book_id, $request->get('message'));
    }

    public function comments($book_id) {

        $book = Book::find($book_id);

        return BookCommentResource::collection($book->getComments());
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookCategoryResource;
use App\Http\Resources\BookPublicResource;
use App\Http\Resources\GenreResource;
use App\Http\Resources\UserBookResource;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function show($id) {

        return new BookPublicResource(Book::find($id));
    }

    public function record($user_id, $book_id) {

        $user = User::find($user_id);
        $record = $user->getBookRecord($book_id);

        return new UserBookResource($record);
    }

    public function updateStatus($book_id, $status) {

        $user = User::find(1);
        $user->updateBookStatus($book_id, $status);

        return abort(200);
    }

    public function category($id) {

        return new BookCategoryResource(BookCategory::find($id));
    }

    public function genre($id) {

        return new GenreResource(Genre::find($id));
    }
}

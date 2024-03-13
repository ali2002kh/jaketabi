<?php

use App\Models\Book;
use App\Models\BookCategory;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // $user = User::find(1)->getShelves()->first()->getUser()->getProfile()->getUser();
    // dd($user);

    // $category = BookCategory::find(1)->getChildren();
    // dd($category);

    // $genre = Book::find(1)->getGenres()->first();
    // $book = $genre->getBooks();
    // dd($book);

    // $user = User::find(1)->getFriendRequests()->first();
    // dd($user);

    $user = User::find(1)->getWantToReadBooks();
    dd($user);
});

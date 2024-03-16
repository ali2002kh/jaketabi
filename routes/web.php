<?php

use App\Models\Book;
use App\Models\BookCategory;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $user = User::find(1);
    $book = Book::find(1);
    $other = User::find(2);

    // dd($user->getShelves()->first()->getPreviewBooks());
    

    // $category = BookCategory::find(1)->getChildren();
    // dd($category);

    // $genre = Book::find(1)->getGenres()->first();
    // $book = $genre->getBooks();
    // dd($book);

    // $user = User::find(1)->hasFriendRequest();
    // dd($user);

    // $user = User::find(1)->getWantToReadBooks();
    // dd($user);

    // dd(User::find(1)->getBookRecord(1)->getProgression());

    // dd(Book::find(1)->getRelatedBooks());

    // dd(User::find(1)->getFriendsBooks());

    // dd($user->getFriendsWhoAreReadingThisBook(1, $preview=true));

    // $book->updateLog(2);

    // $book->IncrementWantToRead();
    // dd($book->getWantToReadCount());

    // dd($user->getTrendingBooks());

    // $other->sendRequestTo($user->id);
    // $user->acceptFriendRequest($other->id);
    // $user->rejectOrRemoveFriend(2);

    // $user->updateBookStatus(2, 3);

    $user->updateCurrentPage(2, 20);
});

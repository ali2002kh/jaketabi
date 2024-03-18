<?php

use App\Http\Resources\BookCategoryResource;
use App\Http\Resources\GenreResource;
use App\Http\Resources\PublisherResource;
use App\Http\Resources\UserPrivateResource;
use App\Http\Resources\UserPublicResource;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\Shelf;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $user = User::find(1);
    $book = Book::find(1);
    $other = User::find(2);
    $shelf = Shelf::find(5);

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

    // dd($user->getFriendsWhoAreReadingThisBook(1, preview:true));

    // $book->updateLog(2);

    // $book->IncrementWantToRead();
    // dd($book->getWantToReadCount());

    // dd($user->getTrendingBooks());

    // $other->sendRequestTo($user->id);
    // $user->acceptFriendRequest($other->id);
    // $user->rejectOrRemoveFriend(2);

    // $user->updateBookStatus(2, 3);

    // $user->updateCurrentPage(2, 20);

    // $user->updateProfile(first_name:"ali");
    // $user->updateUser(username:"hadith", password:"1");

    // $user->createShelf(name:"my shelf", description:"this is my description");

    // $shelf->updateShelf(description:"this is my new new description");

    // $shelf->addBook(1);
    // $shelf->removeBook(1);

    // $user->addComment(1, "This is my new comment");

    return new UserPrivateResource($user);

    // return new BookCategoryResource(BookCategory::find(2));
    
    // return new PublisherResource(Publisher::find(1));

    // return new GenreResource(Genre::find(3));
});
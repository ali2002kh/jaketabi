<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShelfController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// authentication ------------------------------------------------------

Route::post('/signup', [AuthController::class, 'signup']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);


// Home ----------------------------------------------------------------

Route::get('user', [HomeController::class, 'self']);

Route::get('user/{id}', [HomeController::class, 'user']);

Route::get('popular', [HomeController::class, 'popular']);

Route::get('trending', [HomeController::class, 'trending']);

Route::get('friends-activities', [HomeController::class, 'friendsActivities']);

Route::get('friends-shelves', [HomeController::class, 'friendsShelves']);

// Search -------------------------------------------------------------------

Route::post('search', [SearchController::class, 'search']);

// Profile  ---------------------------------------------------------------

Route::post('update-profile', [ProfileController::class, 'update']);

// Shelves ----------------------------------------------------------------

Route::get('shelf/{id}', [ShelfController::class, 'show']);

Route::post('/store-shelf', [ShelfController::class, 'storeShelf']);

Route::get('/add-book-to-shelf/{shelf_id}/{book_id}', [ShelfController::class, 'addBook']);

Route::delete('/remove-book-from-shelf/{shelf_id}/{book_id}', [ShelfController::class, 'removeBook']);

Route::post('/update-shelf-name/{shelf_id}', [ShelfController::class, 'updateName']);

Route::post('/update-shelf-description/{shelf_id}', [ShelfController::class, 'updateDescription']);

Route::delete('/remove-shelf/{shelf_id}', [ShelfController::class, 'delete']);

// Friends ----------------------------------------------------------------

Route::get('/accept-or-add-friend/{id}', [FriendshipController::class, 'acceptOrAdd']);

Route::get('/reject-or-remove-friend/{id}', [FriendshipController::class, 'rejectOrRemove']);

Route::get('/friendship-status/{id}', [FriendshipController::class, 'status']);

// Books -------------------------------------------------------------------

Route::get('book/{id}', [BookController::class, 'show']);

Route::get('book-record/{user_id}/{book_id}', [BookController::class, 'record']);

Route::get('update-book-status/{book_id}/{status}', [BookController::class, 'updateStatus']);

Route::get('update-book-current-page/{book_id}/{page}', [BookController::class, 'updateCurrentPage']);

Route::get('category/{id}', [BookController::class, 'category']);

Route::get('genre/{id}', [BookController::class, 'genre']);

Route::get('book-comments/{book_id}', [BookController::class, 'comments']);

Route::post('add-book-comment/{book_id}', [BookController::class, 'addComment']);
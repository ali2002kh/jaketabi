<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShelfController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Home ----------------------------------------------------------------

Route::get('user', [HomeController::class, 'self']);

Route::get('user/{id}', [HomeController::class, 'user']);

Route::get('popular', [HomeController::class, 'popular']);

Route::get('trending', [HomeController::class, 'trending']);

Route::get('book/{id}', [BookController::class, 'show']);

Route::get('friends-activities', [HomeController::class, 'friendsActivities']);

Route::get('friends-shelves', [HomeController::class, 'friendsShelves']);

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
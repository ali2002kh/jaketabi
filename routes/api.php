<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShelfController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// authentication ------------------------------------------------------

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/signup', [AuthController::class, 'signup']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);


// Home ----------------------------------------------------------------

Route::get('self', [HomeController::class, 'self']);

Route::get('user/{id}', [HomeController::class, 'user']);

Route::get('trending-popular', [HomeController::class, 'trendingAndPopular']);

Route::get('friends-activities', [HomeController::class, 'friendsActivities']);

Route::get('friends-shelves', [HomeController::class, 'friendsShelves']);

// Search -------------------------------------------------------------------

Route::post('search', [SearchController::class, 'search']);

Route::post('search/user-friend', [SearchController::class, 'searchUserFriend']);

Route::post('search/user-role', [SearchController::class, 'searchUserRole']);

Route::post('search/book', [SearchController::class, 'searchBook']);

// Profile  ---------------------------------------------------------------

Route::post('update-profile', [ProfileController::class, 'update']);

Route::get('profile/books/{id}/{status}', [ProfileController::class, 'books']);

// Shelves ----------------------------------------------------------------

Route::get('shelf/{id}', [ShelfController::class, 'show']);

Route::post('/store-shelf', [ShelfController::class, 'storeShelf']);

Route::get('/add-book-to-shelf/{shelf_id}/{book_id}', [ShelfController::class, 'addBook']);

Route::get('/remove-book-from-shelf/{shelf_id}/{book_id}', [ShelfController::class, 'removeBook']);

Route::post('/update-shelf/{shelf_id}', [ShelfController::class, 'update']);

Route::get('/remove-shelf/{shelf_id}', [ShelfController::class, 'delete']);
 
Route::get('/shelves/{user_id}', [ShelfController::class, 'index']);

// Friends ----------------------------------------------------------------

Route::get('/accept-or-add-friend/{id}', [FriendshipController::class, 'acceptOrAdd']);

Route::get('/reject-or-remove-friend/{id}', [FriendshipController::class, 'rejectOrRemove']);

Route::get('/friendship/{id}', [FriendshipController::class, 'status']);

// Books -------------------------------------------------------------------

Route::get('book/{id}', [BookController::class, 'show']);

Route::get('book-record/{user_id}/{book_id}', [BookController::class, 'record']);

Route::get('book-friend/{book_id}', [BookController::class, 'friend']);

Route::get('update-book-status/{book_id}/{status}', [BookController::class, 'updateStatus']);

Route::get('update-book-current-page/{book_id}/{page}', [BookController::class, 'updateCurrentPage']);

Route::get('book/category/{id}/{page}', [BookController::class, 'category']);

Route::get('book-categories-genres', [BookController::class, 'categoriesAndGenres']);

Route::get('genre/{id}/{page}', [BookController::class, 'genre']);

Route::get('publisher/{id}/{page}', [BookController::class, 'publisher']);

Route::get('publishers', [BookController::class, 'publishers']);

Route::get('book/comments/{book_id}', [BookController::class, 'comments']);

Route::post('add-book-comment/{book_id}', [BookController::class, 'addComment']);

// administration ----------------------------------------------------------------

Route::get('book/remove/{id}', [AdminController::class, 'removeBook']);

Route::post('store-book', [AdminController::class, 'storeBook']);

Route::post('update-book/{id}', [AdminController::class, 'updateBook']);

Route::get('admin/book/{id}', [AdminController::class, 'showBook']);

Route::get('promote-normal-user-to-publisher-admin/{user_id}/{publisher_id}', [AdminController::class, 'promoteNormalUserToPublisherAdmin']);

Route::get('demote-publisher-admin-to-normal-user/{user_id}/{publisher_id}', [AdminController::class, 'demotePublisherAdminToNormalUser']);

Route::get('promote-publisher-admin-to-publisher-super-admin/{user_id}/{publisher_id}', [AdminController::class, 'promotePublisherAdminToPublisherSuperAdmin']);

Route::get('publisher-admins/{publisher_id}', [AdminController::class, 'publisherAdmins']);

Route::get('admin/publishers', [AdminController::class, 'publishers']);

Route::get('demote-publisher-super-admin-to-normal-user/{user_id}', [AdminController::class, 'demotePublisherSuperAdminToNormalUser']);

Route::get('promote-normal-user-to-admin/{user_id}', [AdminController::class, 'promoteNormalUserToAdmin']);

Route::get('demote-admin-to-normal-user/{user_id}', [AdminController::class, 'demoteAdminToNormalUser']);

Route::get('promote-admin-to-super-admin/{user_id}', [AdminController::class, 'promoteAdminToSuperAdmin']);

Route::get('admins', [AdminController::class, 'admins']);

// test ----------------------------------------------------------------

Route::get('relevance/{id1}/{id2}', [BookController::class, 'relevance']);

Route::get('refresh-relevance', [BookController::class, 'refreshRelevance']);

Route::post('generate-otp', [AuthController::class, 'generateOtp']);

Route::post('register/{token}', [AuthController::class, 'register']);
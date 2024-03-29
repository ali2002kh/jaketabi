<?php

use App\Http\Controllers\BookController;
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

Route::get('user', [HomeController::class, 'user']);

Route::get('popular', [HomeController::class, 'popular']);

Route::get('trending', [HomeController::class, 'trending']);

Route::get('book/{id}', [BookController::class, 'show']);

Route::get('friends-activities', [HomeController::class, 'friendsActivities']);

Route::get('friends-shelves', [HomeController::class, 'friendsShelves']);

Route::get('shelf/{id}', [ShelfController::class, 'show']);

Route::post('/store-shelf', [ShelfController::class, 'storeShelf']);


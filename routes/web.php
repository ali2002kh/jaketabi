<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;

Route::get('/{any?}', function () {
    return view('welcome');
})->where('any', '^(?!api\/)[\/\w\.\,-]*');

// Route::get('/redis-test', function () {
//     Redis::set('test-key', 'Hello Redis!');
//     $value = Redis::get('test-key');
//     return "Redis test value: " . $value;
// });

// Route::post('/signup', [AuthController::class, 'signup'])->name('signup');

// Route::post('/login', [AuthController::class, 'login'])->name('login');

// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

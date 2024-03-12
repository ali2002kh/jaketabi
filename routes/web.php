<?php

use App\Models\BookCategory;
use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    $user = User::find(1)->getShelves()->first()->getUser()->getProfile()->getUser();
    dd($user);

    $category = BookCategory::find(1)->getChildren();
    dd($category);
});

<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookPublicResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function show($id) {

        return new BookPublicResource(Book::find($id));
    }
}

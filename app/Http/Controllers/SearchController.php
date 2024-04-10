<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookPreviewResource;
use App\Http\Resources\ShelfPreviewResource;
use App\Models\Book;
use App\Models\Shelf;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request) {

        $input = $request->get('input');

        $books = [];
        $shelves = [];

        if($input != '') {
            $books = Book::where('name','LIKE',"%".$input."%")->get();
            $shelves = Shelf::where('name','LIKE',"%".$input."%")->get();
        }
        return response()->json([
            'data' => [
                'books' => BookPreviewResource::collection($books),
                'shelves' => ShelfPreviewResource::collection($shelves),
            ]
        ]);
    }

}


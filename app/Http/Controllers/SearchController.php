<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookCategoryPreviewResource;
use App\Http\Resources\BookPreviewResource;
use App\Http\Resources\GenreResource;
use App\Http\Resources\PublisherResource;
use App\Http\Resources\ShelfPreviewResource;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\Shelf;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request) {

        $input = $request->get('input');

        $books = [];
        $shelves = [];
        $genres = [];
        $categories = [];
        $publishers = [];

        if($input != '') {
            $books = Book::where('name','LIKE',"%".$input."%")->get();
            $shelves = Shelf::where('name','LIKE',"%".$input."%")->get();
            $genres = Genre::where('name','LIKE',"%".$input."%")->get();
            $categories = BookCategory::where('name','LIKE',"%".$input."%")->get();
            $publishers = Publisher::where('name','LIKE',"%".$input."%")->get();
        }
        return response()->json([
            'data' => [
                'books' => BookPreviewResource::collection($books),
                'shelves' => ShelfPreviewResource::collection($shelves),
                'genres' => GenreResource::collection($genres),
                'categories' => BookCategoryPreviewResource::collection($categories),
                'publishers' => PublisherResource::collection($publishers),
            ]
        ]);
    }

}


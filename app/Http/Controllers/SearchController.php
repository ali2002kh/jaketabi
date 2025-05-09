<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookCategoryPreviewResource;
use App\Http\Resources\BookPreviewResource;
use App\Http\Resources\GenreResource;
use App\Http\Resources\PublisherResource;
use App\Http\Resources\ShelfPreviewResource;
use App\Http\Resources\UserFriendResource;
use App\Http\Resources\UserPreviewResource;
use App\Http\Resources\UserRoleResource;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\Shelf;
use App\Models\User;
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
            $books = Book::where('name', 'LIKE', "%".$input."%")
                ->orWhere('description', 'LIKE', "%".$input."%")
                ->orWhere('author', 'LIKE', "%".$input."%")
                ->orWhere('translator', 'LIKE', "%".$input."%")
                ->orWhere('LCC', 'LIKE', "%".$input."%")
                ->orWhere('DDC', 'LIKE', "%".$input."%")
                ->orWhere('ISBN_period', 'LIKE', "%".$input."%")
                ->where('status', 1)
                ->get();

            $shelves = Shelf::where('name', 'LIKE', "%".$input."%")
                ->orWhere('description',  'LIKE', "%".$input."%")
                ->get();
        
            $genres = Genre::where('name', 'LIKE', "%".$input."%")
                ->get();

            $categories = BookCategory::where('name', 'LIKE', "%".$input."%")
                ->get();

            $publishers = Publisher::where('name', 'LIKE', "%".$input."%")
                ->get();
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

    public function searchUserFriend(Request $request) {

        $input = $request->get('input');

        $users = [];

        if($input != '') {
            $users = User::where('username','LIKE',"%".$input."%")
            ->orWhere('email', $input)
            ->orWhere('number', $input)
            ->get();
        }
        return UserFriendResource::collection($users);
    }

    public function searchUserRole(Request $request) {

        $input = $request->get('input');

        $users = [];

        if($input != '') {
            $users = User::where('username','LIKE',"%".$input."%")
            ->orWhere('email','LIKE',"%".$input."%")
            ->orWhere('number','LIKE',"%".$input."%")
            ->get();
        }
        return UserRoleResource::collection($users);
    }

    public function searchBook(Request $request) {

        $input = $request->get('input');
        $publisher_id = null;

        if ($request->has('publisher_id')) {
            
            $publisher_id = $request->get('publisher_id');
        }

        $books = [];

        if($input != '') {
            $books = Book::where('name', 'LIKE', "%".$input."%")
                ->orWhere('description', 'LIKE', "%".$input."%")
                ->orWhere('author', 'LIKE', "%".$input."%")
                ->orWhere('translator', 'LIKE', "%".$input."%")
                ->orWhere('LCC', 'LIKE', "%".$input."%")
                ->orWhere('DDC', 'LIKE', "%".$input."%")
                ->orWhere('ISBN_period', 'LIKE', "%".$input."%")
                ->where('status', 1)
                ->get();
        }

        $filtered = collect();

        if ($publisher_id) {
            foreach($books as $book) {
                if ($book->publisher_id == $publisher_id) {
                    $filtered->add($book);
                }
            }
        } else {
            $filtered = $books;
        }

        return BookPreviewResource::collection($filtered);
    }

}


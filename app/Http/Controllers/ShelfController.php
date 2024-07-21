<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShelfPreviewResource;
use App\Http\Resources\ShelfResource;
use App\Models\Shelf;
use App\Models\ShelfBook;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class ShelfController extends Controller {
    
    public function show($id) {

        return new ShelfResource(Shelf::find($id));
    }

    public function storeShelf(Request $request) {

        /** @var User $user */ 
        $user = auth()->user();

        $request->validate([
            'shelfName' =>  'required',
        ]);

        $shelf = $user->createShelf(name: $request->get('shelfName'), description: $request->get('description'));
        
        return response()->json([
            'data' => [
                'shelf' => new ShelfResource($shelf),
            ],
            'message' => 'قفسه ایجاد شد'
        ], 200);
    }

    public function addBook($shelf_id, $book_id) {
        
        /** @var User $user */ 
        $user = auth()->user();
        $user->addBookToShelf($shelf_id, $book_id);
        return abort(200);
    }

    public function removeBook($shelf_id, $book_id) {

        /** @var User $user */ 
        $user = auth()->user();
        $user->removeBookFromShelf($shelf_id, $book_id);
        return abort(200);
    }

    public function update(Request $request, $shelf_id) {
        
        /** @var User $user */ 
        $user = auth()->user();
        $shelf = Shelf::find($shelf_id);

        if ($shelf->getUser()->id == $user->id) {
            $this->validate($request, [
                'name' => 'required',
            ]); 
            $shelf->update(name:$request->get('name'), description:$request->get('description'));
            return abort(200, 'موفقیت آمیز بود');
        }

        return abort(403);
    }

    public function delete($shelf_id) {

        /** @var User $user */ 
        $user = auth()->user();
        $shelf = Shelf::find($shelf_id);
        if ($shelf->getUser()->id == $user->id) {
            $shelf->delete();
            return abort(200);
        } else {
            return abort(403);
        }
    }

    public function index($user_id) {

        $user = User::find($user_id);
        return ShelfPreviewResource::collection($user->getShelves());
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShelfResource;
use App\Models\Shelf;
use App\Models\ShelfBook;
use App\Models\User;
use Illuminate\Http\Request;

class ShelfController extends Controller {
    
    public function show($id) {

        return new ShelfResource(Shelf::find($id));
    }

    public function storeShelf(Request $request) {

        $user = User::find(1);

        $request->validate([
            'name' =>  'required|unique:shelves',
        ]);

        $user->createShelf(name:$request->get('name'), description:$request->get('description'));
        return abort(200);
    }

    public function addBook($shelf_id, $book_id) {
        
        $user = User::find(1);
        $user->addBookToShelf($shelf_id, $book_id);
        return abort(200);
    }

    public function removeBook($shelf_id, $book_id) {

        $user = User::find(1);
        $user->removeBookFromShelf($shelf_id, $book_id);
        return abort(200);
    }

    public function updateName(Request $request, $shelf_id) {
        
        $user = User::find(1);
        $shelf = Shelf::find($shelf_id);

        if ($shelf->getUser()->id == $user->id) {
            $this->validate($request, [
                'name' => 'required',
            ]); 
    
            $shelf->update(name:$request->get('name'));
            return abort(200);
        }

        return abort(403);
    }

    public function updateDescription(Request $request, $shelf_id) {
        
        $user = User::find(1);
        $shelf = Shelf::find($shelf_id);

        if ($shelf->getUser()->id == $user->id) {
    
            $shelf->update(description:$request->get('description'));
            return abort(200);
        }

        return abort(403);
    }

    public function delete($shelf_id) {

        $user = User::find(1);
        $user->removeShelf($shelf_id);
        return abort(200);
    }
}

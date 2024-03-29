<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShelfResource;
use App\Models\Shelf;
use Illuminate\Http\Request;

class ShelfController extends Controller
{
    public function show($id) {

        return new ShelfResource(Shelf::find($id));
    }

    public function  storeShelf(Request $request) {


        $request->validate([
            'name' =>  'required|unique:shelves',
        ]);

        $shelf = new Shelf([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'user_id' => 1,
        ]);

        $shelf->save();

        return $shelf->id;
    
    }
}

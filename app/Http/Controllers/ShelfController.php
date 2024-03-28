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
}

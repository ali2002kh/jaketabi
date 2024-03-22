<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserPrivateResource;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller {

    public function user() {

        $user = User::find(1);
        return new UserPrivateResource($user);
    }
}

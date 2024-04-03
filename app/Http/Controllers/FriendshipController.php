<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FriendshipController extends Controller {

    public function acceptOrAdd($id) {

        $user = User::find(1);

        $user->sendRequestTo($id);
    }

    public function rejectOrRemove($id) {

        $user = User::find(1);

        $user->rejectOrRemoveFriend($id);
    }


}

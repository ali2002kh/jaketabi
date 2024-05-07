<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FriendshipController extends Controller {

    public function acceptOrAdd($id) {

        $user = auth()->user();

        $user->sendRequestTo($id);
    }

    public function rejectOrRemove($id) {

        $user = auth()->user();

        $user->rejectOrRemoveFriend($id);
    }

    public function status($id) {

        $user = auth()->user();

        return $user->friendshipStatus($id);
    }
}

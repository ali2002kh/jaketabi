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

        // 0 no request
        // 1 sent request
        // 2 received request
        // 3 friend

        if ($user->isFriend($id)) {
            return 3;
        } else if ($user->hasRequestedTo($id)) {
            return 1;
        } else if ($user->isRequestedBy($id)) {
            return 2;
        } else {
            return 0;
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserFriendResource;
use App\Models\User;
use Illuminate\Http\Request;

class FriendshipController extends Controller {

    public function acceptOrAdd($id) {

        /** @var User $user */ 
        $user = auth()->user();

        $user->sendRequestTo($id);
    }

    public function rejectOrRemove($id) {

        /** @var User $user */ 
        $user = auth()->user();

        $user->rejectOrRemoveFriend($id);
    }

    public function status($id) {

        $user = User::find($id);

        return new UserFriendResource($user);
    }
}

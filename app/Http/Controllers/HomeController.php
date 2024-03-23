<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookPublicResource;
use App\Http\Resources\FriendBookResource;
use App\Http\Resources\UserPrivateResource;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller {

    public function user() {

        $user = User::find(1);
        return new UserPrivateResource($user);
    }

    public function popular() {

        $user = User::find(1);
        return BookPublicResource::collection($user->getPopularBooks());
    }

    public function trending() {

        $user = User::find(1);
        return BookPublicResource::collection($user->getTrendingBooks());
    }

    public function friendsActivities() {

        $user = User::find(1);
        return FriendBookResource::collection($user->getFriendsBooks());
    }
}

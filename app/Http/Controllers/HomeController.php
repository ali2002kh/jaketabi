<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookPublicResource;
use App\Http\Resources\FriendBookResource;
use App\Http\Resources\ShelfPreviewResource;
use App\Http\Resources\ShelfResource;
use App\Http\Resources\UserPrivateResource;
use App\Http\Resources\UserPublicResource;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller {

    public function self() {

        $user = User::find(1);

        return new UserPrivateResource($user);
    }

    public function user($id) {

        $user = User::find(1);

        if ($user->id == $id) {
            return new UserPrivateResource($user);
        } else {
            $user = User::find($id);
            return new UserPublicResource($user);
        }
        
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

    public function friendsShelves() {

        $user = User::find(2);
        return ShelfPreviewResource::collection($user->getFriendsShelves());
    }
}

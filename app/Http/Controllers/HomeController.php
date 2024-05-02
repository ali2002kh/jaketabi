<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookPreviewResource;
use App\Http\Resources\BookPublicResource;
use App\Http\Resources\FriendBookResource;
use App\Http\Resources\ShelfPreviewResource;
use App\Http\Resources\ShelfResource;
use App\Http\Resources\UserPrivateResource;
use App\Http\Resources\UserPublicResource;
use App\Models\Book;
use App\Models\BookLog;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller {

    public function self() {

        if (auth()->check()) {
            return new UserPrivateResource(auth()->user());
        } else {
            return abort(401);
        }
    }

    public function user($id) {

        $user = auth()->user();

        if ($user and $user->id == $id) {
            return new UserPrivateResource($user);
        } else {
            $user = User::find($id);
            return new UserPublicResource($user);
        }
        
    }

    public function popular() {

        $logs = BookLog::orderBy('already_read', 'DESC')->take(20)->get();
        $books = collect();

        foreach ($logs as $log) {
            $books->add($log->getBook());
        }

        return BookPublicResource::collection($books);
    }

    public function trending() {

        $logs = BookLog::orderBy('reading', 'DESC')->take(20)->get();
        $books = collect();

        foreach ($logs as $log) {
            $books->add($log->getBook());
        }
        return BookPublicResource::collection($books);
    }

    public function friendsActivities() {

        $user = auth()->user();
        return FriendBookResource::collection($user->getFriendsBooks());
    }

    public function friendsShelves() {

        $user = auth()->user();
        return ShelfPreviewResource::collection($user->getFriendsShelves());
    }
}

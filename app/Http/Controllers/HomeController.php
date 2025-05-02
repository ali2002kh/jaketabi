<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookPreviewResource;
use App\Http\Resources\FriendBookResource;
use App\Http\Resources\ShelfPreviewResource;
use App\Http\Resources\ShelfResource;
use App\Http\Resources\UserPrivateResource;
use App\Http\Resources\UserPublicResource;
use App\Models\Book;
use App\Models\BookLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller {

    public function self() {

        if (auth()->check()) {
            return new UserPrivateResource(auth()->user());
        } else {
            return abort(401);
        }
    }

    public function user($id) {

        /** @var User $user */
        $user = auth()->user();

        if ($user and $user->id == $id) {
            return new UserPrivateResource($user);
        } else {
            $user = User::find($id);
            return new UserPublicResource($user);
        }

    }

    public function trendingAndPopular() {
        $cacheKey = 'trending_and_popular';

        // Cache::forget('trending_and_popular');

        $responseData = Cache::remember($cacheKey, now()->addMinutes(30), function () {

            $trendings = BookLog::orderBy('reading', 'DESC')->take(20)->get()
                ->map(fn($log) => $log->getBook());

            $populars = BookLog::orderBy('already_read', 'DESC')->take(20)->get()
                ->map(fn($log) => $log->getBook());

            return [
                'data' => [
                    'popular' => BookPreviewResource::collection($populars),
                    'trending' => BookPreviewResource::collection($trendings),
                ]
            ];
        });

        return response()->json($responseData);
    }


    public function friendsActivities() {

        /** @var User $user */
        $user = auth()->user();
        return FriendBookResource::collection($user->getFriendsBooks());
    }

    public function friendsShelves() {

        /** @var User $user */
        $user = auth()->user();
        return ShelfPreviewResource::collection($user->getFriendsShelves());
    }
}

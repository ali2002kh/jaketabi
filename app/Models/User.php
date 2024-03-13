<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function getShelves() {

        return Shelf::where('user_id', $this->id)->get();
    }

    public function getProfile() {

        return Profile::where('user_id', $this->id)->first();
    }

    public function name() {
        return $this->getProfile()->first_name.' '.$this->getProfile()->last_name;
    }

    public function isFriend($user_id) {

        $sended = Friend::all()
        ->where('sender', $this->id)
        ->where('receiver', $user_id)
        ->where('accepted', true);

        $received = Friend::all()
        ->where('sender', $user_id)
        ->where('receiver', $this->id)
        ->where('accepted', true);

        $friendship = $sended->union($received)->count();
        return $friendship;
    }

    public function isRequestedTo($user_id) {

        return Friend::all()
        ->where('sender', $this->id)
        ->where('receiver', $user_id)->count();
    }

    public function isRequestedBy($user_id) {

        return Friend::all()
        ->where('receiver', $this->id)
        ->where('sender', $user_id)->count();
    }


    public function getFriends() {

        $friends = array();
        $all_users = User::all();
        foreach ($all_users as $u) {
            if ($u->is_friend($this->id)) {
                $friends[] = $u;
            }
        }
        return $friends;
    }

    public function getFriendRequests() {

        $senders = array();

        $friend_requests = Friend::all()
        ->where('receiver', $this->id)
        ->where('accepted', false)
        ;

        foreach($friend_requests as $fr) {

            $senders[] = User::find($fr->sender());
        }

        return $senders();
    }

    public function getFriendsShelves() {

        $friends = $this->getFriends();
        $shelves = array();

        foreach($friends as $f) {
            foreach ($f->getShelves() as $s) {
                $shelves[] = $s;
            }
        }
        
        return $shelves;
    }
}

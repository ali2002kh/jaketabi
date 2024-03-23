<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Collection;

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


    // user information --------------------------------------------------------------------------

    public function getProfile() {

        return Profile::where('user_id', $this->id)->first();
    }

    public function name() {
        $profile = $this->getProfile();
        if ($profile) {
            return $this->getProfile()->first_name.' '.$this->getProfile()->last_name;
        }   
    }

    public function getImage() {
        $profile = $this->getProfile();
        if ($profile) {
            $image = $this->getProfile()->image;
            if ($image) {
                return $image;
            }
        }
        return 'default-image.jpg';
        
    }

    public function updateUser(
        $username=null, 
        $email=null,
        $number=null,
        $password=null,
    ) {
        if ($username) {
            $this->username = $username;
        }

        if ($email) {
            $this->email = $email;
        }

        if ($number) {
            $this->number = $number;
        }

        if ($password) {
            $this->password = $password;
        }

        $this->save();
    }

    public function updateProfile(
        $first_name=null, 
        $last_name=null,
        $image=null,
        $birth_date=null,
    ) {
        $profile = $this->getProfile();
        if (!$profile) {
            $profile = new Profile();
            $profile->user_id = $this->id;
        }

        if ($first_name) {
            $profile->first_name = $first_name;
        }

        if ($last_name) {
            $profile->last_name = $last_name;
        }

        if ($image) {
            $profile->image = $image;
        }

        if ($birth_date) {
            $profile->birth_date = $birth_date;
        }

        $profile->save();
    }

    // friendships --------------------------------------------------------------------------

    public function isFriend($user_id) {

        $sent = Friend::
        where('sender', $this->id)
        ->where('receiver', $user_id)
        ->where('accepted', true);

        $received = Friend::
        where('sender', $user_id)
        ->where('receiver', $this->id)
        ->where('accepted', true);

        $friendship = $sent->union($received)->count();
        return $friendship;
    }

    public function hasRequestedTo($user_id) {

        return Friend::
        where('sender', $this->id)
        ->where('receiver', $user_id)
        ->where('accepted', false)
        ->count();
    }

    public function isRequestedBy($user_id) {

        return Friend::
        where('receiver', $this->id)
        ->where('sender', $user_id)
        ->where('accepted', false)
        ->count();
    }


    public function getFriends() {

        $friends = collect();

        $sent = Friend::
        where('sender', $this->id)
        ->where('accepted', true)
        ->get();

        foreach ($sent as $s) {
            $friends->add($s->getReceiver());
        }

        $received = Friend::
        where('receiver', $this->id)
        ->where('accepted', true)
        ->get();

        foreach ($received as $r) {
            $friends->add($r->getSender());
        }
        
        return $friends;
    }

    public function hasFriendRequest() {

        if ($this->getFriendRequests()->isEmpty()) {
            return false;
        }
        return true;
    }

    public function getFriendRequests() {

        $senders = collect();

        $friend_requests = Friend::
        where('receiver', $this->id)
        ->where('accepted', false)
        ->get()
        ;

        foreach($friend_requests as $fr) {

            $senders->add($fr->getSender());
        }

        return $senders;
    }

    public function acceptFriendRequest($user_id) {

        $request = Friend::
        where('sender', $user_id)
        ->where('receiver', $this->id)
        ->first();

        $request->accepted = true;
        $request->save();
    }

    public function rejectOrRemoveFriend($user_id) {

        $request = Friend::
        where('sender', $user_id)
        ->where('receiver', $this->id)
        ->first();

        $request->delete();
    }

    public function sendRequestTo($user_id) {

        $request = new Friend([
            'sender' => $this->id,
            'receiver' => $user_id,
        ]);

        $request->save();
    }

    public function getFriendsWhoAreReadingOrAlreadyReadThisBook($book_id, $status) {

        $result = collect();
        $friends = $this->getFriends();

        foreach ($friends as $friend) {

            $record = $friend->getBookRecord($book_id);

            if ($record) {
                if ($record->status == $status) {
                    $result->add($friend);
                }
            }
        }

        return $result;
    }

    public function getFriendsWhoAreReadingThisBook($book_id, $preview=false) {

        $result = $this->getFriendsWhoAreReadingOrAlreadyReadThisBook($book_id, 2);

        if ($preview) {
            $result = $result->take(3);
        }

        return $result;
    }

    public function getFriendsWhoAlreadyReadThisBook($book_id, $preview=false) {

        $result = $this->getFriendsWhoAreReadingOrAlreadyReadThisBook($book_id, 3);

        if ($preview) {
            $result = $result->take(3);
        }

        return $result;
    }

    public function getFriendsReadingBookPreview($book_id) {

        $reading = $this->getFriendsWhoAreReadingThisBook($book_id, preview: true);
        $read = $this->getFriendsWhoAlreadyReadThisBook($book_id, preview: true);
        
        $result = $reading->union($read)->take(3);
        return $result;
    }

    // shelves --------------------------------------------------------------------------

    public function getShelves() {

        return Shelf::where('user_id', $this->id)->get();
    }

    public function createShelf($name, $description=null) {

        $shelf = new Shelf();
        $shelf->user_id = $this->id;
        $shelf->name = $name;
        if ($description) {
            $shelf->description = $description;
        }
        $shelf->save();
    }

    public function getFriendsShelves() {

        $friends = $this->getFriends();
        $shelves = collect();

        foreach($friends as $f) {
            foreach ($f->getShelves() as $s) {
                $shelves->add($s);
            }
        }
        
        return $shelves;
    }

    // books --------------------------------------------------------------------------

    public function getRecordedBooks($status) {

        $books = collect();
        $records = UserBook::
        where('user_id', $this->id)
        ->where('status', $status)
        ->get();

        foreach ($records as $r) {

            $b = $r->getBook();
            if ($b->isActive()) {
                $books->add($b);
            }
        }

        return $books;
    }

    public function getWantToReadBooks() {
        return $this->getRecordedBooks(1);
    }

    public function getCurrentlyReadingBooks() {
        return $this->getRecordedBooks(2);
    }

    public function getAlreadyReadBooks() {
        return $this->getRecordedBooks(3);
    }

    public function getBookRecord($book_id) {

        return UserBook::
        where('book_id', $book_id)
        ->where('user_id', $this->id)
        ->get()
        ->first();
    }

    public function getFriendsBooks() {

        $books = collect();
        $index = [];

        $friends = $this->getFriends();

        foreach ($friends as $friend) {

            foreach ($friend->getCurrentlyReadingBooks() as $b) {
                $books->add($b);
                $index[$b->id] = isset($index[$b->id]) ? $index[$b->id] + 1 : 1;
            }

            foreach ($friend->getAlreadyReadBooks() as $b) {
                $books->add($b);
                $index[$b->id] = isset($index[$b->id]) ? $index[$b->id] + 1 : 1;
            }
            
        }
    
        $groupedBooks = $books->groupBy('id');
        $sortedBooks = $groupedBooks->map(function (Collection $books, $key) use ($index) {
            return ['count' => $index[$key], 'book' => $books->first()];
        })->sortByDesc('count');
    
    
        $books = collect();
        foreach ($sortedBooks as $sb) {
            $books->add($sb['book']);
        }

        return $books;
    }

    public function updateBookStatus($book_id, $status) {

        $record = $this->getBookRecord($book_id);
        $book = Book::find($book_id);

        if ($record) {
            $previous_status = $record->status;
            $record->status = $status;
            $book->updateLog($previous_status, 'dec');
        } else {
            $record = new UserBook([
                'user_id' => $this->id,
                'book_id' => $book_id,
                'status' => $status,
            ]);
        }

        $record->save();
        $book->updateLog($status, 'inc');
    }

    public function updateCurrentPage($book_id, $page) {

        $record = $this->getBookRecord($book_id);
        $record->current_page = $page;
        $record->save();
    }

    public function getPopularBooks() { 
        
        $logs = BookLog::orderBy('already_read', 'DESC')->take(20)->get();
        $books = collect();

        foreach ($logs as $log) {
            $books->add($log->getBook());
        }

        return $books;
    }

    public function getTrendingBooks() { 
        
        $logs = BookLog::orderBy('reading', 'DESC')->take(20)->get();
        $books = collect();

        foreach ($logs as $log) {
            $books->add($log->getBook());
        }

        return $books;
    }

    // comments --------------------------------------------------------------------------

    public function addComment($book_id, $message) {

        $comment = new BookComment([
            'user_id' => $this->id,
            'message' => $message,
            'book_id' => $book_id,
        ]);
        $comment->save();
    }

}

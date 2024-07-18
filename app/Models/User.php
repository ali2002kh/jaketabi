<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
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


    // administration --------------------------------------------------------------------------

    public function getRoleUser() {
        return RoleUser::where('user_id', $this->id)->first();
            
            // 1: admin
            // 2: super admin
            // 3: publisher admin
            // 4: publisher super admin
    }

    public function getPermissions() {

        $role = $this->getRoleUser()->getRole();
        return $role->getPermissions();
    }

    public function getPublisher() {

        return $this->getRoleUser()->getPublisher();
    }

    public function createBook (
        $isbn, 
        $name,
        $image = null,
        $author,
        $category_id,
        $release_date = null,
        $publisher_id,
        $description = null,
        $translator = null,
        $page_count = null,
        $lcc = null,
        $ddc = null,
        $isbn_period = null,
    ) {

        $book = new Book([
            'ISBN' => $isbn,
            'name' => $name,
            'image' => $image,
            'author' => $author,
            'category_id' => $category_id,
            'release_date' => $release_date,
            'publisher_id' => $publisher_id,
            'description' => $description,
            'translator' => $translator,
            'page_count' => $page_count,
            'LCC' => $lcc,
            'DDC' => $ddc,
            'ISBN_period' => $isbn_period,
        ]);

        $book->save();

        $bookLog = new BookLog(['book_id' => $book->id]);
        $bookLog->save();

        return $book->id;
    }

    public function promoteNormalUserToPublisherAdmin($user_id, $publisher_id) {

        $ru = new RoleUser();
        $ru->user_id = $user_id;
        $ru->role_id = 3;
        $ru->publisher_id = $publisher_id;
        $ru->save();
    }

    public function promotePublisherAdminToPublisherSuperAdmin($user_id, $publisher_id) {

        $ru = RoleUser::where('user_id', $user_id)->first();

        if (!$ru) {
            abort(404);
        }

        if ($ru->publisher_id == $publisher_id) {
            $ru->role_id = 4;
            $ru->save();
            abort(200);
        } 

        abort(403);
    }

    public function demotePublisherAdminToNormalUser($user_id, $publisher_id) {

        $ru = RoleUser::where('user_id', $user_id)->first();

        if (!$ru) {
            abort(404);
        }

        if ($ru->publisher_id == $publisher_id) {
            $ru->delete();
            abort(200);
        } 

        abort(403);
    }

    public function demoteAdminOrPublisherSuperAdminToNormalUser($user_id) {

        $ru = RoleUser::where('user_id', $user_id)->first();

        if (!$ru) {
            abort(404);
        }

        $ru->delete();
        abort(200);
    }

    public function promoteNormalUserToAdmin($user_id) {

        $ru = new RoleUser();
        $ru->user_id = $user_id;
        $ru->role_id = 1;
        $ru->save();
    }

    public function promoteAdminToSuperAdmin($user_id) {

        $ru = RoleUser::where('user_id', $user_id)->first();

        if (!$ru) {
            abort(404);
        }

        $ru->role_id = 2;
        $ru->save();
        abort(200);
    }



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
        $username = null, 
        $email = null,
        $number = null,
        $password = null,
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
        $firstName = null, 
        $lastName = null,
        $image = null,
        $birthDate = null,
    ) {
        $profile = $this->getProfile();
        if (!$profile) {
            $profile = new Profile();
            $profile->user_id = $this->id;
        }

        if ($firstName) {
            $profile->first_name = $firstName;
        }

        if ($lastName) {
            $profile->last_name = $lastName;
        }

        if ($image) {
            $profile->image = $image;
        }

        if ($birthDate) {
            $profile->birth_date = $birthDate;
        }

        $profile->save();
    }

    // friendships --------------------------------------------------------------------------

    public function getRequest($user_id) {

        $sent = Friend::
        where('sender', $this->id)
        ->where('receiver', $user_id);

        $received = Friend::
        where('sender', $user_id)
        ->where('receiver', $this->id);

        $friendship = $sent->union($received)->first();
        return $friendship;
    }

    public function isFriend($user_id) {

        $request = $this->getRequest($user_id);
        if ($request && $request->accepted) {
            return true;
        }
        return false;
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

        if ($request) {
            $request->accepted = true;
            $request->save();
        } else {
            return 'there is no request';
        }
    }

    public function rejectOrRemoveFriend($user_id) {

        $request = $this->getRequest($user_id);

        if ($request) {
            $request->delete();
        } else {
            abort(404);
        }

    }

    public function sendRequestTo($user_id) {

        if ($this->hasRequestedTo($user_id)) {
            return 'already requested';
        }

        if ($this->isRequestedBy($user_id)) {
            $this->acceptFriendRequest($user_id);
            return 'request accepted';
        }
        
        if ($this->isFriend($user_id)) {
            return 'already friend';
        }

        $request = new Friend([
            'sender' => $this->id,
            'receiver' => $user_id,
        ]);

        $request->save();
    }

    public function friendshipStatus($id) {

        // 0 no request
        // 1 sent request
        // 2 received request
        // 3 friend

        if ($this->isFriend($id)) {
            return 3;
        } else if ($this->hasRequestedTo($id)) {
            return 1;
        } else if ($this->isRequestedBy($id)) {
            return 2;
        } else {
            return 0;
        }
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
        
        $result = $read->merge($reading)->take(3);
        return $result;
    }

    // shelves --------------------------------------------------------------------------

    public function getShelves() {

        return Shelf::where('user_id', $this->id)->get();
    }

    public function createShelf($name, $description = null) {

        $shelf = new Shelf();
        $shelf->user_id = $this->id;
        $shelf->name = $name;
        if ($description) {
            $shelf->description = $description;
        }
        $shelf->save();
        return $shelf;
    }

    public function addBookToShelf($shelf_id, $book_id) {
    
        $shelf = Shelf::find($shelf_id);
        if ($shelf->getUser()->id == $this->id) {
            $shelf->addBook($book_id);
        }
    }

    public function removeBookFromShelf($shelf_id, $book_id) {
    
        $shelf = Shelf::find($shelf_id);
        if ($shelf->getUser()->id == $this->id) {
            $shelf->removeBook($book_id);
        }
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

    public function getShelvesWithThisBook($book_id) {

        $shelves = collect();
        foreach ($this->getShelves() as $shelf) {
            if ($shelf->hasBook($book_id)) {
                $shelves->add($shelf->id);
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

        $now = Carbon::now()->format('Y-m-d');
        $record = $this->getBookRecord($book_id);
        $book = Book::find($book_id);

        if ($record) {
            $previous_status = $record->status;
            $record->status = $status;
            $book->updateLog($previous_status, 'dec');
            if ($status == 2) {
                if (is_null($record->started_at)) {
                    $record->started_at = $now;
                } 
            } else if ($status == 3) {
                if (is_null($record->started_at)) {
                    $record->started_at = $now;
                } 
                $record->finished_at = $now;
            }

        } else {
            $record = new UserBook([
                'user_id' => $this->id,
                'book_id' => $book_id,
                'status' => $status,
            ]);
            if ($status == 2) {
                $record->started_at = $now;
            } else if ($status == 3) {
                $record->started_at = $now;
                $record->finished_at = $now;
            }
        }

        $record->save();
        $book->updateLog($status, 'inc');
    }

    public function updateCurrentPage($book_id, $page) {

        $now = Carbon::now()->format('Y-m-d');
        $book = Book::find($book_id);
        if ($book->page_count < $page) {
            return 'error'; 
        }
        $record = $this->getBookRecord($book_id);
        $record->current_page = $page;
        $record->last_read_at = $now;
        $record->save();
    }

    // comments --------------------------------------------------------------------------

    public function addComment($book_id, $message) {

        $comment = new BookComment([
            'user_id' => $this->id,
            'message' => $message,
            'book_id' => $book_id,
        ]);
        $comment->save();
        return $comment;
    }

}

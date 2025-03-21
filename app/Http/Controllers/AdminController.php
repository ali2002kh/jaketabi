<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookPrivateResource;
use App\Http\Resources\PublisherAdministrationResource;
use App\Http\Resources\UserRoleResource;
use App\Models\Book;
use App\Models\GenreBook;
use App\Models\Publisher;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

// use Illuminate\Validation\Rule;

class AdminController extends Controller
{

    public function showBook($id) {
        $book = Book::find($id);
        return new BookPrivateResource($book);
    }

    public function storeBook(Request $request) {

        /** @var User $user */ 
        $user = auth()->user();
         
        if (!$user->getPermissions()->contains('id', 1)) {
            return abort(403);
        }

        $request->validate([
            'isbn' => 'required|unique:books',
            'book_name' => 'required',
            'author' => 'required',
            'category_id' => 'required',
            'genres' => 'required',
        ]);

        if ($user->getPermissions()->contains('id', 11)) {
            $request->validate([
                'publisher_id' => 'required',
            ]);
            $publisher_id = $request->get('publisher_id');
        } else {
            $publisher_id = $user->getPublisher()->id;
        }

        if ($request->hasFile('file')) {

            $request->validate([
                'image' => 'mimes:jpeg,bmp,png'
            ]);

            $request->file->store('book', 'public');

            $image = $request->file->hashName();

        } else {
            $image = null;
        }

        $book = $user->createBook(
            isbn : $request->get('isbn'), 
            name : $request->get('book_name'),
            image : $image,
            author : $request->get('author'),
            category_id : $request->get('category_id'),
            release_date : $request->get('release_date'),
            publisher_id : $publisher_id,
            description : $request->get('description'),
            translator : $request->get('translator'),
            page_count : $request->get('page_count'),
            lcc : $request->get('lcc'),
            ddc : $request->get('ddc'),
            isbn_period : $request->get('isbn_period'),
        );

        foreach (explode(",", $request->get('genres')) as $genre_id) {
            $gb = new GenreBook();
            $gb->genre_id = $genre_id;
            $gb->book_id = $book->id;
            $gb->save();
        }

        $book->updateBookRelations();
        
        return abort(200, 'کتاب با موفقیت ایجاد شد');
    }

    public function updateBook($book_id, Request $request) {
      
        /** @var User $user */ 
        $user = auth()->user();
        $book = Book::find($book_id);

        if (
            !$user->getPermissions()->contains('id', 2) ||
            (
                !$user->getPermissions()->contains('id', 11) && 
                $user->getPublisher()->id != $book->getPublisher()->id
            )
        ) {
            return abort(403);
        }

        $request->validate([
            'isbn' => [Rule::unique('books')->ignore($book_id), 'required'],
            'book_name' => 'required',
            'author' => 'required',
            'category_id' => 'required',
            'genres' => 'required',
        ]);

        if ($user->getPermissions()->contains('id', 11)) {
            $request->validate([
                'publisher_id' => 'required',
            ]);
            $publisher_id = $request->get('publisher_id');
        } else {
            $publisher_id = $user->getPublisher()->id;
        }

        if ($request->hasFile('file')) {

            $request->validate([
                'image' => 'mimes:jpeg,bmp,png'
            ]);

            $request->file->store('book', 'public');

            $image = $request->file->hashName();

        } else {
            $image = null;
        }

        $prevGenres = $book->getGenreBooks();

        foreach ($prevGenres as $g) {
            $g->delete();
        }

        foreach (explode(",", $request->get('genres')) as $genre_id) {
            $gb = new GenreBook();
            $gb->genre_id = $genre_id;
            $gb->book_id = $book_id;
            $gb->save();
        }

        $book->updateBook (
            isbn : $request->get('isbn'), 
            name : $request->get('book_name'),
            image : $image,
            author : $request->get('author'),
            category_id : $request->get('category_id'),
            release_date : $request->get('release_date'),
            publisher_id : $publisher_id,
            description : $request->get('description'),
            translator : $request->get('translator'),
            page_count : $request->get('page_count'),
            lcc : $request->get('lcc'),
            ddc : $request->get('ddc'),
            isbn_period : $request->get('isbn_period'),
        );

        $book->updateBookRelations();

       return abort(200, 'با موفقیت بروزرسانی شد');
    }

    public function removeBook($book_id) {

        /** @var User $user */ 
        $user = auth()->user();
        $book = Book::find($book_id);

        if (
            !$user->getPermissions()->contains('id', 3) ||
            (
                !$user->getPermissions()->contains('id', 11) && 
                $user->getPublisher()->id != $book->getPublisher()->id
            )
        ) {
            return abort(403);
        }

        $book->remove();
        abort(200, 'کتاب با موفقیت حذف شد');
    }

    public function promoteNormalUserToPublisherAdmin($user_id, $publisher_id) {

        /** @var User $user */ 
        $user = auth()->user();
        
        if (
            !$user->getPermissions()->contains('id', 4) ||
            (
                !$user->getPermissions()->contains('id', 11) && 
                $user->getPublisher()->id != $publisher_id
            )
        ) {
            return abort(403);
        }

        $user->promoteNormalUserToPublisherAdmin($user_id, $publisher_id);

    }

    public function promotePublisherAdminToPublisherSuperAdmin($user_id, $publisher_id) {

        /** @var User $user */ 
        $user = auth()->user();
        if (
            !$user->getPermissions()->contains('id', 5) ||
            (
                !$user->getPermissions()->contains('id', 11) && 
                $user->getPublisher()->id != $publisher_id
            )
        ) {
            return abort(403);
        }

        $user->promotePublisherAdminToPublisherSuperAdmin($user_id, $publisher_id);

    }

    public function demotePublisherAdminToNormalUser($user_id, $publisher_id) {

        /** @var User $user */ 
        $user = auth()->user();
        if (
            !$user->getPermissions()->contains('id', 6) ||
            (
                !$user->getPermissions()->contains('id', 11) && 
                $user->getPublisher()->id != $publisher_id
            )
        ) {
            return abort(403);
        }

        $user->demotePublisherAdminToNormalUser($user_id, $publisher_id);

    }

    public function demotePublisherSuperAdminToNormalUser($user_id) {

        /** @var User $user */ 
        $user = auth()->user();
        if (!$user->getPermissions()->contains('id', 7)) {
            return abort(403);
        }

        $user->demoteAdminOrPublisherSuperAdminToNormalUser($user_id);

    }

    public function promoteNormalUserToAdmin($user_id) {

        /** @var User $user */ 
        $user = auth()->user();
        if (!$user->getPermissions()->contains('id', 8)) {
            return abort(403);
        }

        $user->promoteNormalUserToAdmin($user_id);

    }

    public function demoteAdminToNormalUser($user_id) {

        /** @var User $user */ 
        $user = auth()->user();
        if (!$user->getPermissions()->contains('id', 9)) {
            return abort(403);
        }

        $user->demoteAdminOrPublisherSuperAdminToNormalUser($user_id);

    }

    public function promoteAdminToSuperAdmin($user_id) {

        /** @var User $user */ 
        $user = auth()->user();
        if (!$user->getPermissions()->contains('id', 10)) {
            return abort(403);
        }

        $user->promoteAdminToSuperAdmin($user_id);

    }

    public function publisherAdmins($publisher_id) {

        /** @var User $user */
        $user = auth()->user();
        if (!$user->getPermissions()->contains('id', 4)) {
            return abort(403);
        }

        $publisher = Publisher::find($publisher_id);
        return UserRoleResource::collection($publisher->getAdmins());

    }

    public function publishers() {

        return PublisherAdministrationResource::collection(Publisher::all());
    }

    public function admins() {

        /** @var User $user */
        $user = auth()->user();
        if (!$user->getPermissions()->contains('id', 10)) {
            return abort(403);
        }

        $admins = collect();
        $roleUsers = RoleUser::where('role_id', 1)
        ->orWhere('role_id', 2)
        ->get();

        foreach ($roleUsers as $ru) {
            $admins->add($ru->getUser());
        }

        return UserRoleResource::collection($admins);
    }
    
}

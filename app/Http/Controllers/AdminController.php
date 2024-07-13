<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\GenreBook;
use Illuminate\Http\Request;
// use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function storeBook(Request $request) {

        /** @var User $user */ 
        $user = auth()->user();
         
        if (!$user->getPermissions()->contains('id', 1)) {
            return abort(403);
        }

        $request->validate([
            'isbn' => 'required',
            'name' => 'required',
            'author' => 'required',
            'category_id' => 'required',
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

        $user->createBook(
            isbn : $request->get('isbn'), 
            name : $request->get('name'),
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
            'isbn' => 'required',
            'name' => 'required',
            'author' => 'required',
            'category_id' => 'required',
        ]);


        if ($request->hasFile('file')) {

            $request->validate([
                'image' => 'mimes:jpeg,bmp,png'
            ]);

            $request->file->store('book', 'public');

            $image = $request->file->hashName();

        } else {
            $image = null;
        }

        $book->updateBook (
            isbn : $request->get('isbn'), 
            name : $request->get('name'),
            image : $image,
            author : $request->get('author'),
            category_id : $request->get('category_id'),
            release_date : $request->get('release_date'),
            description : $request->get('description'),
            translator : $request->get('translator'),
            page_count : $request->get('page_count'),
            lcc : $request->get('lcc'),
            ddc : $request->get('ddc'),
            isbn_period : $request->get('isbn_period'),
        );

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

    public function addGenreToBook ($book_id, $genre_id) {

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

        $gb = new GenreBook();
        $gb->book_id = $book_id;
        $gb->genre_id = $genre_id;
        $gb->save();
    }

    public function removeGenreFromBook ($book_id, $genre_id) {

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

        $gb = GenreBook::where(['book_id' => $book_id, 'genre_id' => $genre_id]);
        $gb->delete();
    }
}

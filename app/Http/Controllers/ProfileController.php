<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookPreviewResource;
use App\Http\Resources\UserBookResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller {
    
    public function update(Request $request) {

        /** @var User $user */ 
        $user = auth()->user();

        $request->validate([
            'username' => [Rule::unique('users')->ignore($user->id), 'required'],
            // 'number' => ['regex:/(09)[0-9]{9}/', 'required' , Rule::unique('users')->ignore($user->id)],
            'email' => [Rule::unique('users')->ignore($user->id), 'required'],
            'password' => 'min:8|nullable',
            'confirmPassword' => 'same:password',
        ]);

        $user->updateUser(
            username: $request->get('username'),
            email: $request->get('email'),
            // number: $request->get('number'),
            password: $request->get('password'),
        );

        $user->updateProfile(
            firstName: $request->get('fname'),
            lastName: $request->get('lname'),
            // birthDate: $request->get('birth_date'),
        );

        if ($request->hasFile('file')) {

            $request->validate([
                'image' => 'mimes:jpeg,bmp,png'
            ]);

            $request->file->store('user', 'public');

            $user->updateProfile(
                image: $request->file->hashName(),
            );
        }

       return abort(200, 'با موفقیت بروزرسانی شد');
    }

    public function books($id, $status) {

        $user = User::find($id);
        $books = $user->getRecordedBooks($status);
        $records = collect();
        foreach($books as $book) {
            $records->add($user->getBookRecord($book->id));
        }
        return UserBookResource::collection($records);
    }
}
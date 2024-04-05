<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller {
    
    public function update(Request $request) {

        $user = User::find(1);

        $request->validate([
            'username' => [Rule::unique('users')->ignore($user->id), 'required'],
            'number' => ['regex:/(09)[0-9]{9}/', 'required' , Rule::unique('users')->ignore($user->id)],
            'email' => [Rule::unique('users')->ignore($user->id), 'required'],
            'password' => 'min:8|nullable',
            'confirmPassword' => 'same:password',
        ]);

        $user->updateUser(
            username: $request->get('username'),
            email: $request->get('email'),
            number: $request->get('number'),
            password: $request->get('password'),
        );

        $user->updateProfile(
            firstName: $request->get('first_name'),
            lastName: $request->get('last_name'),
            birthDate: $request->get('birth_date'),
        );

        if ($request->hasFile('file')) {

            $request->validate([
                'image' => 'mimes:jpeg,bmp,png'
            ]);

            $request->file->store('user', 'public');

            $old_image = $user->getImage();
            $old_image->delete();

            $user->updateProfile(
                image: $request->file->hashName(),
            );
        }

       return abort(200, 'information successfully updated');
    }
}
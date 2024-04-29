<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

    public function login(Request $request) {

        $request->validate([
            'emailOrNumberOrUsername' => 'required',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->get('emailOrNumberOrUsername'))->first();
        if ($user == null) {
            $user = User::where('number', $request->get('emailOrNumberOrUsername'))->first();
            if ($user == null) {
                $user = User::where('username', $request->get('emailOrNumberOrUsername'))->first();
                if ($user == null) {
                    return abort(421, 'ایمیل یا شماره تلفن یا نام کاربری اشتباه است');
                }
            }
        }

        if (Hash::check($request->get('password'), $user->password)) {
            Auth::login($user);
            return abort(200);
        }

        return abort(421, 'رمز عبور اشتباه است');
    }

    public function signup(Request $request) {

        $request->validate([
            'username' => 'required|unique:users',
            'number' => 'required|regex:/(09)[0-9]{9}/|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|min:8',
            'confirmPassword' => 'required|same:password|min:8',
        ]);
    
        $user = new User([
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'number' => $request->get('number'),
            'password' => $request->get('password'),
        ]);
        $user->save();
        Auth::login($user);
    }

    public function logout() {

        Auth::logout();
        return abort(200);
    }
}

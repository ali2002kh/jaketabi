<?php

namespace App\Http\Controllers;

use App\Http\Services\Message\MessageService;
use App\Http\Services\Message\SMS\SmsService;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        // dd($user);
        Auth::login($user);
        return abort(200);
    }

    public function logout() {

        Auth::logout();
        return abort(200);
    }

    public function loginRegister(Request $request) {

        $request->validate([
            'number' => 'required|regex:/(09)[0-9]{9}/',
        ]);

        $number = $request->get('number');

        // all mobile numbers are in on format 9** *** ***
        $number = ltrim($number, '0');
        $number = substr($number, 0, 2) === '98' ? substr($number, 2) : $number;
        $number = str_replace('+98', '', $number);

        $user = User::where('number', $number)->first();

        //create otp code
        $otpCode = rand(111111, 999999);
        $token = Str::random(60);

        $otp = new Otp;
        $otp->token = $token;
        $otp->user_id = $user->id;
        $otp->otp_code = $otpCode;
        $otp->number = $number;


        $smsService = new SmsService();
        $smsService->setFrom(Config::get('sms.otp_from'));
        $smsService->setTo(['0' . $user->number]);
        $smsService->setText("جاکتابی  \n  کد تایید : $otpCode");
        $smsService->setIsFlash(true);

        $messagesService = new MessageService($smsService);
        $messagesService->send();
    }
}

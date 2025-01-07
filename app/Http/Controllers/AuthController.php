<?php

namespace App\Http\Controllers;

use App\Http\Services\Message\MessageService;
use App\Http\Services\Message\SMS\SmsService;
use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
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

    public function generateOtp(Request $request) {

        // phpinfo();

        $request->validate([
            'number' => 'required|regex:/(09)[0-9]{9}/',
        ]);

        $number = $request->get('number');

        $user = User::where('number', $number)->first();

        if (!$user) {
            $user = new User([
                'username' => 'guest'.Str::random(10),
                'email' => 'guest'.Str::random(10),
                'number' => $number,
                'password' => Str::random(10),
            ]);
            $user->save();
        } else {

            if ($user->mobile_verified_at) {
                $request->validate([
                    'number' => 'unique:users',
                ]);
            }

            $otp = Otp::where('user_id', $user->id)
            ->where('used', 0)
            ->where('created_at', '>=', Carbon::now()->subMinute(2)->toDateTimeString())
            ->first();

            if ($otp) {
                return abort(403, 'too soon');
            }
        }

        //create otp code
        $otpCode = rand(111111, 999999);
        $token = Str::random(60);

        $otp = new Otp;
        $otp->token = $token;
        $otp->user_id = $user->id;
        $otp->otp_code = $otpCode;
        $otp->number = $number;
        $otp->save();

        $smsService = new SmsService();
        $smsService->setFrom(Config::get('sms.otp_from'));
        $smsService->setTo([$number]);
        $smsService->setText("جاکتابی  \nکد تایید : $otpCode");
        $smsService->setIsFlash(true);

        $messagesService = new MessageService($smsService);
        $messagesService->send();

        return abort(200, 'کد تایید برای شما ارسال شد', ['token' => $token]);
    }

    public function register(Request $request, $token) {

        $request->validate([
            'username' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|min:8',
            'confirmPassword' => 'required|same:password|min:8',
            'otp' => 'required|min:6|max:6',
        ]);

        $otp = Otp::where('token', $token)
        ->where('used', 0)
        ->where('created_at', '>=', Carbon::now()->subMinute(2)->toDateTimeString())
        ->first();

        $user = $otp->getUser();

        if (!$otp) {
            $user->delete();
            return abort(403, 'Invalid token');
        }

        if ($otp->otp_code !== $request->get('otp')) {
            $otp->delete();
            $user->delete();
            return abort (403, 'کد وارد شده صحیح نمیباشد');
        }

        $otp->used = 1;
        $otp->save();

        if (!$user->mobile_verified_at) {
            $user->mobile_verified_at = Carbon::now();
        }

        $user->username = $request->get('username');
        $user->email = $request->get('email');
        $user->password = $request->get('password');
        $user->save();

        Auth::login($user);
        return abort(200);
    }


}

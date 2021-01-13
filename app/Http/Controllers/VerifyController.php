<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\SendCode;
use App\Securities;
use Illuminate\Support\Facades\Auth;


class VerifyController extends Controller
{
    public function viewEmailNotification(Request $request){
        return view('verification.email_verification');
    }

    public function getMobileVerification(Request $request){
        $phone = $request->input('login');
        return view('verification.mobile_verification', compact('data'));
    }

    public function postMobileVerification(Request $request){

        if($user = User::where('secretkey',$request->code)->first()){
            $security = Securities::where('user_id', $user->user_id)->first();
            if($security->tfa_enabled){
                Auth::login($user);
                return view("home", compact("user"));
            }
            $user->status = "activated";
            $user->save();
            Auth::login($user);
            return view("home", compact("user"));
        }else{
            return back()->withMessage('Verification code is incorrect, Please try again');
        }

    
    }
    public function resendMobileVerification(Request $request){
        SendCode::sendCode($request->login);
        return back()->withMessage('Resent Verification Code');
    }

    public function verifyUser($token){
    $user = User::where('secret_key', $token)->first();
    if($user){
        if(!$user->email_verified) {
            $user->email_verified = now();
            $user->save();
            $status = "Your e-mail is verified. You can now login.";
        } else {
            $status = "Your e-mail is already verified. You can now login.";
        }
    } else {
        return redirect()->route('login')->with('error',"Sorry your email can not be identified.");
    }
    return redirect()->route('login')->with('error',$status);
    }

}

<?php

namespace App\Http\Controllers\Api;

use Hash;
use App\User;
use App\SendCode;
use App\Securities;
use App\Locked_Accounts;
use App\Contact_Information;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{

    use AuthenticatesUsers{
        logout as performLogout;
    }

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    public function login(Request $request){


        $loginData = $request->validate([
            'login' => 'bail|required', 
            'password' => 'required',
        ]);

            
        if(is_numeric($loginData['login'])){
            $field = "contact";
        }elseif(filter_var($loginData['login'], FILTER_VALIDATE_EMAIL)){
            $field = "email";
        }else{
            return response()->json(['message' => 'Invalid Email'])->setStatusCode(400);
        }
        $request->merge([$field => $request->input('login')]);
        $loginData[$field] = $loginData['login'];
        unset($loginData['login']);

        if($field == "contact"){
            return redirect()->route('login')->withErrors(['login' => "Invalid Username or Password"]);
            
            $ci = Contact_Information::where('contact', $loginData['login'])->first();
            if($ci){
                if(empty(Locked_Accounts::where('user_id', $ci->user_id)->first())){
                    $security = Securities::where('user_id', $user->user_id)->first();
                    $user = User::find($ci->user_id);
                    if(Hash::check($request->input('password'), $user->password))
                    {
                        $security->max_trials = 0;
                        $security->save();
                        if ($security->otp_enabled == 1){
                            SendCode($ci->contact);
                            return redirect('/verify_mobile?phone='.$ci->contact);
                        }
                        Auth::login($user);
                       
                        $user = auth()->user()->user_id;
                        return view('home'); 
                    }
                    $security->max_trials += 1;
                    $security->save();
                    if($security->max_trials >= 5){
                        Locked_Accounts::create([
                            'lc_id' => $user->user_id,
                            'user_id' => $user->user_id,
                            'expires_at' => now(),
                        ]);
                        return redirect()->route('login')->withErrors(['login',"Locked Account"]);
                        // return response(['message' => 'Account Locked']);
                    }
                    return redirect()->route('login')->withErrors(['login' => "Invalid Username or Password."]);
                    // return response(['message' => "Invalid username or password. trials = ".$security->max_trials]);
                }
                return redirect()->route('login')->with('success',"Locked Account");
                // return response(['message' => 'Locked Account']);
            }
            return redirect()->route('login')->withErrors(['login' => "Invalid Username or Password"]);
            // return response(['message' => 'Invalid Username or Password']);

        }else{

            $user = User::where('email', $loginData[$field])->first();

            if($user){
                if($user->email_verified){
                    $security = Securities::where('user_id', $user->user_id)->first();
                    if(!auth()->attempt($loginData)){
                        $security->max_trials += 1;
                        $security->save();
                        return redirect()->route('login')->withErrors(['login' => "Invalid username or password."]);
                    }
                    Auth::login($user);
                    $accessToken = auth()->user()->createToken('authToken')->accessToken;

                    $credentials = $request->only('email', 'password');
			        if ($token = $this->guard()->attempt($credentials)) {
			            return response()->json(['user' => $user, 'access_token' => $accessToken], 200)->header('Authorization', $token);
			        }
                }
                return redirect()->route('login')->withErrors(['login' => "Please verify Email first"]);
            }
            return redirect()->route('login')->withErrors(['login' => "Invalid Username or Password"]);
            /*
                GET USER'S IP AND DEVICE INFO
                IF ATTEMPTS EXCEED MAX LIMIT
                BLOCK USER'S IP
            */

        }
    }

    
}

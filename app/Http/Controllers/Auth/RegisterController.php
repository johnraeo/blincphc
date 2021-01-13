<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Devices;
use App\Profile;
use App\SendCode;
use App\Securities;
use App\Wallet\Wallet;
use App\Mail\VerifyMail;
use App\Contact_Information;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;


class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function register(Request $request){


        $loginData = $request->validate([
            'email' => ['string', 
                        'email', 
                        'max:255', 
                        'required',
                        'unique:users'],

            'password' => ['required',
                           'string',
                           'min:8',
                           'max: 30', 
                           'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/', 
                           'confirmed'],
        ]);

        $this->createWithEmail($request);

        // return redirect()->route('login')->with('error',"We have sent an verification mail to the email you provided");
        return response()->json(['message' => "We have sent an verification mail to the email you provided"],200);

    }

    // protected function register(Request $request){

    //     $field =$request->input('login');
    //     if(is_numeric($request->input('login'))){
    //         return redirect()->route('register')->withErrors(['login'  => "Invalid email"]);
    //         $field = "contact";
    //     }elseif(filter_var($request->input('login'), FILTER_VALIDATE_EMAIL)){
    //         $field = "email";
    //     }else{
    //         // return redirect()->route('register')->with('error',"Invalid Email");
    //         return redirect()->route('register')->withErrors(['login'  => "Invalid email"]);

    //         return (['message' => 'Invalid email/mobile number']);
    //     }

    //     $request->merge([$field => $request->input('login')]);

    //     if($field == "contact"){
    //         $this->validatePhone($request->all())->validate();
    //         // event(new Registered($user = $this->createWithPhone($request)));
    //         // dd(redirect('getMobileVerification', (int)$request->login));
    //         dd((int)$request->login);
    //         return redirect()->route('getMobileVerification', ['phone' => (int)$request->login]);
    //     }else{
    //         $request->request->remove('login');
    //         $this->validateEmail($request->all())->validate();
    //         event(new Registered($user = $this->createWithEmail($request)));
            
    //         $accessToken = $user->createToken('authToken')->accessToken;

    //         // return response(['user' => $user, 'access_token' => $accessToken]);

    //         return redirect()->route('login')->with('error',"We have sent a verification mail to the email you provided");
    //         return $this->registered($request, $user) ?: redirect()->route('getEmailVerification', $request->email);
    //     }
    // }

    protected function validateEmail(array $data)
    {
        return Validator::make($data, [
            'email' => ['string', 
                        'email', 
                        'max:255', 
                        'required',
                        'unique:users'],

            'password' => ['required',
                           'string',
                           'min:8',
                           'max: 30', 
                           'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/', 
                           'confirmed'],
        ]);
    }
    // protected function validatePhone(array $data)
    // {

    //     return Validator::make($data, [
    //         // 'contact' => ['required', 'string', 'unique:contact_information'],
    //         'password' => ['required', 'string', 'min:8', 'confirmed'],
    //     ]);
    // }


    protected function createWithEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if($user){
            return response()->json(['message' => "Email is already in use"],400);
        }
        $user = User::create([
            'email' => $request->email,
            'access_level' => 0,
            'password' => bcrypt($request->password),
            'provider' => null,
            'secret_key' => random_int(100000,999999), //16 characters
            'status' => 0,
        ]);
        $this->createProfile($user->user_id);
        $this->createSecurities($user->user_id);
        $this->createDevice($user->user_id,$request);
        $this->createWallet($user->user_id);
        \Mail::to($user->email)->send(new VerifyMail($user));
        return $user;
    }
    protected function createWithPhone(Request $request)
    {
        $user = User::create([
            'username' => null,
            'password' => bcrypt($request->password),
            'access_level' => 0,
            'verified' => 0,
            'provider' => null,
            // 'secret_key' => SendCode::sendCode($request->login),
            'status' => 0,
        ]);
        // $this->createProfile($user->user_id);
        $this->createSecurities($user->user_id);
        $this->createCI($user->user_id, $request);
        $this->createWallet($user->user_id);

        
        return $user;
    }
    
    protected function createWallet($user_id){
        Wallet::create([
            'wallet_id' => $user_id,
            'user_id' => $user_id,
            'type' => "PHP",
            'balance' => 0.00,
            'created_at' => now(),
        ]);
    }

    protected function createProfile($user_id){
        
        Profile::create([
            'profile_id' => $user_id,
            'user_id' => $user_id,
        ]);
    }
    protected function createSecurities($user_id){
        Securities::create([
            'security_id' => $user_id,
            'user_id' => $user_id,
        ]);
    }
    protected function createDevice($user_id, $data){
        
        $user_agent = $data->header('User-Agent');
        $bname = "";
        $platform ="";

        if (preg_match('/linux/i', $user_agent)) {
            $platform = 'Linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $user_agent)) {
            $platform = 'Mac';
        }
        elseif (preg_match('/windows|win32/i', $user_agent)) {
            $platform = 'Windows';
        }
        if(preg_match('/MSIE/i',$user_agent) && !preg_match('/Opera/i',$user_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Firefox/i',$user_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$user_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$user_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$user_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$user_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        Devices::create([
            'user_id' => $user_id,
            'medium' => $bname,
            'ip' => $data->Ip(),
            'device_type' => '',
            'os' => $platform,
            // 'brand_model' => ,
            'installation_id' => null,
            // 'status' => 0,
            // 'hash' => ,
            'created_at' => now()
        ]);
    }
    
}

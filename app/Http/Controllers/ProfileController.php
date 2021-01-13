<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Securities;
use App\Contact_Information;
use App\Profile;
use App\Http\Resources\UserResource;   

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //View the profile page
    public function show($id){
        $user = User::where('user_id',$id)->first();
        $sid = Securities::where('user_id',$id)->first();
        $cid = Contact_Information::where('user_id',$id)->first();
        $profile = Profile::find($id);
        return view('profile', compact('user', 'sid','cid', 'profile'));
    }
    //Update the user's profile details
    public function update(Request $request, $id){
        $user = User::where('user_id',$id)->first();
        $security = Securities::where('user_id',$id)->first();
        if($request->has('tfa_status')){
            $security->tfa_enabled = 1;
        }else{
            $security->tfa_enabled = 0;
        }
        $security->update();

        $profile = Profile::find($id);
        $profile->first_name = $request->input('first_name');
        $profile->last_name = $request->input('last_name');
        $profile->gender = $request->input('gender');
        $profile->address = $request->input('address');
        $profile->update();
        
        return back()->with('success','Successfully Update Profile');

    }
    public function profile(Request $request){
        $user = $request->user('api');

        // return UserRzesource::collection($user);
        return $user;


    }

}

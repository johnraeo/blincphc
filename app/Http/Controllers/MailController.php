<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MailController extends Controller
{
    public function SendInvite(Request $request){
        $user = auth()->guard('api')->user();
        Mail::to($request->email)->send(new SendInvite($user));
    }

    public function SendReset(Request $request){
        $user = auth()->guard('api')->user();
        Mail::to($request->email)->send(new SendReset($user));
    }

    public function SendInstructions(){
        $user = auth()->guard('api')->user();
        Mail::to($user->email)->send(new SendInstructions($user));
    }

}

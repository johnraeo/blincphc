<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Wallet\Wallet;
use App\Http\Resources\WalletResource;
use App\User;
use App\Mail\SendInvite;
use App\Mail\SendReset;
use App\Mail\SendInstructions;
use Illuminate\Support\Facades\Mail;


class WalletController extends Controller
{
    public function wallet(){
        $user = auth()->guard('api')->user();
        $wallet = Wallet::where('user_id', $user->user_id)->get();

        return WalletResource::collection($wallet);
    }
    public function LinkWallet(Request $request){
        $this->validate($request, [
            'acct_name' => 'required',
            'type' => 'required'
        ]);
        $user = auth()->guard('api')->user();
        if($user){
	    	$wallet = Wallet::create([
		            'user_id' => $user->user_id,
                    'account_name' => $request->acct_name,
		            'type' => $request->type,
		            'balance' => 0.00000,
		            'created_at' => now(),
		        ]);
			return response()->json(['message' => 'BTS Wallet Linked'])->setStatusCode(200);
        }else{
        	return response()->json(['message' => 'Unauthenticated'])->setStatusCode(400);
        }

    }

    public function CheckEmail(Request  $request){
        $user = User::where('email', $request->email)->first();
        if($user){
            return response()->json()->setStatusCode(200);
        }else{
            return response()->json(['message' => 'Email is not registered in Blinc.ph Would you like to invite?'])->setStatusCode(404);

        }
    }

    public function CheckAccount(Request  $request){
        $user = Wallet::where('account_name', $request->account_name)->first();
        if(!$user){
            return response()->json()->setStatusCode(200);
        }else{
            return response()->json(['message' => 'Account name is already taken/linked'])->setStatusCode(404);

        }
    }


}

<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use App\Transactions\Convert;
use App\Transactions\History;
use App\Wallet\Wallet;


class ConvertController extends Controller
{
    public function convert(Request $request){
        $this->validate($request, [
            'account_name' => 'required',
            'amount' => 'required',
            'total_amount' => 'required',
            'convert_from' => 'required',
            'convert_to' => 'required',
            'exchange_rate' => 'required',
            'transaction_fee' => 'required',
            'block_no' => 'required'
            ]);
            
        $user = $request->user('api');
        $user_wallet = Wallet::where('user_id', $user->user_id)
                                    ->where('type', "PHP")->first();
        $convert_from = Wallet::where('user_id', $user->user_id)
                                        ->where('type', $request->convert_from)->first();
        $convert_to = Wallet::where('user_id', $user->user_id)
                                        ->where('type', $request->convert_to)->first();
        $convert = Convert::create([
            'user_id' => $user->user_id,
            'account_name' => $request->account_name,
            'amount_to_convert' => $request->amount,
            'amount_converted' => $request->total_amount,
            'convert_from' => $request->convert_from == "PHP" ? 0: 1,
            'convert_to' => $request->convert_to == "PHP" ? 0: 1,
            'exchange_rate' => $request->exchange_rate,
            'status' => 0,
            'transaction_fee' => $request->transaction_fee,
            'trx_id' => random_int(100000,999999),
            'block_no' => $request->block_no,
        ]);

        $transaction = History::create([
            'user_id' => $user->user_id,
            'participant' => $user->user_id,
            'running_balance' => $user_wallet->balance + $request->amount, 
            'amount' => $request->amount,
            'status' => 0,
            'transact_date' => $convert->created_at,
            'transact_type' => "CF",
            'movement' => 0,
            'transact_type_id' => $convert->convert_id,
        ]);

        if($request->convert_from == "PHP"){
            $user_wallet->balance = $user_wallet->balance - $request->amount;
            $user_wallet->save();
        }else if($request->convert_from == "BTS"){
            $user_wallet->balance = $user_wallet->balance + $request->total_amount;
            $user_wallet->save();
        }


        return response()->json(['message' => $transaction->transact_id])->setStatusCode(200);
        
    }

}
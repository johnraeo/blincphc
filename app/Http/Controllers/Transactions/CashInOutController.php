<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transactions\Cash_In_Out;
use App\Transactions\History;
use App\Wallet\Wallet;


class CashInOutController extends Controller
{
    public function CashInOut(Request $request){
    	$this->validate($request, [
            'amount' => 'required',
            'type' => 'required',
            'meth' => 'required',
            'transaction_fee' => 'required'
        ]);

        $user = $request->user('api');
        $user_wallet = Wallet::where('user_id', $user->user_id)->first();
        if($request->type == 'CO' && $user_wallet->balance <= $request->amount){
             return response()->json(['message' => 'Insufficient Balance'])->setStatusCode(200);
        
        }else if($request->type == "CI"){
            $cio = Cash_In_Out::create([
                'user_id' => $user->user_id,
                'method_id' => $request->meth,
                'amount' => $request->amount,
                'transaction_fee' => $request->transaction_fee,
                'reference' => random_int(10000,99999),
                'transact_type' => "CI",
                'status' => 3,
                // 'memo' => $request->memo ?: '',
            ]);

        }else if($request->type == "CO"){
            $cio = Cash_In_Out::create([
                'user_id' => $user->user_id,
                'method_id' => $request->meth,
                'amount' => $request->amount,
                'transaction_fee' => $request->transaction_fee,
                'reference' => random_int(10000,99999),
                'transact_type' => "CO",
                'status' => 3,
                // 'memo' => $request->memo ?: '',
            ]);
            $user_wallet->balance = $user_wallet->balance - $request->amount;
            $user_wallet->save();
        }
        $transaction = History::create([
            'user_id' => $user->user_id,
            'participant' => $user->user_id,
            'running_balance' => $user_wallet->balance + $request->amount, 
            'amount' => $request->amount,
            'status' => $cio->status,
            'transact_date' => now(),
            'transact_type' => $cio->transact_type,
            'movement' => $cio->transact_type=="CO" ? 1 : 0,
            'transact_type_id' => $cio->cash_in_out_id,

        ]);

        if($request->type == "CI"){
            return response()->json(['message' => $transaction->transact_id])->setStatusCode(200);

        }else if($request->type == "CO"){
            return response()->json(['message' => $transaction->transact_id])->setStatusCode(200);
        }
    }
}

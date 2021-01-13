<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Wallet\Wallet;
use App\Bill\BillerCompany;
use App\Bill\BillsPayment;
use App\Transactions\History;

class BillsPaymentController extends Controller
{
    public function GetComp(){
        return BillerCompany::get();
    }

    public function PayBill(Request $request){
        $this->validate($request, [
            'amount' => 'required',
            'due_date' => 'required',
            'ref_no' => 'required',
            'biller' => 'required',
        ]);
        $user = $request->user('api');
        $user_wallet = Wallet::where('user_id', $user->user_id)->first();
        
        if($user_wallet->balance < $request->amount){
            return response()->json(['message' => 'Insufficient Balance' ])->setStatusCode(400);
        }else{

            $user_wallet = Wallet::where('user_id', $user->user_id)->first();

            $bill_payment = BillsPayment::create([
                'user_id' => $user->user_id,
                'biller_id' => $request->biller,
                'subscriber_name' => $user->email,
                'subscriber_no' => random_int(100000,999999),
                'reference' => $request->ref_no,
                'amount' => $request->amount,
                'memo' => $request->memo ?: '',
                'category' => 1,
                'status' => 3,
                'transaction_fee' => 0,
                'created_at' => now()
            ]);

            $transaction = History::create([
                'user_id' => $user->user_id,
                'participant' => $user->user_id,
                'running_balance' => $user_wallet->balance, 
                'amount' => $request->amount,
                'status' => 3,
                'transact_date' => $bill_payment->created_at,
                'transact_type' => "BP",
                'movement' => 1,
                'transact_type_id' => $bill_payment->bills_payment_id,
            ]);

            return response()->json(['message' => $transaction->transact_id ])->setStatusCode(200);

        }
    }
}
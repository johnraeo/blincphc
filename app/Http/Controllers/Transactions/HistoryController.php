<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Http\Resources\HistoryResource;
use Illuminate\Http\Request;

use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Transactions\History;
use App\Transactions\Send_Req;
use App\Transactions\Cash_In_Out;
use App\Bill\BillsPayment;
use App\Wallet\Wallet;
use App\User;

use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function index()
    {
        $user = auth()->user('api');
        $transactions = History::where('user_id', $user->user_id)
        ->where('status', '!=', 3)
        ->orderby('transact_date', 'DESC')
        ->with('bills_payment')
        // ->with('biller_company')
        ->with('user')
        ->with('convert')
        ->paginate(5);

        return HistoryResource::collection($transactions);
    }
    public function pending(){
        $user = auth()->user('api');
        $transactions = History::where('user_id', $user->user_id)
        ->where('status',3)
        ->orderby('transact_date', 'DESC')
        ->with('bills_payment')
        // ->with('biller_company')
        ->with('user')
        ->get();

        return HistoryResource::collection($transactions);
    }

    public function view(Request $request){
        $transaction = History::where('transact_id', $request->id)->first();
        switch($transaction->transact_type){
            case "SF":
                $send_req = Send_Req::where('send_req_id', $transaction->transact_type_id)->first();
                $type = $send_req->currency_type == 0 ? "PHP" : "BTS";
                $wallet = Wallet::where('user_id', $send_req->user_id)->where('type', $type)->first();
                $transaction = History::where('transact_id', $request->id)
                        ->with('user')
                        ->with('send_req')
                        // ->with('wallet')
                        ->with(["wallet" => function ($query) use ($type){
                            $query->where('type', $type);
                        }])
                        ->first();
                break;

            case "RF":
                $send_req = Send_Req::where('send_req_id', $transaction->transact_type_id)->first();
                $type = $send_req->currency_type == 0 ? "PHP" : "BTS";
                $wallet = Wallet::where('user_id', $send_req->user_id)->where('type', $type)->first();
                $transaction = History::where('transact_id', $request->id)
                        ->with('user')
                        ->with('send_req')
                        // ->with('wallet')
                        ->with(["wallet" => function ($query) use ($type){
                            $query->where('type', $type);
                        }])
                        ->first();
                break;

            case "CI":
                $transaction = History::where('transact_id', $request->id)
                        ->with('user')
                        ->with('cash_in_out')
                        ->first();
                break;

            case "CO":
                $transaction = History::where('transact_id', $request->id)
                        ->with('user')
                        ->with('cash_in_out')
                        ->first();
                break;

            case "CF":
                $transaction = History::where('transact_id', $request->id)
                        ->with('user')
                        ->with('convert')
                        ->first();
                break;

            case "BP":
                $transaction = History::where('transact_id', $request->id)
                        ->with('user')
                        // ->with('biller_company')
                        ->with('bills_payment')
                        ->first();

                break;

            default:
                break;
        }

    	return $transaction;
    }
    public function cancel(Request $request){
        $transaction = History::where('transact_id', $request->id)->first();
        if($transaction){
            if($transaction->status == 0){
                return response()->json(['message' => 'Transaction has already been Processed'])->setStatusCode(400);
            }else{
                if($transaction->transact_type == 'SF' ||
                    $transaction->transact_type == 'RF'){
                    $participant = History::where('user_id', $transaction->participant)->first();
                    $participant->transact_date = now();
                    $participant->status = 4;
                    $participant->save();
                }
                
                $transaction->transact_date = now();
                $transaction->status = 4;
                $transaction->save();

                switch($transaction->transact_type){
                    case "SF":
                        $send_req = Send_Req::where('send_req_id', $transaction->transact_type_id)->first();
                        $send_req->status = 4;
                        $send_req->save;
                        break;

                    case "RF":
                        $send_req = Send_Req::where('send_req_id', $transaction->transact_type_id)->first();
                        $send_req->status = 4;
                        $send_req->save;
                        break;

                    case "CI":
                        $cash_in = Cash_In_Out::where('cash_in_out_id',$transaction->transact_type_id)->first();
                        $cash_in->status = 4;
                        $cash_in->save();
                        break;

                    case "CO":
                        $cash_out = Cash_In_Out::where('cash_in_out_id',$transaction->transact_type_id)->first();
                        $wallet = Wallet::where('wallet_id', $transaction->user_id)->first();
                        $cash_out->status = 4;
                        $wallet->balance = $wallet->balance + $transaction->amount;
                        $wallet->save();
                        break;

                    case "BP":
                        $bills_payment = BillsPayment::where('bills_payment_id',$transaction->transact_type_id)->first();
                        $wallet = Wallet::where('wallet_id', $transaction->user_id)->first();
                        $bills_payment->status = 4;
                        $wallet->balance = $wallet->balance + $transaction->amount;
                        $wallet->save();
                        break;

                }

                return response()->json(['id' => $transaction->transact_id])->setStatusCode(200);
            }
            
        }else{
            return response()->json(['message' => 'Transaction Not Found'])->setStatusCode(404);
        }

    }


    public function getEmail(Request $request){
        $user = User::where('user_id', $request->id)->first();
        return $user->email;
    }
}
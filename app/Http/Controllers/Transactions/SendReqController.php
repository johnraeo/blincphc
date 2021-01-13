<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Wallet\Wallet;
use App\Transactions\Send_Req;
use App\Transactions\History;

class SendReqController extends Controller
{
    public function SendRequest(Request $request){

        $this->validate($request, [
            'senreq' => 'required|email',
            'amount' => 'required',
            'currency' => 'required',
            'type' => 'required',
        ]);

        $sndrUser = $request->user('api');
        $rcvrUser = $this->checkUser($request->senreq);
        $type = $request->currency == 0 ? 'PHP' : 'BTS';
        if(!$rcvrUser){
            return response()->json(['message' => 'an email invitation has been sent to the email provided'])->setStatusCode(404);
        }
        if($sndrUser->email == $rcvrUser->email){
            return response()->json(['message' => 'Unable to send or request funds to yourself'])->setStatusCode(400);
        }

        $sndr_wallet = Wallet::where('user_id', $sndrUser->user_id)
                                        ->where('type', $type)->first();
        $rcvr_wallet = $rcvrUser ? Wallet::where('user_id', $rcvrUser->user_id)
                                        ->where('type', $type)->first() : '';
        if(!$rcvr_wallet){
            return response()->json(['message' => 'User does not have a '.$type." wallet"])->setStatusCode(404);

        }else if($request->type == "SF"){
            if($type == 'BTS' && $request->bts_balance >= $request->amount){
                $send = Send_Req::create([
                    'user_id' => $sndrUser->user_id,
                    'receiver_user_id' => $rcvrUser->user_id,
                    'currency_type' => $request->currency,
                    'transact_type' => $request->type,
                    'amount' => $request->amount,
                    'status' => 0,
                    'memo' => $request->memo ?: null,
                    'transaction_fee' => 0,
                    'trx_id' => random_int(100000,999999),
                    'block_no' => 0,
                ]);
                $sndrTransaction = History::create([
                    'user_id' => $sndrUser->user_id,
                    'participant' => $rcvrUser->user_id,
                    'running_balance' => 0,
                    'running_balance' => $request->bts_balance - $request->amount,
                    'amount' => $request->amount,
                    'status' => 0,
                    'transact_date' => $send->created_at,
                    'transact_type' => $send->transact_type,
                    'movement' => 1,
                    'transact_type_id' => $send->send_req_id,
                ]);

                $rcvrTransaction = History::create([
                    'user_id' => $rcvrUser->user_id,
                    'participant' => $sndrUser->user_id,
                    'running_balance' => 0,
                    'running_balance' => $request->bts_balance + $request->amount,
                    'amount' => $request->amount,
                    'status' => 0,
                    'transact_date' => $send->created_at,
                    'transact_type' => "RF",
                    'movement' => 0,
                    'transact_type_id' => $send->send_req_id,
                ]);
                return response()->json(['message' => $sndrTransaction->transact_id])->setStatusCode(200);

            }else if($sndr_wallet->balance >= $request->amount){
                $send = Send_Req::create([
                    'user_id' => $sndrUser->user_id,
                    'receiver_user_id' => $rcvrUser->user_id,
                    'currency_type' => $request->currency,
                    'transact_type' => $request->type,
                    'amount' => $request->amount,
                    'status' => 0,
                    'memo' => $request->memo ?: null,
                    'transaction_fee' => 0,
                    'trx_id' => random_int(100000,999999),
                    'block_no' => 0,
                ]);

                $sndrTransaction = History::create([
                    'user_id' => $sndrUser->user_id,
                    'participant' => $rcvrUser->user_id,
                    'running_balance' => $sndr_wallet->balance - $request->amount,
                    'amount' => $request->amount,
                    'status' => 0,
                    'transact_date' => $send->created_at,
                    'transact_type' => $send->transact_type,
                    'movement' => 1,
                    'transact_type_id' => $send->send_req_id,
                ]);

                $rcvrTransaction = History::create([
                    'user_id' => $rcvrUser->user_id,
                    'participant' => $sndrUser->user_id,
                    'running_balance' => $rcvr_wallet->balance + $request->amount,
                    'amount' => $request->amount,
                    'status' => 0,
                    'transact_date' => $send->created_at,
                    'transact_type' => "RF",
                    'movement' => 0,
                    'transact_type_id' => $send->send_req_id,
                ]);

                if($rcvr_wallet && $rcvrUser){
                    $sndr_wallet->balance = $sndr_wallet->balance - $request->amount;
                    $sndr_wallet->save();

                    $rcvr_wallet->balance = $rcvr_wallet->balance + $request->amount;
                    $rcvr_wallet->save();

                    return response()->json(['message' => $sndrTransaction->transact_id])->setStatusCode(200);
                }

                }else{
                return response()->json(['message' => 'Insufficient Balance' ])->setStatusCode(400);
            }

        }else if($request->type == "RF"){
            $req = Send_Req::create([
                'user_id' => $sndrUser->user_id,
                'receiver_user_id' => $rcvrUser->user_id,
                'currency_type' => $request->currency,
                'transact_type' => $request->type,
                'amount' => $request->amount,
                'status' => 3,
                'memo' => $request->memo ?: null,
                'transaction_fee' => 0,
                'trx_id' => random_int(100000,999999),
                'block_no' => 0,
            ]);

            $rcvrTransaction = History::create([
                'user_id' => $sndrUser->user_id,
                'participant' => $rcvrUser->user_id,
                'running_balance' => $sndr_wallet->balance,
                'amount' => $request->amount,
                'status' => 3,
                'transact_date' => now(),
                'transact_type' => $request->type,
                'movement' => 0,
                'transact_type_id' => $req->send_req_id
            ]);

            $sndrTransaction = History::create([
                'user_id' => $rcvrUser->user_id,
                'participant' => $sndrUser->user_id,
                'running_balance' => $rcvr_wallet->balance,
                'amount' => $request->amount,
                'status' => 3,
                'transact_date' => now(),
                'transact_type' => "SF",
                'movement' => 1,
                'transact_type_id' => $req->send_req_id
            ]);

            return response()->json(['message' => $rcvrTransaction->transact_id])->setStatusCode(200);

        }
    }

    public function checkUser($request){
        $user ="";
        if(is_numeric($request)){
            if($user = User::where('user_id', $request)->first()){
            }else{
                $contact = Contact_Information::where('contact', $request)->first();
                $user = User::where('user_id', $contact->user_id)->first();
            }
        }else if(filter_var($request, FILTER_VALIDATE_EMAIL)){
            $user = User::where('email', $request)->first();
        }
        return $user;
    }

    public function DenyRequest(Request $request){
        $transaction = History::where('transact_id', $request->id)->first();

        if($transaction){
            if($transaction->status == 4){
                return response()->json(['message' => 'Transaction has already been cancelled by the requester'])->setStatusCode(400);
            }else{
                $participant = History::where('user_id', $transaction->participant)
                ->where('transact_type_id', $transaction->transact_type_id)
                ->first();
                $participant->transact_date = now();
                $participant->status = 1;
                $participant->save();

                $transaction->transact_date = now();
                $transaction->status = 1;
                $transaction->save();

                $send_req = Send_Req::where('send_req_id', $transaction->transact_type_id)->first();
                $send_req->status = 1;
                $send_req->save;

                return response()->json(['message' => $transaction->transact_id])->setStatusCode(200);
            }

        }else{
            return response()->json(['message' => 'Transaction Not Found'])->setStatusCode(404);
        }

    }
    public function AcceptRequest(Request $request){
        $transaction = History::where('transact_id', $request->id)->first();
        if($transaction){
            if($transaction->status == 4){
                return response()->json(['message' => 'Transaction has already been cancelled by the requester'])->setStatusCode(400);
            }else{
                $participant = History::where('user_id', $transaction->participant)
                ->where('transact_type_id', $transaction->transact_type_id)
                ->first();
                $participant->transact_date = now();
                $participant->status = 0;
                $participant->save();

                $transaction->transact_date = now();
                $transaction->status = 0;
                $transaction->save();

                $SenReq = Send_Req::where('send_req_id', $participant->transact_type_id)->first();
                $rcvrWallet = Wallet::where('user_id', $SenReq->receiver_user_id)->first();
                $sndrWallet = Wallet::where('user_id', $SenReq->user_id)->first();

                if($transaction->movement == 0){
                    $rcvrWallet->balance = $rcvrWallet->balance + $SenReq->amount;
                    $rcvrWallet->save();
                    $sndrWallet->balance = $sndrWallet->balance - $SenReq->amount;
                    $sndrWallet->save();
                }else{
                    $rcvrWallet->balance = $rcvrWallet->balance - $SenReq->amount;
                    $rcvrWallet->save();
                    $sndrWallet->balance = $sndrWallet->balance + $SenReq->amount;
                    $sndrWallet->save();
                }

                return response()->json(['message' => $transaction->transact_id])->setStatusCode(200);
            }

        }else{
            return response()->json(['message' => 'Transaction Not Found'])->setStatusCode(404);
        }

    }

}

<?php

namespace App\Transactions;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    public $table = "transactions";
    protected $primaryKey = 'transact_id';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User', 'participant');
    }
    public function send_req()
    {
        return $this->belongsTo('App\Transactions\Send_Req', 'transact_type_id');
    }
    public function cash_in_out()
    {
        return $this->belongsTo('App\Transactions\Cash_In_Out' , 'transact_type_id');
    }
    public function convert()
    {
        return $this->belongsTo('App\Transactions\Convert', 'transact_type_id');
    }
    public function company()
    {
        return $this->belongsTo('App\Company', 'transact_type_id');
    }
    public  function biller_company()
    {
        return $this->hasManyThrough('App\Bill\BillerCompany', 'App\Bill\BillsPayment', 'bills_payment_id', 'biller_id', 'transact_type_id');

    }
    public function bills_payment()
    {
        return $this->belongsTo('App\Bill\BillsPayment', 'transact_type_id');
    }
    public function wallet()
    {
        return $this->hasManyThrough('App\Wallet\Wallet', 'App\User', 'user_id', 'user_id', 'participant');
    }



    protected $fillable = [
        'user_id',
        'participant',
        'running_balance',
        'amount',
        'status',
        'transact_date',
        'updated_at',
        'transact_type',
        'movement',
        'transact_type_id',
    ];
}

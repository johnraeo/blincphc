<?php

namespace App\Transactions;

use Illuminate\Database\Eloquent\Model;

class Send_Req extends Model
{
    public $table = "send_req";
    protected $primaryKey = 'send_req_id';
    public $timestamps = true;
    
    public function user()
    {
    	return $this->belongsTo('App\User');
    }
    public function history()
    {
        return $this->hasMany('App\Transactions\History', 'send_req_id');
    }
    protected $fillable = [
        'user_id',
        'receiver_user_id',
        'currency_type',
        'transact_type',
        'amount',
        'status',
        'memo',
        'transaction_fee',
        'trx_id',
        'block_no',
    ];
}

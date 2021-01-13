<?php

namespace App\Transactions;

use Illuminate\Database\Eloquent\Model;

class Send_Req_External extends Model
{
    public $table = "send_req_external";
    protected $primaryKey = 'send_req_external_id';
    public $timestamps = false;

    public function Send_Req()
    {
    	return $this->belongsTo('App\Transactions\Send_Req');
    }

    protected $fillable = [
        'send_req_external_id',
        'account_name',
        'send_req_id',
    ];
}

<?php

namespace App\Wallet;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = "wallet";
    protected $primaryKey = 'wallet_id';
    public $timestamps = true;

    public function user()
    {
    	return $this->belongsTo('App\User');
    }
    public function utility_payment()
    {
        return $this->hasMany('App\Wallet\Utility_payment');
    }
    public function request_fund()
    {
        return $this->hasMany('App\Wallet\Request_Fund');
    }
    public function send_fund()
    {
        return $this->hasMany('App\Wallet\Send_Fund');
    }
    public function cash_in()
    {
        return $this->hasMany('App\Wallet\Cash_In');
    }
    public function cash_out()
    {
        return $this->hasMany('App\Wallet\Cash_Out');
    }
    public function history()
    {
        return $this->belongsTo('App\Transactions\History');
    }

    protected $fillable = [
        'user_id',
        'account_name',
        'type',
        'balance',
    ];
}

<?php

namespace App\Transactions;

use Illuminate\Database\Eloquent\Model;

class cash_in_out extends Model
{
    public $table = "cash_in_out";
    protected $primaryKey = 'cash_in_out_id';
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function transfer_method()
    {
        return $this->belongsTo('App\Transactions\Transfer_Method','user_id');
    }

    public function history()
    {
        return $this->hasMany('App\Transactions\History','transact_id');
    }

    protected $fillable = [
        'user_id',
        'method_id',
        'amount',
        'transaction_fee',
        'reference',
        'transact_type',
        'status',
        'expires_at',
    ];
}

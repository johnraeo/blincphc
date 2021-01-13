<?php

namespace App\Transactions;

use Illuminate\Database\Eloquent\Model;

class Convert extends Model
{
    public $table = "convert";
    protected $primaryKey = 'convert_id';
    public $timestamps = true;

    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function history()
    {
        return $this->hasMany('App\Transactions\History');
    }
    protected $fillable = [
        'user_id',
        'account_name',
        'amount_to_convert',
        'amount_converted',
        'convert_from',
        'convert_to',
        'exchange_rate',
        'status',
        'transaction_fee',
        'trx_id',
        'block_no',
    ];
}

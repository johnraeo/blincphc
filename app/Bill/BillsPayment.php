<?php

namespace App\Bill;

use Illuminate\Database\Eloquent\Model;

class BillsPayment extends Model
{
    public $table = "bills_payment";
    protected $primaryKey = 'bills_payment_id';
    public $timestamps = true;

    
    public function user()
    {
    	return $this->belongsTo('App\User');
    }
    public function biller_company()
    {
    	return $this->belongsTo('App\Bill\BillerCompany');
    }
    public function history()
    {
        return $this->belongsTo('App\Bill\BillsPayment', 'bills_payment_id');
    }
 
    protected $fillable = [
        'user_id',
        'biller_id',
        'subscriber_name',
        'subscriber_no',
        'reference',
        'amount',
        'memo',
        'status',
        'transaction_fee',
        'created_at',
    ];
}

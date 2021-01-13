<?php

namespace App\Transactions;

use Illuminate\Database\Eloquent\Model;

class Transfer_Method extends Model
{
    public $table = "cash_transfer_method";
    protected $primaryKey = 'method_id';
    
    public function company()
    {
    	return $this->belongsTo('App\Company');
    }
    public function cash_in_out()
    {
    	return $this->hasMany('App\Transactions\Cash_In_Out');
    }

    protected $fillable = [
        'method_id',
        'method_type',
        'company_id',
        'cash_in_support',
        'cash_out_support',
        'cash_in_availability',	
        'cash_out_availability',
    ];
}

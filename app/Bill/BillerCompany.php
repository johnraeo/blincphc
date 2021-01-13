<?php

namespace App\Bill;

use Illuminate\Database\Eloquent\Model;

class BillerCompany extends Model
{
    public $table = "biller_company";
    protected $primaryKey = 'biller_id';
    public $timestamps = true;

    
    public function bills_payment()
    {
        return $this->hasOne('App\Bill\BillsPayment');
    }
    public function history()
    {
        return $this->hasOne('App\Transactions\History', 'biller_id');
    }
    protected $fillable = [
        'company_name',
        'image',
        'reference',
        'category',
        'status',
    ];
}

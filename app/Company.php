<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public $table = "company";
    protected $primaryKey = 'company_id';
    
    public function transfer_method()
    {
    	return $this->hasMany('App\Transactions\Transfer_Method');
    }
    public function history()
    {
        return $this->hasMany('App\Transactions\History', 'user_id');
    }

    protected $fillable = [
        'company_name',
        'image',
        'status',
        'region',
    ];
}

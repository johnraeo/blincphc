<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Otps extends Model
{
    public $table = "otps";
    protected $primaryKey = 'otp_id';
    public $timestamps = false;
    
    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    protected $fillable = [
        'otp_id', 
        'user_id',
        'otp_data',
        'created_at'
    ];
}

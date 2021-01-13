<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Securities extends Model
{
    public $table = "securities";
    protected $primaryKey = 'security_id';
    public $timestamps = false;

    public function user()
    {
    	return $this->belongsTo('App\User', 'user_id');
    }
    
    protected $fillable = [
        'security_id', 
        'user_id',
        'is_private',
        'otp_enabled',
        'otp_secret',
        'tfa_enabled',
        'mpin_enabled',
        'max_trials',
    ];
}

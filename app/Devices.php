<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devices extends Model
{
    protected $primaryKey = 'device_id';
    public $timestamps = true;

    public function user()
    {
    	return $this->belongsTo('App\User');
    }
    protected $fillable = [
        'device_id',
        'user_id',
        'medium',
        'ip',
        'device_type',
        'created_at',
        'expires_at',
    ];
}

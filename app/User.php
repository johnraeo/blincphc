<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    protected $primaryKey = 'user_id';

    public function profile()
    {
        return $this->hasOne('App\Profile');
    }
    public function wallet()
    {
        return $this->hasOne('App\Wallet\Wallet');
    }

    public function securities()
    {
        return $this->hasOne('App\Securities');
    }
    public function otps()
    {
        return $this->hasOne('App\Otps');
    }

    public function devices()
    {
        return $this->hasMany('App\Devices');
    }
    public function cash_in_out()
    {
        return $this->hasMany('App\Transactions\Cash_In_Out');
    }
    public function send_req()
    {
        return $this->hasMany('App\Transactions\Send_Req');
    }
    public function convert()
    {
        return $this->hasMany('App\Transactions\Convert');
    }   
    public function bills_payment()
    {
        return $this->hasMany('App\Bill\BillsPayment');
    }
    public function history()
    {
        return $this->hasMany('App\Transactions\History', 'user_id');
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'access_level',
        'password',
        'email_verified',
        'provider',
        'secret_key',
        'created_at',
        'updated_at',
        'deleted_at',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified' => 'datetime',
    ];
}

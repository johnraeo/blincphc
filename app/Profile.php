<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    public $table = "profile";
    protected $primaryKey = 'profile_id';
    public $timestamps = true;
    
    public function user()
    {
    	return $this->belongsTo('App\User');
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profile_id',
        'user_id',
        'first_name',
        'last_name',
        'gender',
        'birthdate',
        'address',
        'user_img',
        'contact_number',
        'address',
        'updated_at'

    ];
}

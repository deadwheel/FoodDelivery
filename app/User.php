<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Laravel\Passport\HasApiTokens;
class User extends Authenticatable
{
    use Notifiable, HasApiTokens, EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
	 protected $table = "users";
	 
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function details(){

        return $this->hasOne('App\UserDetails');
    }

    public function orders() {

        return $this->hasMany('App\Order');

    }

}

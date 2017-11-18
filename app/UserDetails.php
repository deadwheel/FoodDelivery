<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    protected $table = "user_details";

    protected $fillable = [
        'firstname', 'lastname', 'address', 'postcode', 'city', 'phonenumber',
    ];

    protected $visible = [
        'firstname', 'lastname', 'address', 'postcode', 'city', 'phonenumber',
    ];

    public function users() {

        return $this->belongsTo('App\User', 'user_id');
    }

}

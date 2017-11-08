<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    protected $table = "user_details";
	
	public function users(){
		
		return $this->belongsTo('App\User','user_id');
		
	}
}

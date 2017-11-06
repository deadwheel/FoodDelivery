<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query;
class Offer extends Model
{
    protected $table = 'offers';
	
	
	 public function orders(){
		
	     return $this->belongsToMany('App\Order'); 

		
	}
}

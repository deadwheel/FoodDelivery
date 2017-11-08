<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $table = 'orders';
	//protected $guarded = [];
		
    public function offers(){
		
        return $this->belongsToMany('App\Offer','order_offer','order_id','offer_id')->withPivot('order_id','offer_id','quantity'); 
		
		
	}
	

}

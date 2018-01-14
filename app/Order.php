<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $table = 'orders';
	///protected $guarded = [];

    protected $visible = ['id','det','address', 'price', 'deliverer_id'];
		
    public function offers(){

        return $this->belongsToMany('App\Offer','orrderoffer','order_id','offer_id')->withPivot('order_id','offer_id','quantity');
		
		
	}

    public function users() {

        return $this->belongsTo('App\User', 'user_id');
    }
	
	
	public function payment(){
		
		return $this->hasOne('App\Payment','order_id');
		
	}
	
	
	public function Rdriver(){
		
		return $this->hasOne('App\Driver','order_id');
		
	}

}

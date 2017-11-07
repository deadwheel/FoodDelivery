<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderOffer extends Pivot
{
    protected $table = 'order_offer';
	//protected $guarded = [];
	//protected $fillable = ['id','offer_id', 'order_id', 'quantity', 'created_at', 'updated_at'];
  

}

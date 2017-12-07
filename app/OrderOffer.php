<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderOffer extends Pivot
{
    protected $table = 'orrderoffer';
    protected $visible = ['offer_id', 'quantity'];
	//protected $guarded = [];
	//protected $fillable = ['id','offer_id', 'order_id', 'quantity', 'created_at', 'updated_at'];
  

}

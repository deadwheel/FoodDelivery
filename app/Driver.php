<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query;
class Driver extends Model
{
    protected $table = 'order_driver';
	protected $fillable = ['order_id','deliverer_id','driver_loc'];
	public $timestamps = false;
	

}

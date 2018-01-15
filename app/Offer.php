<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query;
class Offer extends Model
{
    protected $table = 'offers';
	public $timestamps = false;
	protected $fillable = ['name','price','description'];
	protected $visible = ['name', 'price', 'description', 'image'];
	
	public function orders(){
		
	     return $this->belongsToMany('App\Order'); 

		
	}
}

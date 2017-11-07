<?php

namespace App\Http\Controllers\api;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Order;
use App\OrderOffer;


class OrderController extends Controller
{
    public function create(Request $request) {
		
		
		   $area = json_decode($request->getContent(), true);
		 
		   
		  
			
			$zamowienie = new Order;
			$zamowienie->user_id = Auth::id();
			
			$zamowienie->save();
						
			$zamowienie->offers()->createMany($area['order_details']);
			
			$licznik = 0;
			
			foreach($zamowienie->offers()->get() as $offer){
				
				$zamowienie->offers()->updateExistingPivot($offer->id, ['quantity' => $area['order_details'][$licznik]['order_offer.quantity']]);
				
				$licznik++;
			
			}
			

			
			
			
		
		
		
		return response()->json(['offer'=>$zamowienie->offers()->get(),'data' => $area['order']], 200, [], JSON_NUMERIC_CHECK);
		
	} 
}

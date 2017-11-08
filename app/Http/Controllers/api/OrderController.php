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
		 
		   
		   $licznik = 0;
			
			$zamowienie = new Order;
			$zamowienie->user_id = Auth::id();
			
			$zamowienie->save();
			
			$id_order = $zamowienie->id;
			
			foreach($area['order_details'] as $values)
            {
				$area['order_details'][$licznik]['order_id'] = $id_order;
				$licznik++;
			}
			// Jak da sie dopisac do tablic order_id w jakis prostszy sposob to mozesz przerobic tego foreacha
			
			
			print_r($area['order_details']);
			
			
			for ($i = 0; $i <= 1; $i++) {
				$comment = new OrderOffer($area['order_details'][$i]);
				$comment->save(); // blad Cannot add or update a child row: a foreign key constraint fails 
			}
			
			// Zrob tak by zamiast tego fora wyzej dalo sie uzyc
			// $zamowienie->offers()->createMany($area['order_details']);
			// bo poki co to wywala blad attribute mass assignment
			
			
			
			
		
		
		
		return response()->json(['data' => $area['order']], 200, [], JSON_NUMERIC_CHECK);
		
	} 
}
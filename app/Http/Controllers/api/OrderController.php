<?php
namespace App\Http\Controllers\api;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Order;
use App\OrderOffer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
 
	public function create(Request $request) {
				
				
		
		   $area = json_decode($request->getContent(), true);
			
	 
		   $validator = Validator::make($area, [
				
				'order_details.*.offer_id' => 'required|exists:offers,id',
				'order_details.*.quantity' => 'required|integer',
				'order_address.*.isprofile' =>'required|boolean'
	
			]);
			

		   if ($validator->fails()) {
			   
				return response()->json(['error' => $validator->errors()], 200, [], JSON_NUMERIC_CHECK);
			
			}

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
			
			
			for ($i = 0; $i <= 1; $i++) {
				
				$comment = new OrderOffer($area['order_details'][$i]);
				$comment->save();
				
			}
			
			
			
			
		
		
		
		return response()->json(['data' => $area['order_details']], 200, [], JSON_NUMERIC_CHECK);
		
	} 

}
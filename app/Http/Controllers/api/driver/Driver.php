<?php

namespace App\Http\Controllers\api\driver;

use App\UserDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use Auth;
use App\User;
use Config;
use App\OrderOffer;
use App\Offer;
use Validator;

class Driver extends Controller
{


    public function get_orders_by_id($active = null) {


			if($active == "active") {
				
				
			$orders = Order::where('state', Config::get('constants.driver_OMW'))->whereHas('Rdriver', function($q)
			{
				$q->where('deliverer_id', Auth::id());

			})->first();
			
			//where('deliverer_id', Auth::id())->
			
					if($orders) {
			
						if($orders->is_optional_address == 1) {

							$location = $orders->location;
						}

						else {

							$user_get = User::findOrFail(Auth::id())->with("details")->first();
							$location = $user_get->details->address.", ".$user_get->details->postcode." ".$user_get->details->city;

						}

						$orders["address"] = $location;



						$details = OrderOffer::where('order_id', $orders->id)->get()->toArray();

						$price = 0.0;

						foreach ($details as $key2 => $value2) {

							$details[$key2]["offer_det"] = Offer::find($value2['offer_id'])->toArray();
							$price += $value2['quantity'] * $details[$key2]["offer_det"]["price"];

						}

						$orders["det"] = $details;
						$orders["price"] = $price;
						
						
					}
			
			}
			
			else {
            
			
				//$orders = Order::where('deliverer_id', Auth::id())->where('state', Config::get('constants.driver_ready_to_go'))->get();
				
				$orders = Order::where('state', Config::get('constants.driver_ready_to_go'))->whereHas('Rdriver', function($q)
				{
					$q->where('deliverer_id', Auth::id());

				})->get();
				
				
					foreach ($orders as $key => $value) {

						if($value['is_optional_address'] == true) {

							$location = $value['location'];
						}

						else {

							$user_get = User::findOrFail(Auth::id())->with("details")->first();
							$location = $user_get->details->address.", ".$user_get->details->postcode." ".$user_get->details->city;

						}

						$orders[$key]["address"] = $location;



						$details = OrderOffer::where('order_id', $value['id'])->get()->toArray();

						$price = 0.0;

						foreach ($details as $key2 => $value2) {

							$details[$key2]["offer_det"] = Offer::find($value2['offer_id'])->toArray();
							$price += $value2['quantity'] * $details[$key2]["offer_det"]["price"];

						}

						$orders[$key]["det"] = $details;
						$orders[$key]["price"] = $price;

				}
			
			
			}




            return response()->json(['data' => $orders], 200);


    }


    public function take_it(Request $request, $id) {


        $order = Order::findOrFail($id);
		
		//dd($order->Rdriver);

        if(!is_null($order->Rdriver) && Auth::id() == $order->Rdriver->deliverer_id) {

            $order->state = Config::get('constants.driver_OMW');
            $order->save();

            return response()->json(['success' => 'success'], 200);

        }

        else {

            return response()->json(['error' => 'Error'], 401);

        }


    }

    public function status_delivered(Request $request, $id) {

        $order = Order::findOrFail($id);

        if(!is_null($order->Rdriver) && Auth::id() == $order->Rdriver->deliverer_id) {

            $order->state = Config::get('constants.order_delivered');
            $order->save();

            return response()->json(['success' => 'success'], 200);


        }

        else {

            return response()->json(['error' => 'Error'], 401);

        }
    }
	
	
	
	public function cancel_it(Request $request, $id) {

        $order = Order::findOrFail($id);

        if(!is_null($order->Rdriver) && Auth::id() == $order->Rdriver->deliverer_id) {

            $order->state = Config::get('constants.driver_ready_to_go');
            $order->save();

            return response()->json(['success' => 'success'], 200);


        }

        else {

            return response()->json(['error' => 'Error'], 401);

        }
    }
	
	

    public function update_position(Request $request, $id) {

        Validator::make($request->all(), [

            'position' => 'required|string'

        ])->validate();
		
        $order = Order::findOrFail($id);
		
        if(!is_null($order->Rdriver) && Auth::id() == $order->Rdriver->deliverer_id) {
		
		
			//dd($order->Rdriver);
            $order->Rdriver->driver_loc = $request->position;
            $order->Rdriver->save();

            return response()->json(['success' => 'success'], 200);


        }

        else {

            return response()->json(['error' => 'Error'], 401);

        }

    }
	
	
	public function get_active(Request $request) {
		
		
		
		$order = Order::where('deliverer_id', Auth::id())->where('state', Config::get('constants.driver_OMW'))->first();

		if($order) {
			
			return response()->json(['success' => 'true'], 200);
			
		}
		
		else {
			
			return response()->json(['success' => 'false'], 200);
			
		}
		
	}


}

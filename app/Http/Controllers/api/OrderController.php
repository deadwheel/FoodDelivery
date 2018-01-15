<?php
namespace App\Http\Controllers\api;
use App\Offer;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Order;
use App\OrderOffer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\User;
use PayPal\Api\Payment;
use App\Payment as pay;
use Config;
use GuzzleHttp\Client;
use App\UserDetails;

class OrderController extends Controller
{
 
 		public function index(Request $request){
			
		$orders = Order::where("user_id",Auth::id())->with("offers")->with("Rdriver")->get();
			
		if($orders!=null){
																	
			$json = [];
			
						
			foreach($orders as $order){
			
			
			 $offers = [];
			 $stdC = new \stdClass;
			 $stdC->id = $order->id;
			 
			 if($order->is_optional_address) {
				 
				  $stdC->location = $order->location;
				 
			 }
			 
			 else {
				 
				$user_get = User::findOrFail(Auth::id())->with("details")->first();
				$stdC->location = $user_get->details->address.", ".$user_get->details->postcode." ".$user_get->details->city;
				 
			 }
			 
			 
			 $loc = "";
			 if(!is_null($order->Rdriver)) {
				 $loc = $order->Rdriver->driver_loc;
			 }
			 
			 $stdC->driver_loc = $loc;
			 $stdC->status = $order->state;
			 $stdC->created_at = $order->created_at;
			 //$stdC->offers = $order->offers;

			 foreach($order->offers as $p){			 
			 	
				$quantity["quantity"] = $p->pivot->quantity;
			 	$offers[] = array_merge($p->toArray(), $quantity);
						 	 
			}
			
			 $stdC->offers = $offers;
			 
			 $json[] = $stdC;
			 			
			}
					
		
				return response()->json(["data"=>$json], 200);
			}else
				return response()->json(['error' => 'error'], '401');
	}
 
 
	public function create(Request $request) {

		
       $area = json_decode($request->getContent(), true);

        Validator::make($area, [

            'order_details.*.offer_id' => 'required|exists:offers,id',
            'order_details.*.quantity' => 'required|integer',
            'order_address.isprofile' =>'required|boolean'

        ])->validate();
		
		$location = "";
		$payment = $this->verify($area['payment_details']['paymentId'], json_decode($area['payment_details']['payment_client']));



		if($area['order_address']['isprofile']){
			
				$opcja = false;
				
				
			}else {
				
					$opcja = true;
					$location = $area['order_address']['opt_address'];	
			}				
			
        $zamowienie = new Order;
        $zamowienie->user_id = Auth::id();
		$zamowienie->is_optional_address = $opcja;
		$zamowienie->location = $location;
			
			
			   
        
		  $zamowienie->state =  Config::get('constants.order_paid');      
        $zamowienie->save();
		
		$paym = new pay();
		$paym->paypalPaymentId = $payment->payment->getId();
		$paym->create_time = $payment->payment->getCreateTime();
		//$paym->update_time = $payment->payment->getUpdateTime();
		$paym->state = $payment->payment->getState();
		$paym->amount = $payment->amount_server;
		$paym->currency = $payment->currency_server;		
       
			
      	$zamowienie->payment()->save($paym);
				

        $user_det = [];


        foreach($area['order_details'] as $key => $values) {

            $offer_id =	$values['offer_id'];
            $user_det[$offer_id] = ['quantity' => $values['quantity']];

        }


        $zamowienie->offers()->sync($user_det);

		
		return response()->json(['data' => $area['order_details']], 200, [], JSON_NUMERIC_CHECK);
		
	}


	public function orders_list_user($id) {

	    if(Auth::id() == $id) {

            $user = User::find(Auth::id())->orders()->get();

            foreach ($user as $key => $value) {

                $details = OrderOffer::where('order_id', $value['id'])->get()->toArray();

                foreach ($details as $key2 => $value2) {

                    $details[$key2]["offer_det"] = Offer::find($value2['offer_id'])->toArray();

                }

                $user[$key]["det"] = $details;

            }


            return response()->json(['data' => $user], 200);

        }

        else {

            return response()->json(['error' => 'error'], '401');

        }

    }

	private function verify($payment_id, $payment_client){
		
		 $response["error"] = false;
         $response["message"] = "Payment verified successfully";
        			
		
            try {
     
				$paymentId = $payment_id;
							
                $payment_client = $payment_client;
				
			    $apiContext = new \PayPal\Rest\ApiContext(
                    
						new \PayPal\Auth\OAuthTokenCredential(
                        'AQP1-J5EhPqaqT3MCnzPW-zt9IFE8Cm8GTytaayY11DYMkMmoDTIFeIRKzRexDtfgmiwW2nMcrvGwlD6', // ClientID
                        'EJ5tnuxfZ_sa5FpUcomBUJF9cHkCEKC5tQxZYBLkFW0sCFBY0BeWCBirSNJzUZbne7uwKSJs7K_3f7E_'      // ClientSecret
                        )
                );
 
                // Gettin payment details by making call to paypal rest api
                $payment = Payment::get($paymentId, $apiContext);
 
                // Verifying the state approved
                if ($payment->getState() != 'approved') {
                    $response["error"] = true;
                    $response["message"] = "Payment has not been verified. Status is " . $payment->getState();
                    return response()->json(['error' => $response], 200, [], JSON_NUMERIC_CHECK);
				  }
   
                // Amount on client side
                $amount_client = $payment_client->amount;
 
                // Currency on client side
                $currency_client = $payment_client->currency_code;
  
                // Paypal transactions
                $transaction = $payment->getTransactions()[0];
                // Amount on server side
                $amount_server = $transaction->getAmount()->getTotal();
                // Currency on server side
                $currency_server = $transaction->getAmount()->getCurrency();
				
				$pay = new \stdClass;
				$pay->payment = $payment;
				$pay->amount_server  = $amount_server;
				$pay->currency_server=$currency_server;
				
                $sale_state = $transaction->getRelatedResources()[0]->getSale()->getState();
 
          				
                // Verifying the amount
                if ($amount_server != $amount_client) {
                    $response["error"] = true;
                    $response["message"] = "Payment amount doesn't matched.";
					return response()->json(['error' => $response], 200, [], JSON_NUMERIC_CHECK);
                }
 
                // Verifying the currency
                if ($currency_server != $currency_client) {
                    $response["error"] = true;
                    $response["message"] = "Payment currency doesn't matched.";
                    return response()->json(['error' => $response], 200, [], JSON_NUMERIC_CHECK);
                }
 
                // Verifying the sale state
                if ($sale_state != 'completed') {
                    $response["error"] = true;
                    $response["message"] = "Sale not completed";
                    return response()->json(['error' => $response], 200, [], JSON_NUMERIC_CHECK);
                }
             
			 
				return $pay;
			 
            } catch (\PayPal\Exception\PayPalConnectionException $exc) {
                if ($exc->getCode() == 404) {
                    $response["error"] = true;
                    $response["message"] = "Payment not found!";
                 return response()->json(['error' => $response], 200, [], JSON_NUMERIC_CHECK);
                } else {
                    $response["error"] = true;
                    $response["message"] = "Unknown error occurred!" . $exc->getMessage();
                  return response()->json(['error' => $response], 200, [], JSON_NUMERIC_CHECK);
                }
            } catch (Exception $exc) {
                $response["error"] = true;
                $response["message"] = "Unknown error occurred!" . $exc->getMessage();
                return response()->json(['error' => $response], 200, [], JSON_NUMERIC_CHECK);
            }
				
		
	}



	public function get_status_order($id) {

	    $order = Order::findOrFail($id);

	    if(Auth::id() != $order->user_id) {

            return response()->json(['status' => 'error', 'message' => 'Wrong order id'], 401, [], JSON_NUMERIC_CHECK);

        }

        else {


            if ($order->state == Config::get('constants.order_paid')) {

                return response()->json(['status' => 'ok', 'message' => 'W trakcie realizacji'], 200, [], JSON_NUMERIC_CHECK);

            }

            else if($order->state == Config::get('constants.driver_ready_to_go')) {

                return response()->json(['status' => 'ok', 'message' => 'Twoje zamowienie jest juz u kierowcy'], 200, [], JSON_NUMERIC_CHECK);

            }

            else if($order->state == Config::get('constants.driver_OMW')) {


                //TODO make somewhere config variable api key
                //TODO make location user details !DONE!

                if(!is_null($order->Rdriver) &&  !empty($order->Rdriver->driver_loc)) {
					
					$user_loc = "";
					
					if($order->is_optional_address) {
						
						$user_loc = $order->location;
						
					}
					
					else {
						
						$user_get = User::findOrFail(Auth::id())->with("details")->first();
						$user_loc = $user_get->details->address.", ".$user_get->details->postcode." ".$user_get->details->city;
						
					}
					

                    $client = new Client();
                    $response = $client->request('GET', 'https://maps.googleapis.com/maps/api/distancematrix/json', [
                        'verify' => false,
                        'query' => [

                            'origins' => $order->Rdriver->driver_loc,
                            'destinations' => $user_loc,
                            'language' => 'pl',
                            'key' => 'AIzaSyDi9M1ZBjISCVryKPJkwjjT1LjjtY38Q4c'

                        ]
                    ]);

                    if ($response->getStatusCode() == 200) {

                        $body = json_decode($response->getBody(), true);

                        if ($body["status"] == "OK" && $body["rows"][0]["elements"][0]["status"] == "OK") {


                            return response()->json(['status' => 'ok', 'message' => 'Kierowca juz jedzie do ciebie i bedzie za okolo ' . $body["rows"][0]["elements"][0]["duration"]["text"] . ''], 200, [], JSON_NUMERIC_CHECK);

                        }

                        else {

                            return response()->json(['status' => 'ok', 'message' => 'Kierowca juz jedzie do ciebie'], 200, [], JSON_NUMERIC_CHECK);

                        }

                    }

                    else {

                        return response()->json(['status' => 'ok', 'message' => 'Kierowca juz jedzie do ciebie'], 200, [], JSON_NUMERIC_CHECK);

                    }

                }


                else {

                    return response()->json(['status' => 'ok', 'message' => 'Kierowca juz jedzie do ciebie'], 200, [], JSON_NUMERIC_CHECK);

                }

            }

            else if($order->state == Config::get('constants.order_delivered')) {

                return response()->json(['status' => 'ok', 'message' => 'Dostarczono'], 200, [], JSON_NUMERIC_CHECK);


            }

            else {

                return response()->json(['status' => 'error', 'message' => 'Undefined status'], 200, [], JSON_NUMERIC_CHECK);

            }



        }


    }
	
}
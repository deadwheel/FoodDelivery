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

class OrderController extends Controller
{
 
 		public function index(Request $request){
			
		$orders = Order::where("user_id",$request->user()->id)->with("offers")->get();
			
		if($orders!=null){
																	
			$json = [];		
						
			foreach($orders as $order){
			
			 $stdC = new \stdClass;
			 $stdC->id = $order->id;
			 $stdC->location = $order->location;
			 $stdC->driver_loc = $order->driver_loc;
			 $stdC->status = $order->status;
			 $stdC->offers = $order->offers;
			 
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
		
		$payment = $this->verify($area['payment_details']['paymentId'], json_decode($area['payment_details']['payment_client']));

        $zamowienie = new Order;
        $zamowienie->location = $area['order_address']['opt_address'];
        $zamowienie->is_optional_address = $area['order_address']['isprofile'];
        $zamowienie->user_id = Auth::id();
        $zamowienie->save();
		
		$paym = new pay();
		$paym->paypalPaymentId = $payment->payment->getId();
		$paym->create_time = $payment->payment->getCreateTime();
		$paym->update_time = $payment->payment->getUpdateTime();
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
	
}
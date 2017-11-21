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

class OrderController extends Controller
{
 
	public function create(Request $request) {

		
       $area = json_decode($request->getContent(), true);


        Validator::make($area, [

            'order_details.*.offer_id' => 'required|exists:offers,id',
            'order_details.*.quantity' => 'required|integer',
            'order_address.isprofile' =>'required|boolean'

        ])->validate();

        $zamowienie = new Order;
        $zamowienie->user_id = Auth::id();
        $zamowienie->save();

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

}
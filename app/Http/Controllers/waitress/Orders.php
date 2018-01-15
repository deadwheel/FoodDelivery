<?php

namespace App\Http\Controllers\waitress;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\Role;
use App\User;
use Config;
use App\Driver;

class Orders extends Controller
{

    public function test(Request $request) {

        if(!empty($request->ids)) {

            foreach ($request->ids as $item) {

                $order = Order::findOrFail($item);
					
					
                    if(!is_null($request->drivers[$item])) {
						
						$driv = Driver::updateOrCreate(
							['order_id' => $order->id],
							['deliverer_id' => $request->drivers[$item],'driver_loc' => '']
						);
						
                        $order->state = Config::get('constants.driver_ready_to_go');
                    }

                    else {
						
						$zp = Driver::where("order_id", $order->id)->first();
						if(!is_null($zp)) {
						
							$zp->delete();
						
						}
						
                        $order->state = Config::get('constants.order_paid');
                    }


                    $order->save();

            }


        }

        return redirect('waitress/orders')->with("success", "Edited");

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('offers')->with('Rdriver')->get();

        $drivers = User::whereHas('roles', function($q){
            $q->where('name', 'driver');
        })->get();


    return view('waitress.orders')->with('orders',$orders)->with('drivers',$drivers);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


}

<?php

namespace App\Http\Controllers\waitress;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\Role;
use App\User;
use Config;

class Orders extends Controller
{

    public function test(Request $request) {

        if(!empty($request->ids)) {

            foreach ($request->ids as $item) {

                $order = Order::findOrFail($item);
                if($order->deliverer_id != $request->drivers[$item]) {
					
					
					dd($order->Rdriver);

                    //$order->deliverer_id = $request->drivers[$item];

                    //if(!is_null($request->drivers[$item])) {
                        $order->state = Config::get('constants.driver_ready_to_go');
                    //}

                    //else {
                       // $order->state = null;
                    //}

                    $order->save();

                }
                //echo $request->drivers[$item];

            }


        }

        return redirect('waitress/orders')->with("success", "Edited");
        //dd($request->all());

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('offers')->get();
        //$drivers = Role::with('users')->where('name', 'driver')->get();

        $drivers = User::whereHas('roles', function($q){
            $q->where('name', 'driver');
        })->get();

        //dd($drivers);

/*        foreach ($orders as $order) {
            echo $order->id;

            foreach ($order->offers as $key => $value) {

                echo $value->name;
                echo $value->pivot->quantity;

            }
            echo $order->offers;
            echo "<br />";

        }
        */


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

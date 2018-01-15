<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Offer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Offers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

      $offers = Offer::all();

      return view('admin.offers')->with('offers', $offers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.offers_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        Validator::make($request->all(), [

            'name' => 'required|unique:offers,name',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0.01',
            'offer' => 'mimes:jpeg,bmp,png,gif,jpg'

        ])->validate();

        $offer = new Offer();
        $offer->name = $request->name;
        $offer->description = $request->description;
        $offer->price = $request->price;


        if(!empty($request->file('offer'))) {

            $path = $request->file('offer')->store('offers', 'public');
            $offer->image = Storage::url($path);

        }
		
		else {
			
			$offer->image = "";
			
		}


        $offer->save();

        return redirect('admin/offers')->with('success','Offer has been added');

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
        $offer = Offer::findOrFail($id);

        return view('admin.offers_edit')->with('offer', $offer);
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


        Validator::make($request->all(), [

            'description' => 'required|string',
            'price' => 'required|numeric|min:0.01',
            'offer' => 'mimes:jpeg,bmp,png,gif,jpg'

        ])->validate();


        $offer = Offer::findOrFail($id);

        if($offer->name != $request->name) {

            Validator::make($request->all(), [

                'name' => 'required|unique:offers,name',

            ])->validate();

        }

        $offer->name = $request->name;
        $offer->description = $request->description;
        $offer->price = $request->price;

        if(!empty($request->file('offer'))) {

		
			 if($path = $request->file('offer')->store('offers', 'ftp')) {


                if (!is_null($offer->image)) {

                    Storage::disk('ftp')->delete($offer->image);

                }

				
                $offer->image = $path;

            }
			

        }


        $offer->save();

        return redirect('admin/offers')->with('success','Offer has been edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $offer = Offer::findOrFail($id);

        if(!empty($offer->image)) {

            Storage::delete(str_replace("/storage/", "/public/", $offer->image));

        }

        $offer->delete();
        return redirect('admin/offers')->with('success','Offer has been removed');
    }
}

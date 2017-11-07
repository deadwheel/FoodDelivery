<?php

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfferController extends Controller
{
    public function index(){
		
		$offers = DB::table('offers')->get();
		
		return response()->json(['data' => $offers], 200, [], JSON_NUMERIC_CHECK);
		
	} 
}

<?php

namespace App\Http\Controllers\api;

use Illuminate\Support\Facades\DB;
use Laravel\Passport\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\UserDetails;
use function MongoDB\BSON\toJSON;
use Response;
 
    class UserController extends Controller
    {
     		
    public function login(Request $request){
      	   
		$this->validate($request, [
    		'username' => 'required',
    		'password' => 'required'
    	]);

		if (Auth::attempt(['email' => $request->username, 'password' => $request->password])) {
		
		$user = Auth::user();
		
		
				
		$params = [
		
    		'grant_type' => 'password',
    		'client_id' => '2',
    		'client_secret' => DB::table('oauth_clients')->where('id', '2')->value('secret'), 
			'password' => $request->password,
			'username' => $request->username,
			'scope' => '',	
    		
    	];
					
		$request->request->add($params);
		
	
    	$proxy = Request::create(
			'/oauth/token',
			'POST'
			
		);
				
		$response = Route::dispatch($proxy);	
	  
    	$json = (array)json_decode($response->getContent());
		$json['role_id'] = $user->id;
		$response->setContent(json_encode($json));
		return $response;
				
		}else return null;

    }
	
	
	public function refresh(Request $request){
    	
		$this->validate($request, [
    		'refresh_token' => 'required'
    	]);
		
			$params = [
		
    		'grant_type' => 'refresh_token',
    		'client_id' => '2',
    		'client_secret' => DB::table('oauth_clients')->where('id', '2')->value('secret'), 
			'password' => $request->password,
			'scope' => '*'	
    		
    	];
		
		$params['username'] = $request->username ?: $request->email;
		
		$request->request->add($params);

    	$proxy = Request::create('oauth/token', 'POST');

    	return Route::dispatch($proxy);

    }

	
	public function logout(Request $request){
	
			$accessToken = Auth::user()->token();

			DB::table('oauth_refresh_tokens')
    		->where('access_token_id', $accessToken->id)
    		->update(['revoked' => true]);

			$accessToken->revoke();
			$accessToken->delete();
				
		
    	return response()->json([], 204);

    }
	
	public function details(){
		
        return response()->json(['user' => Auth::user()]);
	
	}
		
	public function register(Request $request){
		
		$this->validate($request, [
    		'name' => 'required',
    		'email' => 'required|email|unique:users,email',
    		'password' => 'required|min:6'
    	]);

    	$user = User::create([
    		'name' => $request->name,
    		'email' => $request->email,
    		'password' => bcrypt($request->password)
    	]);
		
		$params = [
		
    		'grant_type' => 'password',
    		'client_id' => '2',
    		'client_secret' => DB::table('oauth_clients')->where('id', '2')->value('secret'), 
			'password' => $request->password,
			'username' => $request->name,
			'scope' => '*'	
    		
    	];
				
				
		$request->request->add($params);

    	$proxy = Request::create('oauth/token', 'POST');

    	return Route::dispatch($proxy);
		
	}



	public function update_details(Request $request, $id) {

	    if (Auth::id() == $id) {


            // TODO Validacja ew poprawic
            Validator::make($request->all(), [
                'firstname' => 'nullable|alpha',
                'lastname' => 'nullable|alpha',
                'phonenumber' => 'nullable|numeric',
                'address' => 'nullable|string',
                'city' => 'nullable|string',
                'postcode' => 'nullable|string',


            ])->validate();

            $user = User::find(Auth::id())->details;


            if (is_null($user)) {

                $userdet = new UserDetails;
                $userdet->fill(request()->all());

                $user = User::find(Auth::id());
                $user->details()->save($userdet);

                return response()->json(['user_details' => $user->details],'200');




            } else {

                $user->fill($request->all());
                $user->save();

                return response()->json(['user_details' => $user],'200');

            }



        }

        else {


	        return response()->json(['error' => 'error'], '401');

        }

    }


    public function getDetails($id) {


	    if(Auth::id() == $id) {


	        $user = User::findOrFail(Auth::id());
	        $user->details;

	        return response()->json(['user_details' => $user], '200');

        }

        else {


            return response()->json(['error' => 'error'], '401');

        }

    }

}

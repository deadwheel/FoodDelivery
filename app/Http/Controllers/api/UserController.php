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
			'refresh_token' => $request->refresh_token,
    		'client_id' => '2',
    		'client_secret' => DB::table('oauth_clients')->where('id', '2')->value('secret'), 
			'scope' => ''	
    		
    	];
		
		$params['username'] = $request->username ?: $request->email;
		
		$request->request->add($params);

    	$proxy = Request::create('oauth/token', 'POST');

		$response = Route::dispatch($proxy);	
	  
    	$json = (array)json_decode($response->getContent());
		$json['role_id'] = Auth::id();
		$response->setContent(json_encode($json));
		return $response;

    }

	
	public function logout(Request $request){
		
		if(Auth::check()) {
	
			$accessToken = Auth::user()->token();

			DB::table('oauth_refresh_tokens')
    		->where('access_token_id', $accessToken->id)
    		->update(['revoked' => true]);

			$accessToken->revoke();
			$accessToken->delete();
			
		}
				
		
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


public function update_details(Request $request) {

	    


            // TODO Validacja ew poprawic
            Validator::make($request->all(), [
                'firstname' => 'nullable|alpha',
                'lastname' => 'nullable|alpha',
                'phonenumber' => 'nullable|numeric',
                'address' => 'nullable|string',
                'city' => 'nullable|string',
                'postcode' => 'nullable|string',


            ])->validate();
	
            /*$user = User::find(Auth::id())->details;
	
				*/
					$data = $request->all();		
					$user =  User::find(Auth::id())->details();
					$user->updateOrCreate(
   					 ['user_id' => Auth::id()],
   					 $data
						);
					
					return response()->json(['data' => $user->get()],'200');
					
        /*    if (is_null($user)) {

                $userdet = new UserDetails;"sadasd"
                $userdet->fill(request()->all());

                $user = User::find(Auth::id());
                $user->details()->save($userdet);

                return response()->json(['data' => $user->details],'200');


            } else {

                $user->fill($request->all());
                $user->save();

                return response()->json(['data' => $user],'200');

            }

	*/
        }
  

    public function getDetails() {

	        $user = User::find(Auth::id());
	    		
	        if($user->details != null){
	             return response()->json(['data' => $user->details()->get()], '200');
				}
     

      

    }

}

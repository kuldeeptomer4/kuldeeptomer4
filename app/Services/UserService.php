<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProfiles;
use App\Models\Address;

class UserService {

	/* Register */
	public function register( $request ) {				 
		$user = User::create( [			 
			'email'		 => $request->email,
			'last_login_date' => date('Y-m-d'),
			'password'	 => bcrypt( $request->password ),
			'type' => $request->type,
		] );
		$userAddress = Address::create([
			'user_id' => $user->id,
			'address_line_1' => $request->address, 
			'city' => $request->city,
			'state' => $request->state,
			'country' => $request->country,
			'geo_location' => $request->geo_location,
		]);
	 	$userProfile = UserProfiles::create( [
			'user_id' => $user->id,
			'first_name'		 => $request->first_name,
			'last_name' => $request->last_name,
			'mobile_number' => $request->mobile_number,			
			'office_number' => $request->office_number,
			'address_id' => $userAddress->id,
			'profile_pic' => $request->profile_image
		]);
		return $user;
	}
 	
	/* login */ 
	public function login( $request ) {
		$login_credentials = [
			'email'		 => $request->email,
			'password'	 => $request->password,
		];
		$user = auth()->attempt( $login_credentials );
		return $user;
	}
	/* change password */
	public function changepassword($userid, $request){		
		$data = User::find($userid);	 
		if (!\Hash::check($request->current_password, $data->password)) {
			return 0;
		}else{
			$update_password = User::where('id', $userid)->update( [			 
				'password'	 => bcrypt( $request->confirm_password ),
			] );
			return $update_password;
		}	
	}
	

	/* forgot password */
	public function forgotpassword($request){
		$data = User::where(['email'=>$request->email, 'is_moov_admin' => $request->type])->first();
	 	if($data){
			$data->is_forgot_password  = 1;
			$data->save();
			return $data;
		}else{
			return 0;
		}
		
	}	



	// dfdffds
}
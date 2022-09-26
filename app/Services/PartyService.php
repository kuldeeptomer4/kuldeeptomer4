<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\PropertyParties;
use App\Traits\AuthenticationUser;
use App\Models\Address;
use App\Models\PropertyImages;
use App\Models\UserProfiles;
use App\Models\User;

class PartyService{
	use AuthenticationUser;

	public function create( $request ) {
		$userid = $this->AuthUser();	
		
		$user = User::create( [			 
			'email'		 => $request->email,
			'last_login_date' => date('Y-m-d'),
			'type' => 'parties',
		]);
		
		$partyAddress = Address::create([
			'user_id' => $user->id,
			'address_line_1' => $request->address, 
			'city' => $request->city,
			'state' => $request->state,
			'country' => $request->country,
			'geo_location' => $request->geo_location,
		]);

		$parties = PropertyParties::create( [	
			'property_id' => 	$request->property_id,	
			'role_id' => 	$request->role,
			'user_id' => $user->id,
			'invited_by' => $userid,			
			'invited_date' => date('Y-m-d'),
			'is_enabled' => 0,
			'accepted_invitation' => 0,
		]);
		$userProfile = UserProfiles::create( [
			'user_id' => $user->id,
			'first_name'		 => $request->name,
			'company'  => $request->company,
			'mobile_number' => $request->contact_number,			
			'office_number' => $request->other_number,
			'address_id' => $partyAddress->id,
			'profile_pic' => $request->profile_images
		]);
		

		return $partyAddress;
	}
	
	
}
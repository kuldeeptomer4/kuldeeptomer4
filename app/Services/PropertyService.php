<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\Properties;
use App\Traits\AuthenticationUser;
use App\Models\Address;
use App\Models\PropertyImages;

class PropertyService {
	use AuthenticationUser;

	public function create( $request ) {
		$userid = $this->AuthUser();	
		$propertyAddress = Address::create([
			'user_id' => $userid,
			'address_line_1' => $request->property_address_line_1, 
			'city' => $request->city,
			'state' => $request->state,
			'country' => $request->country,
			'geo_location' => $request->geo_location,
		]);

		$property = Properties::create( [	
			'agency_id' => 	$userid,	
			'created_by_id' => 	$userid,
			'name'		 => $request->property_name,
			'slug' => $request->property_name,
			'address_id'	 => $propertyAddress->id,
			'property_value' => $request->property_value,
			'description' => $request->property_description,
			'type' => $request->property_type,
			'estimated_completion_date' => $request->estimated_completion_date,			
			'currency_id' => $request->property_currency,
		]);
		
		$property_images = $request->property_images;
		// $property_images = ["image1", "image2", "image3", "image4"];
		if ($request->property_images) {
			foreach ($request->property_images as $file) {
				$saveimage = new PropertyImages;
				$saveimage->property_id = '1';
				$saveimage->image = $file;
				$saveimage->save();
			}
		}		

		return $property;
	}
	
	
}
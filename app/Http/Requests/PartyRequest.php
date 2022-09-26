<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\ApiResponser;

class PartyRequest extends FormRequest {

	use ApiResponser;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	
	 public function rules() {
		return [
		 	'role'		 => 'required|numeric',
			'email' => 'required|email|unique:users|regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/',
			'contact_number' => 'required|min:6',
			'name' => 'required', 
			'company' => 'required',
			'city' => 'required',
			'state' => 'required',
			'country' => 'required',
			'geo_location' => 'required',
			'profile_images'           => 'required',
		];
	}
	
	public function failedValidation( Validator $validator ) {
		throw new HttpResponseException(
		$this->errorResponse( $validator->messages(), 422 )
		);
	}


}
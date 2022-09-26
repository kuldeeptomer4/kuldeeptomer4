<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\ApiResponser;

class ProprtyRequest extends FormRequest {

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
		 	'property_name'		 => 'required',
			'property_value'       => 'required',
			'property_description' => 'required', 
			'property_type' => 'required',
			'estimated_completion_date'  => 'required',
			'property_images' => 'required',
			'property_address_line_1'           => 'required',
			'city' => 'required',
			'state' => 'required',
			'country' => 'required',
			'geo_location' => 'required'
		];
	}
	
	public function failedValidation( Validator $validator ) {
		throw new HttpResponseException(
		$this->errorResponse( $validator->messages(), 422 )
		);
	}
}
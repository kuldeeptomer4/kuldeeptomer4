<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\ApiResponser;

class UserRegisterRequest extends FormRequest {

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
			'first_name'		 => 'required',
			'last_name'  => 'required',
			'email'		 => 'required|email|unique:users',
			'mobile_number'   => 'required',
			'office_number' => 'required',
			'address'      => 'required',
			'password'	 => 'required|min:8',
			'confirm_password'  => 'required|same:password',
			'city' => 'required',
			'state' => 'required',
			'country' => 'required',
			'geo_location' => 'required',
		];
	}

	public function failedValidation( Validator $validator ) {

		throw new HttpResponseException(
		$this->errorResponse( $validator->messages(), 422 )
		);
	}

}
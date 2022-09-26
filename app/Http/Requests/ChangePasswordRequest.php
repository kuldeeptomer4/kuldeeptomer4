<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\ApiResponser;

class ChangePasswordRequest extends FormRequest {

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
		 	'current_password'		 => 'required',
			'new_password'           => 'required',
			'confirm_password'       => 'required|same:new_password',
		];
	}

	public function failedValidation( Validator $validator ) {

		throw new HttpResponseException(
		$this->errorResponse( $validator->messages(), 422 )
		);
	}

}
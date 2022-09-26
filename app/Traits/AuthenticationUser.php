<?php

namespace App\Traits;

trait AuthenticationUser {

	protected function AuthUser() {
		$user = auth('sanctum')->user()->id;
		return $user;
	}

	

}

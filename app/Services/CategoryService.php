<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryService {

	public function create( $request ) {

		$cat				 = new Category();
		$cat->title			 = $request->get( 'title' );
		$cat->slug			 = $request->get( 'slug' );
		$cat->description	 = $request->get( 'description' ) ?? '';
		$cat->save();

		return $cat;
	}

}

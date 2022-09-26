<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Http\Requests\CategoryCreateRequest;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Services\CategoryService;

class CategoryController extends ApiController {

	protected $categoryService;

	public function __construct() {
		$this->categoryService = new CategoryService();
	}

	/**
	 * @OA\Post(
	 *     path="/category/create",
	 *     summary="Create category",
	 *     tags={"Category"},
	 *     description="Create category",
	 *     operationId="createCategory",
	 * 	   @OA\RequestBody(
	 *         description="Input data format",
	 *         @OA\MediaType(
	 * 			   mediaType="application/x-www-form-urlencoded",
	 *             @OA\Schema(
	 *                 type="object",
	 *                 @OA\Property(
	 *                     property="title",
	 *                     description="Title",
	 *                     type="string",
	 *                 ),
	 *                 @OA\Property(
	 *                     property="slug",
	 *                     description="Slug",
	 *                     type="string"
	 *                 ),
	 *                 @OA\Property(
	 *                     property="description",
	 *                     description="Description",
	 *                     type="string"
	 *                 )
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Category has been created successfully.",
	 *         @OA\Schema(
	 *             type="array",
	 *             @OA\Items(ref="#/components/schemas/Pet")
	 *         ),
	 *     ),
	 *     @OA\Response(
	 *         response="401",
	 *         description="You are authorized",
	 *     ),
	 *     security={
	 *         {"petstore_auth": {"write:pets", "read:pets"}}
	 *     },
	 *     deprecated=false
	 * )
	 */
	public function create( CategoryCreateRequest $request ) {

		$user = Auth::user();

		if ( !$user->hasRole( 'admin' ) ) {
			return $this->errorResponse( 'You are authorized', 400 );
		}

		try {
			DB::beginTransaction();

			$cat = $this->categoryService->create( $request );

			DB::commit();
			return $this->successResponse( $cat, 'Category has been created successfully.', 200 );
		} catch ( \Exception $exp ) {
			DB::rollBack();
			return $this->errorResponse( $exp->getMessage(), 400 );
		}
	}

}

<?php

namespace App\Http\Controllers\AgentAdmin;

use DB;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\StaticPageRequest;
use App\Models\StaticPage;
use App\Traits\ApiResponser;
use App\Traits\AuthenticationUser;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Request;
use App\Models\UserRole;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="1.0.0",
 *         title="Moov Project",
 *         description="This is a sample server Moov project. You can find out more about Swagger at [http://swagger.io](http://swagger.io) or on [irc.freenode.net, #swagger](http://swagger.io/irc/).  For this sample, you can use the api key `special-key` to test the authorization filters.",
 *         termsOfService="http://swagger.io/terms/",
 *         @OA\Contact(
 *             email="apiteam@swagger.io"
 *         ),
 *         @OA\License(
 *             name="Apache 2.0",
 *             url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *         )
 *     ),
 *     @OA\Server(
 *         description="MoovAPI host",
 *         url="http://localhost/moov_project/public/api/"
 *     ),
 *     @OA\ExternalDocumentation(
 *         description="Find out more about Swagger",
 *         url="http://swagger.io"
 *     )
 * )
 */
class UserController extends ApiController {
	
	use ApiResponser;
	use AuthenticationUser;

	protected $userService;

	public function __construct() {
		$this->userService = new UserService();
	}
	 

	/**
	 * @OA\Post(
	 *     path="/user/register",
	 *     summary="Register User",
	 *     tags={"User"},
	 *     description="Register user",
	 *     operationId="registerUser",
	 * 	   @OA\RequestBody(
	 *         description="Input data format",
	 *         @OA\MediaType(
	 * 			   mediaType="application/x-www-form-urlencoded",
	 *             @OA\Schema(
	 *                 type="object",
	 *                 @OA\Property(
	 *                     property="name",
	 *                     description="Name",
	 *                     type="string",
	 *                 ),
	 *                 @OA\Property(
	 *                     property="email",
	 *                     description="Email",
	 *                     type="string"
	 *                 ),
	 *                 @OA\Property(
	 *                     property="password",
	 *                     description="Password",
	 *                     type="string"
	 *                 )
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Registered successfully",
	 *         @OA\Schema(
	 *             type="array",
	 *             @OA\Items(ref="#/components/schemas/Pet")
	 *         ),
	 *     ),
	 *     @OA\Response(
	 *         response="400",
	 *         description="Email is already taken",
	 *     ),
	 *     security={
	 *         {"petstore_auth": {"write:pets", "read:pets"}}
	 *     },
	 *     deprecated=false
	 * )
	 */

	/* Register User */
	public function register_user( UserRegisterRequest $request ) {
		try {
			DB::beginTransaction();
				$user = $this->userService->register( $request );
				$access_token_example = $user->createToken( "API TOKEN" )->plainTextToken;
				$data = [ 'token' => $access_token_example ];
			DB::commit();
			return $this->successResponse( $data, 'Registered successfully', 200 );
		} catch ( \Exception $exp ) {
			DB::rollBack();
			return $this->errorResponse( $exp->getMessage(), 400 );
		}
	}

	/**
	 * @OA\Post(
	 *     path="/user/login",
	 *     summary="Login User",
	 *     tags={"User"},
	 *     description="Login user",
	 *     operationId="loginUser",
	 * 	   @OA\RequestBody(
	 *         description="Input data format",
	 *         @OA\MediaType(
	 * 			   mediaType="application/x-www-form-urlencoded",
	 *             @OA\Schema(
	 *                 type="object",
	 *                 @OA\Property(
	 *                     property="email",
	 *                     description="Email",
	 *                     type="string",
	 *                 ),
	 *                 @OA\Property(
	 *                     property="password",
	 *                     description="Password",
	 *                     type="string"
	 *                 )
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Login successfully",
	 *         @OA\Schema(
	 *             type="array",
	 *             @OA\Items(ref="#/components/schemas/Pet")
	 *         ),
	 *     ),
	 *     @OA\Response(
	 *         response="400",
	 *         description="Email/Passoword is incorrect",
	 *     ),
	 *     security={
	 *         {"petstore_auth": {"write:pets", "read:pets"}}
	 *     },
	 *     deprecated=false
	 * )
	 */


	/* Login Users*/
	public function login_user( UserLoginRequest $request ) {
		try {
			DB::beginTransaction();
			$is_user_valid = $this->userService->login( $request );
			if ( $is_user_valid ) {
				$user				 = User::where( 'email', $request->email )->first();
				$user_login_token	 = $user->createToken( "API TOKEN" )->plainTextToken;
				$data				 = $user;
				$userRole = UserRole::find($user->type);
				$data->role = $userRole->role_name;
				$data->token = $user_login_token;
			DB::commit();
				return $this->successResponse( $data, 'Login successfully', 200 );
			} else {
				return $this->errorResponse( 'Email/Passoword is incorrect', 401 );
			}
			DB::commit();
		} catch ( \Exception $exp ) {
			DB::rollBack();
			return $this->errorResponse( $exp->getMessage(), 400 );
		}
	}

	/**
	 * @OA\Get(
	 *     path="/user/detail",
	 *     summary="User Details",
	 *     tags={"User"},
	 *     description="User details",
	 *     operationId="userDetail",
	 *     @OA\SecurityScheme(
	 *		   type="apiKey",
	 *		   in="header",
	 *		   securityScheme="api_key",
	 *		   name="Authorization",
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="User detail fetched successfully",
	 *         @OA\Schema(
	 *             type="array",
	 *             @OA\Items(ref="#/components/schemas/Pet")
	 *         ),
	 *     ),
	 *     @OA\Response(
	 *         response="401",
	 *         description="Unauthenticated",
	 *     ),
	 *     security={
	 *         {"petstore_auth": {"write:pets", "read:pets"}}
	 *     },
	 *     deprecated=false
	 * )
	 */

	/* Logout Users*/
	public function Logout(Request $request){
		$accessToken = $request->bearerToken();
	    $token = PersonalAccessToken::findToken($accessToken);
		$token->delete();
		return $this->successResponse('User logout successfully', 200 );
	}

	/* Forgot password*/
	public function ForgotPassword(ForgotPasswordRequest $request){
		// dd("dsdsdfds");
		try {
			DB::beginTransaction();
				$userid = $this->AuthUser();
				$user = $this->userService->forgotpassword($request);
				// dd($user);
				if($user == 0){
					return $this->errorResponse( 'Incorrect email!', 400 );
				}
				$data = User::find($user->id);
			DB::commit();
			return $this->successResponse( $data, 'Email sent successfully!', 200 );
		} catch ( \Exception $exp ) {
			DB::rollBack();
			return $this->errorResponse( $exp->getMessage(), 400 );
		}
	}

	/* Change password*/
	public function ChangePassword(ChangePasswordRequest $request){
		try {
			DB::beginTransaction();
				$userid = $this->AuthUser();										
				$user = $this->userService->changepassword($userid, $request);
				if($user == 0){
					return $this->errorResponse( 'Incorrect current password!', 400 );
				}
				$data = User::find($userid);
			DB::commit();
			return $this->successResponse( $data, 'Password updated successfully!', 200 );
		} catch ( \Exception $exp ) {
			DB::rollBack();
			return $this->errorResponse( $exp->getMessage(), 400 );
		}
	}

	
	/*  Static Page */
	public function StaticPage(StaticPageRequest $request){
		try{
			$data = StaticPage::where('content_type', $request->page)->first();
			return $this->successResponse( $data, 200 );
		}catch ( \Exception $exp ) {
			DB::rollBack();
			return $this->errorResponse( $exp->getMessage(), 400 );
		}
	}

	/* Role */
	public function Role(){
		$userrole = UserRole::get();
		return $this->successResponse( $userrole, 'User role fetched successfully', 200 );
	}

	/* Upload Image*/
	public function UploadImage(){
		dd("image");
	}

	// public function detail_user() {
	// 	$user = Auth::user();
	// 	return $this->successResponse( $user, 'User detail fetched successfully', 200 );
	// }
}
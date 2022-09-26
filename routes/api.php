<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgentAdmin\UserController;
use App\Http\Controllers\AgentAdmin\AgentController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\AgentAdmin\PropertyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post( 'agency/register', [ UserController::class, 'register_user' ] );
Route::post( 'agency/login', [ UserController::class, 'login_user' ] );
Route::post('agency/forgot-password', [UserController::class, 'ForgotPassword']);

Route::group(['prefix' => 'agency','middleware' => ['auth:sanctum']], function () {

	/* User management route */
	Route::get( 'logout', [UserController::class, 'Logout']);
	
	
	Route::post('change-password', [UserController::class, 'ChangePassword']);
	Route::post('static-page', [UserController::class, 'StaticPage']);
	Route::get('role', [UserController::class, 'Role']);

	Route::post('upload-image', [UserController::class, 'UploadImage']);
	
	/* Property management route */
	Route::post('add-property', [PropertyController::class, 'AddProperty']);
	Route::post('add-party-details', [PropertyController::class, 'AddPartyDetails']);
	Route::get('party-list', [PropertyController::class, 'PartyList']);
	Route::get('property-listing', [PropertyController::class, 'PropertyListing']);

	/* Stages management route */
	Route::post('add-stage', [PropertyController::class, 'AddStage']);
	Route::post('delete-stage', [PropertyController::class, 'DeleteStage']);
});
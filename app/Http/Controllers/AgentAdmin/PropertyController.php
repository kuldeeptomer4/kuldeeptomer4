<?php

namespace App\Http\Controllers\AgentAdmin;
use DB;
use App\Services\PropertyService;
use App\Services\PartyService;
use App\Services\StageService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProprtyRequest;
use App\Traits\ApiResponser;
use App\Traits\AuthenticationUser;
use App\Http\Requests\PartyRequest;
use App\Http\Requests\StageRequest;
use App\Http\Requests\DeleteStageRequest;
use App\Models\Properties;
use App\Models\PropertyParties;
use App\Models\UserRole;

class PropertyController extends Controller
{
    use ApiResponser;
	use AuthenticationUser;

	protected $userService;
	protected $PartyService;
	protected $StageService;
	
	public function __construct() {
		$this->PropertyService = new PropertyService();
		$this->PartyService = new PartyService();
		$this->StageService = new StageService();
	}

	/* Add Property */
    public function AddProperty(ProprtyRequest $request){
        try {
			DB::beginTransaction();
				$userid = $this->AuthUser();	
    			$property = $this->PropertyService->create($request);
			DB::commit();
			return $this->successResponse( $property, 'Property added successfully!', 200 );
		} catch ( \Exception $exp ) {
			DB::rollBack();
			return $this->errorResponse( $exp->getMessage(), 400 );
		}
    }


	/* Add Party details */
	public function AddPartyDetails(PartyRequest $request){
		try {
			DB::beginTransaction();
				$userid = $this->AuthUser();	
    			$party = $this->PartyService->create($request);
				$role = UserRole::where('id', $request->role)->first();
			DB::commit();
			return $this->successResponse($party, $role->description.' created successfully!', 200 );
		} catch ( \Exception $exp ) {
			DB::rollBack();
			return $this->errorResponse( $exp->getMessage(), 400 );
		}
	}

	
	/* Party list */
	public function PartyList(){
		$userid = $this->AuthUser();	
		$userrole = PropertyParties::with(['UserName', 'UserRole', 'UserProfile'])->where('invited_by', $userid)->get();
		return $this->successResponse( $userrole, 'User role fetched successfully', 200 );
	}


	/* Add stage */
	public function AddStage(StageRequest $request){
		try {
			DB::beginTransaction();
				$userid = $this->AuthUser();	
				$stages = $this->StageService->create($request);
				DB::commit();
			return $this->successResponse($stages, 'Stage added successfully!', 200 );
		} catch ( \Exception $exp ) {
			DB::rollBack();
			return $this->errorResponse( $exp->getMessage(), 400 );
		}
	}


	/* Delete Stage */
	public function DeleteStage(DeleteStageRequest $request){
		try {
			DB::beginTransaction();
				$deletestages = $this->StageService->delete($request);
				if($deletestages == 1){
					return $this->errorResponse( 'Incorrect stage id!', 400 );
				}
				DB::commit();
			return $this->successResponse('Stage deletd successfully!', 200 );
		} catch ( \Exception $exp ) {
			DB::rollBack();
			return $this->errorResponse( $exp->getMessage(), 400 );
		}
	}


	/* Property Listing */
	public function PropertyListing(){
		$userid = $this->AuthUser();	
		$userrole = Properties::with(['PropertyStage', 'PropertyParties'])->where('created_by_id', $userid)->get();
		return $this->successResponse( $userrole, 'Property fetched successfully', 200 );
	}
}
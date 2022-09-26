<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\PropertyStages;
use App\Traits\AuthenticationUser;
use App\Models\PropertyStageResponsible;
use App\Models\PropertyStageVisibility;

class StageService{
	use AuthenticationUser;

	public function create( $request ) {
		
		$userid = $this->AuthUser();	
		$propertystage = PropertyStages::create( [	
			'property_id' =>  $request->property_id,		 
			'stage_name'		 => $request->name,
			'due_date' => $request->due_date,
			'description' => $request->description,
		]);	
		
		$propertystagevisible = $request->visible_to;
		// $propertystagevisible = ["2", "1", "4", "3"];
		if ($propertystagevisible) {
			foreach ($propertystagevisible as $file) {
				$propertystagevisibl = new PropertyStageVisibility;
				$propertystagevisibl->property_stage_id = $propertystage->id;
				$propertystagevisibl->role_id = $file;
				$propertystagevisibl->created_id = $userid;
				$propertystagevisibl->save();
			}
		}	

		$propertyresponsible = $request->perty_responsible;
		// $propertyresponsible = ["2", "1", "4", "3"];		
		if ($propertyresponsible) {
			foreach ($propertyresponsible as $file) {
				$propertystageresponsible = new PropertyStageResponsible;
				$propertystageresponsible->property_stage_id = $propertystage->id;
				$propertystageresponsible->role_id = $file;
				$propertystageresponsible->created_id = $userid;
				$propertystageresponsible->save();
			}
		}	
		return $propertystage;
	}


	public function delete($request){
		
		// dd($request->stage_id);
		$stageExist = PropertyStages::find($request->stage_id);
		if($stageExist){
			$deletepropertystage = PropertyStages::where('id', $request->stage_id)->delete();
			$deletepropertystagevisibility = PropertyStageVisibility::where('property_stage_id', $request->stage_id)->delete();
			$deletepropertystageresponsible = PropertyStageResponsible::where('property_stage_id', $request->stage_id)->delete();
		
			return 0;

		}else{
			return 1;
		}		
	}


}
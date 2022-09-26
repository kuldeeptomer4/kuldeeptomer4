<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PropertyStages;
use App\Models\PropertyParties;

class Properties extends Model
{
    use HasFactory;

    protected $guarded = ['properties'];
    protected $table = 'properties';

    public function PropertyStage(){        
        return $this->hasMany(PropertyStages::class, 'property_id', 'id');
    }

    public function PropertyParties(){
        return $this->hasMany(PropertyParties::class, 'property_id', 'id');
    }

}

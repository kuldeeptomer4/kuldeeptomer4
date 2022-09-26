<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyStageVisibility extends Model
{
    use HasFactory;

    protected $guarded = ['property_stage_visibility'];
    protected $table = 'property_stage_visibility';
}

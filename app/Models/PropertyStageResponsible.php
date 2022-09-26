<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyStageResponsible extends Model
{
    use HasFactory;

    protected $guarded = ['property_stage_responsible'];
    protected $table = 'property_stage_responsible';
}

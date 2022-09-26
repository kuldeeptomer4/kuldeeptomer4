<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyAgents extends Model
{
    use HasFactory;

    protected $guarded = ['agency_agents'];
    protected $table = 'agency_agents';
}
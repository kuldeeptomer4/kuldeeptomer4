<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\UserRole;

class PropertyParties extends Model
{
    use HasFactory;

    protected $guarded = ['property_parties'];
    protected $table = 'property_parties';

    public function UserName(){
        return $this->belongsTo(User::class, 'invited_by', 'id');
    }

    public function UserRole(){
        return $this->hasOne(UserRole::class, 'id', 'role_id');
    }

    public function UserProfile(){
        return $this->belongsTo(UserProfiles::class, 'invited_by', 'user_id');
    }


}

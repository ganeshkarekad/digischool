<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Entity\User;
use App\Entity\UserRole;

class Role extends Model
{
    /**
     * Roles belonging to a User
     */
    public function users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
}

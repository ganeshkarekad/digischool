<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Entity\Role;
use App\Entity\UserRole;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name', 'email', 'password','dateOfBirth','street','state','country','pinCode','profilePic'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Roles belonging to a User
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role')->withTimestamps();
    }

    public function organisation()
    {
        return $this->belongsTo('App\Organisation','organisationId','id');
    }

    public function course()
    {
        return $this->belongsTo('App\Course','courseId','id');
    }

    /**
     * Return the avatar of the user if the user has uploaded his avatar, else return the placeholder
     */
    public function avatar()
    {
        return ($this->profilePic == '')?"https://image.shutterstock.com/image-illustration/male-default-avatar-profile-gray-260nw-582509287.jpg":"/storage/user/avatar/".$this->id."/".$this->profilePic;
    }

}

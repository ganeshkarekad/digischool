<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organisation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'organisations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name', 'logo', 'shortDescription','Description'
    ];

    /**
     * Link organisation with it's members
     */
    public function users()
    {
        return $this->hasMany('App\User','organisationId','id');
    }

    public function avatar()
    {
        return ($this->logo == '')?"https://via.placeholder.com/500x300?text=LOGO":"/storage/organisation/".$this->id."/".$this->logo;
    }
    
    /**
     * Link Organisation to it's courses
     */
    public function course()
    {
        return $this->hasMany('App/Course','organisationId','id');
    }
}

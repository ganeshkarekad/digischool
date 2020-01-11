<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Subject;

class Course extends Model
{
    //

    protected $table = 'courses';

    public function organisations()
    {
        return $this->belongsTo('App\Organisation','id','organisationId');
    }
    /**
     * Link subjects to the course
     */
    public function subjects()
    {
        return $this->hasMany('App\Subject','courseId','id');
    }

    public function users()
    {
        return $this->hasMany('App\User','courseId','id');
    }
}

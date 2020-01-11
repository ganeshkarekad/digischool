<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    //

    protected $table = 'subjects';

    public function course()
    {
        return $this->belongsTo('App\Course','id','courseId');
    }
}

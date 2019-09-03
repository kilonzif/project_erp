<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AceCourse extends Pivot
{
    //
    protected $table = 'ace_courses';
    protected $fillable = ['ace_id','course_id'];
}

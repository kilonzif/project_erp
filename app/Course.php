<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //
    protected $fillable = ['name','code'];

    public function aces()
    {
//        return $this->belongsToMany('App\Ace','App\AceCourse');
        return $this->belongsToMany('App\Ace')->using('App\AceCourse');
    }
}

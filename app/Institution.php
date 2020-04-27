<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    //
    public function aces(){
        return $this->hasMany('App\Ace');
    }

    public function country(){
        return $this->belongsTo('App\Country');
    }

    public function reports()
    {
        return $this->hasManyThrough('App\Report','App\Ace','institution_id','ace_id');
    }

    public function users()
    {
        return $this->hasMany('App\User','institution');
    }

}

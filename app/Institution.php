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

}

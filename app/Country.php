<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    //
    public function country(){
        return $this->hasMany('App\Institution');
    }
    public function aces(){
        return $this->hasManyThrough('App\Ace','App\Institution');
    }
}

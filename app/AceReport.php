<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AceReport extends Model
{
    //

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function reports(){
        return $this->belongsTo('App\Ace');
    }
}

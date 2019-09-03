<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AceDli extends Model
{
    //
    protected $table = 'ace_dlis';

    public function ace_dlr_indicators()
    {
        return $this->hasMany('App\AceDlrIndicator', 'ace_dli_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    //
    protected $table = 'currencies';
    protected $fillable = ['name','symbol','code'];

    public function ace()
    {
        return $this->hasMany('App\Currency');
    }
}

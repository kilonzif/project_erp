<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitMeasure extends Model
{
    //
    protected $table = 'unit_measures';
    protected $fillable = ['title','order_no','indicator_id'];

    public function specifics()
    {
        return $this->hasMany('App\Specific');
    }

    public function sub_indicators()
    {
        return $this->hasMany('App\SubIndicator');
    }
}

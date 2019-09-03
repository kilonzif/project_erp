<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Specific extends Model
{
    //
    protected $table = 'specifics';

    protected $fillable = ['title','order_no','ace_level_indicator_id','unit_measure_id'];

    public function indicator()
    {
        return $this->belongsTo('App\SubIndicator');
    }

    public function unit_measure()
    {
        return $this->belongsTo('App\UnitMeasure');
    }
}

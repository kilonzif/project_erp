<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AceDlrIndicatorValue extends Model
{
    //
    protected $table = 'ace_dlr_indicator_values';
    protected $fillable = ['ace_id','ace_dlr_indicator_id','reporting_year','value'];

    public function report()
    {
        return $this->belongsTo('App\Report');
    }

    public function aceDlrIndicator()
    {
        return $this->hasMany('App\AceDlrIndicator'.'ace_dlr_indicator_id');
    }
}

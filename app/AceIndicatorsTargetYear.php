<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AceIndicatorsTargetYear extends Model
{
    //
    protected $table = 'ace_indicators_target_years';

    protected $fillable = ['user_id','reporting_year','ace_id'];

    public function target_values()
    {
        return $this->hasMany('App\AceIndicatorsTarget','target_year_id');
    }
}

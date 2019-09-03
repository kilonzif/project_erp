<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AceIndicatorsTarget extends Model
{
    //
    protected $table = 'ace_indicators_targets';

    protected $fillable = ['target','indicator_id','target_year_id','ace_id'];

    public function target_year()
    {
        return $this->belongsTo('App\AceIndicatorsTargetYear','target_year_id');
    }
}

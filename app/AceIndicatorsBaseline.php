<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AceIndicatorsBaseline extends Model
{
    //
    protected $table = 'ace_indicators_baselines';

    protected $fillable = ['baseline','user_id'];

    public function ace()
    {
        return $this->belongsTo('App\Ace');
    }
}

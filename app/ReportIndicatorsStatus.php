<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportIndicatorsStatus extends Model
{
    //
    protected $table = "report_indicators_status";
    protected $fillable = ['report_id','indicator_id','status','responsibility','status_date'];

    public function report()
    {
        return $this->belongsTo('App\Report');
    }
}

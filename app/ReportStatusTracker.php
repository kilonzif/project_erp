<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportStatusTracker extends Model
{
    protected $table = 'report_status_tracker';
    protected $fillable = ['status_code','status_date','report_id'];

    public function report()
    {
        $this->belongsTo('App\Report');
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportValue extends Model
{
    //
    protected $fillable = [
        'report_id', 'indicator_id', 'value'
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReportingPeriod extends Model
{
    protected $table = "reporting_period";
    protected $fillable = ['period_start', 'period_end'];


}
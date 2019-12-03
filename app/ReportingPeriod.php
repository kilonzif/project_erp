<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ReportingPeriod extends Model
{
    use SoftDeletes;
    protected $table = "reporting_period";
    protected $fillable = ['period_start', 'period_end'];

    protected $dates = ['deleted_at'];





}
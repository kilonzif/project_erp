<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    //
    protected $fillable = ['number','report_id','responsibility','status','status_date'];
}

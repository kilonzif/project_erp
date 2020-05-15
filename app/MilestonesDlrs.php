<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MilestonesDlrs extends Model
{
    protected $table = 'milestones_dlrs';

    protected $fillable = ['indicator_id','milestone_no','description','estimated_cost','estimated_earning',
        'start_expected_timeline','end_expected_timeline','status'];

    public function targets()
    {
        return $this->hasMany('App\MilestonesDlrsTarget','milestones_dlr_id');
    }
}

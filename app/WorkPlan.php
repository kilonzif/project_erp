<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class WorkPlan extends Model
{
    protected $table ="workplan";
    protected $fillable = ['ace_id','submission_date','wp_file','wp_year'];



    public function ace(){
        $this->hasMany('App\Ace');
    }


}



<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //

     protected $table = 'projects';

    protected $fillable = ['title','project_coordinator','grant_id','total_grant','start_date','end_date'];

    public function reports()
    {
        return $this->hasMany('App\Report');
    }

    public function indicators(){
        return $this->hasMany('App\Indicator');
    }
}

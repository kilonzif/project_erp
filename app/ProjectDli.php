<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectDli extends Model
{
    //
    protected $table = 'project_dlis';

    public function ace_dlis()
    {
        return $this->hasMany('App\AceDli','project_dli_id','id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IndicatorOne extends Model
{
    protected $table ="indicator_one";
    protected $fillable = ['aceId','requirement','submission_date','file_name','url','web_link','finalised','comments'];
    public function ace(){
        $this->hasMany('App\Ace');
    }
}



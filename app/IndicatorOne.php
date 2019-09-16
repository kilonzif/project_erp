<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IndicatorOne extends Model
{
    protected $table ="indicator_one";
    protected $fillable = ['ace_id','requirement','submission_date','file_one','file_two','url','comments'];
    public function ace(){
        $this->hasMany('App\Ace');
    }
}



<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SectoralBoard extends Model
{
    protected $table ="sectoral_board";
    protected $fillable = ['ace_id','name','title','phone','email'];



    public function ace(){
        $this->hasMany('App\Ace');
    }


}



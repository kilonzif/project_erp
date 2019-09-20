<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aceemail extends Model
{
	    protected $fillable = ['ace_id','contact_name','contact_title','contact_phone','email'];

	    protected $table = 'aceemails';

	    public function ace(){
	    	   return $this->belongsTo('App\Ace');
	    }
    //
}

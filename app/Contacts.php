<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Contacts extends Model
{
	    protected $fillable = ['ace_id','thematic_field','institution','country','edit_status','contact_name','contact_title','contact_phone','email'];

	    protected $table = 'contacts';

	    public function ace(){
	    	   return $this->belongsToMany('App\Ace');
	    }

    public function scopeContact($query, $id) {
        $contact = $query->join('institutions', 'aces.institution_id', '=', 'institutions.id')
            ->join('countries', 'contacts.country', '=', 'countries.id')
            ->where('aces.id', '=', $id)
            ->pluck('countries.country');

        return $contact;
    }





}

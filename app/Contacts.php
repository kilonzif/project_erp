<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Contacts extends Model

{
	    protected $fillable = ['position_id','person_title', 'mailing_name','gender','mailing_phone',
            'mailing_email','insititution','ace','country','thematic_field','new_contact'];

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

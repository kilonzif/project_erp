<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Ace extends Model implements Auditable {
	use \OwenIt\Auditing\Auditable;

	protected $fillable = ['name', 'acronym','grant1','currency1_id','grant2','currency2_id','institution_id', 'contact',
        'email', 'course', 'contact_person', 'person_number', 'person_email', 'position', 'active',
        'ace_type','ace_state','impact_no'];

	public function university() {
		return $this->belongsTo('App\Institution', 'institution_id');
	}

	public function users() {
		return $this->hasMany('App\User');
	}

	public function currency_1() {
		return $this->belongsTo('App\Currency','currency1_id');
	}

	public function currency_2() {
		return $this->belongsTo('App\Currency','currency2_id');
	}

	public function reports() {
		return $this->hasMany('App\Report');
	}

	public function scopeCountry($query, $id) {
		$country = $query->join('institutions', 'aces.institution_id', '=', 'institutions.id')
			->join('countries', 'institutions.id', '=', 'countries.id')
			->where('aces.id', '=', $id)
			->pluck('countries.country');

		return $country;
	}

	public function baselines() {
		return $this->hasMany('App\AceIndicatorsBaseline');
	}

	public function target_years() {
		return $this->hasMany('App\AceIndicatorsTargetYear');
	}

	public function courses() {
		return $this->belongsToMany('App\Course', 'ace_courses');
	}
	public function emails() {
//		return $this->hasMany('App\Aceemail');
	}
	public function contactsEmail() {
		return $this->hasManyThrough('App\Contacts','App\AceContact');
	}

	public function verificationLetters() {
		return $this->hasMany('App\VerificationLetter');
	}

    public function indicator_one(){
	    return $this->belongsTo('App\IndicatorOne');
    }



}

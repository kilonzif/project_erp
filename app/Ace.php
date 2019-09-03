<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Ace extends Model implements Auditable {
	use \OwenIt\Auditing\Auditable;

	protected $fillable = ['name', 'acronym', 'institution_id', 'contact', 'email', 'course', 'contact_person', 'person_number', 'person_email', 'position', 'active'];

	public function university() {
		return $this->belongsTo('App\Institution', 'institution_id');
	}

	public function users() {
		return $this->hasMany('App\User');
	}

	public function currency() {
		return $this->belongsTo('App\Currency');
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
		return $this->hasMany('App\Aceemail');
	}

	public function verificationLetters() {
		return $this->hasMany('App\VerificationLetter');
	}

}

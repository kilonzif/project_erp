<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Laravelista\Comments\Commentable;

class Report extends Model {
	//
	use Commentable;
	protected $fillable = ['project_id', 'reporting_period_id', 'submission_date', 'user_id'];

	public function project() {
		return $this->belongsTo('App\Project');
	}

	public function user() {
		return $this->belongsTo('App\User');
	}

	public function ace() {
		return $this->belongsTo('App\Ace');
	}

	public function report_values() {
		return $this->hasMany('App\ReportValue');
	}

	public function report_indicators_status() {
		return $this->hasMany('App\ReportIndicatorsStatus');
	}

	public function report_status() {
		return $this->hasOne('App\ReportStatusTracker');
	}

	public function scopeSubmittedAndUncompleted($query) {
		return $query->where('status', '=', 99)->orWhere('status', '=', 1);
	}

	public function scopeUncompleted($query) {
		return $query->where('status', '=', 99);
	}

	public function scopeSubmitted($query) {
		return $query->where('status', '=', 1);
	}

	public function scopeOfStatus($query, $status_code) {
		return $query->where('status', '=', $status_code);
	}

	public function verificationLetters() {
		return $this->hasMany('App\VerificationLetter');
	}
}

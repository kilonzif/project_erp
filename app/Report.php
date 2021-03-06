<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravelista\Comments\Commentable;


class Report extends Model {
	//
	use Commentable;
	use Notifiable;
	protected $fillable = ['project_id', 'indicator_id','reporting_period_id', 'submission_date', 'user_id'];

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

	public function report_upload() {
		return $this->hasOne('App\ReportUpload');
	}

	public function report_status() {
		return $this->hasOne('App\ReportStatusTracker');
	}


	public function reporting_period(){
	    return $this->belongsTo('App\ReportingPeriod');
    }

    public function indicator(){
        return $this->belongsTo('App\Indicator');
    }


    public function scopeSubmittedAndUncompleted($query) {
		return $query->where('status', '=', 99)->orWhere('status', '=', 1);
	}

	public static function scopeUncompleted($query) {
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

    public static function getReports($start,$end)
    {
        $report_ids = DB::table('reports')
            ->join('reporting_period', 'reporting_period.id', '=', 'reports.reporting_period_id')
            ->select('reports.id')
            ->where(function ($query) use ($start, $end) {
                return $query->whereIn('reporting_period.period_start', $start)->orWhereIn('reporting_period.period_end', $end);
            })
            ->pluck('id')
        ->toArray();
        return $report_ids;
    }



}

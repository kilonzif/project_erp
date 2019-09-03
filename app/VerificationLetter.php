<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerificationLetter extends Model {
	//

	protected $table = 'verification_letters';

	protected $fillable = ['ace_id', ' amount_due', 'payment', 'total', 'letter_dated', 'date_dispatched'];

	public function ace() {
		return $this->belongsTo('App\Ace');
	}

	public function reports() {
		return $this->belongsTo('App\Report');
	}

}

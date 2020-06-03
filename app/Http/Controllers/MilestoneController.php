<?php

namespace App\Http\Controllers;

use App\Ace;
use App\Classes\ToastNotification;

// use App\Ace;
use App\Institution;

// // use App\Currency;
// // use App\Institution;
// // use App\VerificationLetter;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Crypt;

class MilestoneController extends Controller {
	//

	public function create() {
		$aces = Ace::all();
		$universities = Institution::where('university', '=', 1)->get();

		return view('settings.milestone', compact('aces', 'universities'));
	}

	public function save(Request $request) {
		$theFields = $request->fields;
		foreach ($theFields as $field) {
			Milestone::create($field);
		}
		notify(new ToastNotification('Successful!', 'The data has been added!', 'success'));
		return back();
	}


	public function deleteMilestone(Request $request){
	    dd($request->all());

    }

}

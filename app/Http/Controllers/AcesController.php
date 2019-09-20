<?php

namespace App\Http\Controllers;
use App\Ace;
use App\AceCourse;
use App\AceDlrIndicator;
use App\AceDlrIndicatorCost;
use App\Aceemail;
use App\AceIndicatorsBaseline;
use App\AceIndicatorsTarget;
use App\AceIndicatorsTargetYear;
use App\Classes\CommonFunctions;
use App\Classes\ToastNotification;
use App\Course;
use App\Currency;
use App\Indicator;
use App\IndicatorOne;
use App\Institution;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use vendor\project\StatusTest;
//use Illuminate\Support\Facades\Storage;
//use Illuminate\Support\Facades\File;
use File;



class AcesController extends Controller {
	//
	//
	public function __construct() {
		$this->middleware('auth');
	}


	public function index() {
		$aces = Ace::orderBy('name', 'ASC')->get();
		$currency = Currency::orderBy('name', 'ASC')->get();
		$universities = Institution::where('university', '=', 1)->orderBy('name', 'ASC')->get();
		return view('aces.index', compact('aces', 'universities','currency'));
	}


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request)
    {
            $this->validate($request, [
            'name' => 'required|string|min:3|unique:aces,name',
            'contact' => 'required|numeric|digits_between:10,17',
            'email' => 'required|string|email|min:3',
            'university' => 'required|integer|min:1',
            'field' => 'required|string',
            'active' => 'nullable|boolean',
            'currency' => 'required|numeric',
            'dlr' => 'nullable|numeric|min:0',
            'acronym' => 'required|string|min:2',
            'ace_type' => 'required|string|min:2',

]);


        $addAce = new Ace();
        $addAce->name = $request->name;
        $addAce->acronym = $request->acronym;
        $addAce->field = $request->field;
        $addAce->contact = $request->contact;
        $addAce->currency_id = $request->currency;
        $addAce->email = $request->email;
        $addAce->dlr = $request->dlr;
        $addAce->institution_id = $request->university;
        $addAce->active = $request->active;
        $addAce->ace_type = $request->ace_type;


        $addAce->save();

		if (isset($addAce->id)) {
			notify(new ToastNotification('Successful!', 'New ACE Added', 'success'));
			return redirect()->route('user-management.aces.profile', [Crypt::encrypt($addAce->id)]);
		} else {
			notify(new ToastNotification('Notice', 'Something might have happened. Please try again.', 'info'));
			return back();
		}
	}
	public function add_courses(Request $request,$id){
        $ace_id = Crypt::decrypt($id);
        $ace=Ace::find($ace_id);
        $ace->programmes.=";".$request->ace_programmes;
        $ace->save();
        if($ace->save()){
            notify(new ToastNotification('Successful!', 'Courses ACE Added', 'success'));
            return back();
        }else{
            notify(new ToastNotification('Notice', 'Something might have happened. Please try again.', 'info'));
            return back();
        }
    }
    public function delete_course($aceId,$course){
         $ace_id = Crypt::decrypt($aceId);
         $ace = Ace::find($ace_id);
         $all_programmes = explode(';',$ace->programmes);

        $key = array_search($course, $all_programmes);
        if (false !== $key) {
            unset($all_programmes[$key]);
        }
       $ace->programmes = implode(";",$all_programmes);
        $ace->save();
        if($ace->save()){
            notify(new ToastNotification('Successful!', 'Course Removed', 'success'));
            return back();
        }else{
            notify(new ToastNotification('Notice', 'Something might have happened. Please try again.', 'info'));
            return back();
        }


    }

    public function indicator_one($id)
    {
        $ace_id = Crypt::decrypt($id);

        $ace = Ace::find($ace_id);
        $all_aces = Ace::get();
        $getRequirements = Indicator::activeIndicator()->parentIndicator(1)->pluck('title');
            $indicatorOne = new CommonFunctions();
            $labels = $indicatorOne->getRequirementLabels(null);
            $indicator_ones =IndicatorOne::where('ace_id', '=', $ace_id)->get()->groupBy('requirement')->toArray();


//            dd($indicator_ones->groupBy('requirement'));
//            dd($indicator_ones);
            return view('aces.indicator-one', compact('ace', 'all_aces', 'indicator_ones','labels'));

    }

    public  function indicator_one_save(Request $request, $id)
    {
        $ace_id = Crypt::decrypt($id);
        $oldIndicator=IndicatorOne::find($ace_id);
//        dd(empty($oldIndicator));
        $requirement = $request->requirement;
        $submission_date = $request->submission_date;
        $file_one=$request->file_one;
        $file_two = $request -> file_two;
        $url = $request -> url;
        $comments = $request -> comments;
        $destinationPath = base_path() . '/public/indicator1/'; // upload path
        if(!empty($oldIndicator)) {
            $get_oldIndicator = IndicatorOne::where('ace_id', '=', $ace_id)->get();
            foreach ($oldIndicator as $key => $req) {
                $updateIndicatorOne = IndicatorOne::find($req->id);
                $updateind['requirement'] = $requirement[$key];
                $updateind['submission_date'] = $submission_date[$key];
                if ($request->has('file_one')) {
                    $file_one = $request->file_one;
                    if (isset($request->file('file_one')[$key])) {
                        if ($request->file('file_one')[$key] == "") {
                            $updateind['file_one'] = $req->file_one;
                        } else {
                            $file = $request->file('file_one')[$key];
                            $extension = $file->getClientOriginalExtension();

                            $updateind['file_one'] = $file->getClientOriginalName();
                        }
                    } else {
                        $updateind['file_one'] = $req->file_one;
                    }
                } else {
                    $updateind['file_one'] = $req->file_one;
                }
                if ($request->has('file_two')) {
                    $file_two = $request->file_one;
                    if (isset($request->file('file_two')[$key])) {

                        if ($request->file('file_two')[$key] == "") {
                            $updateind['file_two'] = $req->file_one;
                        } else {
                            $file = $request->file('file_two')[$key];
                            $extension = $file->getClientOriginalExtension();

                            $updateind['file_two'] = $file->getClientOriginalName();
                        }
                    } else {
                        $updateind['file_two'] = $req->file_two;
                    }
                } else {
                    $updateind['file_two'] = $req->file_two;
                }
                $files[] = $updateind['file_one'] . " " . $req->id;
                $files[] = $updateind['file_two'] . " " . $req->id;
                $updateind['url'] = $url[$key];
                $updateind['comments'] = $comments[$key];
                $files[] = $updateind;

                $saveIndicator = $updateIndicatorOne->update($updateind);


            }
        }
        else {
            $requirement = $request->requirement;
            foreach ($requirement as $key => $req) {
                $addIndicatorOne = new IndicatorOne();
                $submission_date = $request->submission_date[$key];
                $file_one = $request->file_one[$key];
                $file_two = $request->file_two[$key];
                $url = $request->url[$key];
                $comments = $request->comments[$key];
                $addIndicatorOne->ace_id = $ace_id;
                $addIndicatorOne->requirement = $requirement[$key];
                $addIndicatorOne->submission_date = $submission_date;
                $file1 = $request->file('file_one')[$key];
                $file2 = $request->file('file_two')[$key];
                if (isset($file1)) {
                    $file1->move($destinationPath, $file1->getClientOriginalName());
                    $addIndicatorOne->file_one = $file_one->getClientOriginalName();
                }
                if (isset($file2)) {
                    $file2->move($destinationPath, $file2->getClientOriginalName());
                    $addIndicatorOne->file_two = $file_two->getClientOriginalName();
                }
                $addIndicatorOne->url = $url;
                $addIndicatorOne->comments = $comments;
                $saveIndicator=$addIndicatorOne->save();
            }
        }


        if (isset($saveIndicator)) {
            notify(new ToastNotification('Successful!', 'Indicator 1 Requirement Added', 'success'));
            return back();
        } else {
            notify(new ToastNotification('Notice', 'Something might have happened. Please try again.', 'info'));
            return back();
        }

    }
	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
	public function edit_view(Request $request) {

		$id = Crypt::decrypt($request->id);
		$ace = Ace::find($id);
		$currency = Currency::orderBy('name', 'ASC')->get();
		$universities = Institution::where('university', '=', 1)->orderBy('name', 'ASC')->get();
        $indicator_ones=IndicatorOne::where('ace_id', '=', $id)->get();
        $requirements=Indicator::activeIndicator()->parentIndicator(1)->pluck('title');
		$view = view('aces.edit-view', compact('ace', 'universities','currency','requirements','indicator_ones'))->render();
		return response()->json(['theView' => $view, 'ace' => $ace, 'indicator_ones'=>$indicator_ones]);
	}

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
	public function update_ace(Request $request) {
		$id = Crypt::decrypt($request->id);
		$this->validate($request, [
			'id' => 'required|string|min:100',
			'name' => 'required|string|min:3|unique:aces,name,' . $id,
			'contact' => 'required|numeric|digits_between:10,17',
			'email' => 'required|string|email|min:3',
			'field' => 'required|string',
			'currency' => 'required|numeric',
			'dlr' => 'nullable|numeric|min:0',
			'university' => 'required|integer|min:1',
			'active' => 'nullable|boolean',
			'acronym' => 'required|string|min:2',
            'ace_type' =>'required|string|min:2',
		]);
		$addAce = Ace::find($id);

		$addAce->name = $request->name;
		$addAce->acronym = $request->acronym;
		$addAce->contact = $request->contact;
		$addAce->currency_id = $request->currency;
		$addAce->field = $request->field;
		$addAce->dlr = $request->dlr;
		$addAce->email = $request->email;
		$addAce->institution_id = $request->university;
		$addAce->active = $request->active;
        $addAce->ace_type = $request->ace_type;
        $addAce->save();

		notify(new ToastNotification('Successful!', 'ACE Updated!', 'success'));
		return back();
	}

	public function ace_page($id) {
		$id = Crypt::decrypt($id);

		$ace = Ace::find($id);
		$dlr_unit_costs = AceDlrIndicatorCost::where('ace_id', '=', $id)->pluck('unit_cost','ace_dlr_indicator_id');
		$dlr_max_costs = AceDlrIndicatorCost::where('ace_id', '=', $id)->pluck('max_cost','ace_dlr_indicator_id');
		$ace_dlrs = AceDlrIndicator::where('parent_id', '=', 0)->orderBy('order', 'asc')->get();

		$target_years = $ace->target_years;
//		dd($target_years);

		$aceemails = Aceemail::where('ace_id', '=', $id)->orderBy('email', 'asc')->get();
        $requirements=Indicator::activeIndicator()->parentIndicator(1)->pluck('title');

		return view('aces.profile', compact('ace','dlr_unit_costs', 'target_years',
            'ace_dlrs', 'aceemails', 'dlr_max_costs','requirements'));
	}

	public function baselines($id) {
		$ace_id = Crypt::decrypt($id);

		$ace = Ace::find($ace_id);
		$all_aces = Ace::get();
		$project = Project::find(1);
		$values = array();
		$getBaselines = AceIndicatorsBaseline::where('ace_id', '=', $ace_id)->pluck('baseline', 'indicator_id');
		if ($getBaselines->isNotEmpty()) {
			$values = $getBaselines;
		}

		return view('aces.baselines', compact('ace', 'project', 'all_aces', 'values'));
	}

	public function target_values($id, $year_id = null) {
		$ace_id = Crypt::decrypt($id);

		$ace = Ace::find($ace_id);
		$all_aces = Ace::get();
		$project = Project::find(1);
		$values = array();
        $getYear = null;
		if ($year_id != null) {
			$getYear = AceIndicatorsTargetYear::find($year_id);
			$getTargets = AceIndicatorsTarget::where('target_year_id', '=', $year_id)->pluck('target', 'indicator_id');

			if ($getTargets->isNotEmpty()) {
				$values = $getTargets;
			}
		}

		return view('aces.target_values', compact('ace', 'project', 'all_aces', 'values', 'year_id', 'getYear'));
	}

	public function baselines_save(Request $request, $id) {
		$ace_id = Crypt::decrypt($id);
		$getBaselines = AceIndicatorsBaseline::where('ace_id', '=', $ace_id)->get();
		if ($getBaselines->isNotEmpty()) {
			foreach ($request->indicators as $indicator => $baseline) {
				$getBaselines = AceIndicatorsBaseline::where('ace_id', '=', $ace_id)->where('indicator_id', '=', $indicator)->update([
					'baseline' => $baseline,
					'user_id' => Auth::id(),
				]);
			}
			notify(new ToastNotification('Successful', 'Baselines have been updated.', 'success'));
		} else {
			foreach ($request->indicators as $indicator => $baseline) {
				$ace_baseline = new AceIndicatorsBaseline();
				$ace_baseline->ace_id = $ace_id;
				$ace_baseline->indicator_id = $indicator;
				$ace_baseline->baseline = $baseline;
				$ace_baseline->user_id = Auth::id();
				$ace_baseline->save();
			}
			notify(new ToastNotification('Successful', 'Baselines have been saved.', 'success'));
		}

		return back();
	}

	public function targets_save(Request $request, $ace_id, $target_year_id = null) {
		$this->validate($request, [
			'start' => 'required|date',
			'end' => 'required|date',
			'indicators' => 'required|array',
		]);
		$aceId = Crypt::decrypt($ace_id);
		if ($target_year_id != null) {

			AceIndicatorsTargetYear::find($target_year_id)->update([
				'start_period' => $request->start,
				'end_period' => $request->end,
				'user_id' => Auth::id(),
			]);

			foreach ($request->indicators as $indicator => $target) {
				AceIndicatorsTarget::where('ace_id', '=', $aceId)
					->where('target_year_id', '=', $target_year_id)
					->where('indicator_id', '=', $indicator)
					->update([
						'target' => $target,
					]);
			}

			notify(new ToastNotification('Successful', 'Indicator Targets updated.', 'success'));
		} else {
			$target_year = new AceIndicatorsTargetYear();
			$target_year->ace_id = $aceId;
			$target_year->user_id = Auth::id();
			$target_year->start_period = $request->start;
			$target_year->end_period = $request->end;
			$target_year->save();

			foreach ($request->indicators as $indicator => $target) {
				AceIndicatorsTarget::create([
					'ace_id' => $aceId,
					'target_year_id' => $target_year->id,
					'indicator_id' => $indicator,
					'target' => $target,
				]);
			}

			notify(new ToastNotification('Successful', 'Indicator Targets added.', 'success'));
		}
		return back();
	}
}

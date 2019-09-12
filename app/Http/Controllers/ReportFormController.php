<?php

namespace App\Http\Controllers;

use App\Ace;
use App\Classes\CommonFunctions;
use App\Classes\ToastNotification;
use App\Indicator;
use App\Project;
use App\Report;
use App\ReportIndicatorsStatus;
use App\ReportStatusTracker;
use App\ReportValue;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ReportFormController extends Controller {
	//
	public function __construct() {
		$this->middleware('auth');
	}

	/**
	 *Shows all submitted the reports
	 */
	public function index() {
		$me = new CommonFunctions();
		if (Auth::user()->hasRole('webmaster|super-admin')) {
			$ace_reports = Report::get();
		} elseif (Auth::user()->hasRole('admin')) {
			$ace_reports = Report::submitted()->get();
		} else {
			$ace_reports = Report::SubmittedAndUncompleted()->where('user_id', '=', Auth::id())->get();
		}
		return view('report-form.index', compact('ace_reports', 'me'));
	}

	/**
	 *Shows all the reports (Archives)
	 */
	public function archive() {
//        if (Auth::user()->hasRole('webmaster|admin|super-admin')){
		$ace_reports = Report::get();
//        }else{
		//            $ace_reports = Report::where('user_id','=', Auth::id())->get();
		//        }
		//        return view('report-form.archive', compact('ace_reports'));
	}

	/**
	 *Add new report form
	 */
	public function add_report() {
		$me = new CommonFunctions();
//		dd($me->isSubmissionOpen());
		$project = Project::where('id', '=', 1)->where('status', '=', 1)->first();
		$indicators = Indicator::where('is_parent','=', 1)->where('status','=', 1)->where('upload','=', 1)->orderBy('identifier','asc')->get();
		$aces = Ace::where('active', '=', 1)->get();
		$ace_officers = User::join('role_user', 'users.id', '=', 'role_user.user_id')
			->join('roles', 'role_user.role_id', '=', 'roles.id')
			->where('roles.name', '=', 'ace-officer')->pluck('users.name', 'users.id');

		if ($project) {
			return view('report-form.new', compact('project', 'aces', 'me', 'ace_officers','indicators'));
		} else {
			notify(new ToastNotification('Notice!', 'Please add the project first!', 'warning'));
			return back();
		}
	}

	/**
	 *Save new report
	 */
	public function save_report(Request $request) {
		if (isset($request->ace_officer)) {
			$ace_id = User::find(Crypt::decrypt($request->ace_officer))->ace;
		} else {
			$ace_id = Auth::user()->ace;
		}
		$exist = Report::where('project_id', '=', Crypt::decrypt($request->project_id))
			->where('ace_id', '=', $ace_id)
			->where('start_date', '=', $request->start)
			->where('end_date', '=', $request->end)
			->first();
		if ($exist) {
			notify(new ToastNotification('Duplicate', 'The report already exist.', 'error'));
			return back()->withInput();
		}

		if (isset($request->submit)) {
			$this->validate($request, [
				'project_id' => 'required|string|min:100',
				'indicators' => 'required|array|min:1',
				'indicators.*' => 'required|numeric|min:0',
				'start' => 'required|string|date',
				'end' => 'required|string|date',
				'submission_date' => 'nullable|string|date',
			]);

			DB::transaction(function () use ($request) {

				if (isset($request->ace_officer)) {
					$ace_id = User::find(Crypt::decrypt($request->ace_officer))->ace;
				} else {
					$ace_id = Auth::user()->ace;
				}
				$indicators = Project::find(1);
				$parent_indicators = $indicators->indicators->where('parent_id', '=', 0)->where('status', '=', 1);

				$submission_date = $request->submission_date;
				if ($submission_date == null) {
					$submission_date = date('Y-m-d');
				}

//                $ace_id = $ace_id;
				$project_id = Crypt::decrypt($request->project_id);
				$report = new Report();
				$report->project_id = $project_id;
				$report->ace_id = $ace_id;
				$report->status = 1;
				$report->start_date = $request->start;
				$report->end_date = $request->end;
				$report->submission_date = $submission_date;
				if (isset($request->ace_officer)) {
					$report->user_id = Crypt::decrypt($request->ace_officer);
				} else {
					$report->user_id = Auth::id();
				}
				$report->save();

				foreach ($request->indicators as $indicator => $value) {
					$report_values = new ReportValue();
					$report_values->report_id = $report->id;
					$report_values->indicator_id = $indicator;
					$report_values->value = $value;
					$report_values->save();
				}

				foreach ($parent_indicators as $parent_indicator) {
					$indicator_st = new ReportIndicatorsStatus();
					$indicator_st->report_id = $report->id;
					$indicator_st->indicator_id = $parent_indicator->id;
					$indicator_st->status = 1;
					$indicator_st->status_date = $submission_date;
					$indicator_st->save();
				}

				ReportStatusTracker::create([
					'report_id' => $report->id,
					'status_code' => 1,
				]);
				notify(new ToastNotification('Successful!', 'Report Submitted!', 'success'));
			});
		} else {
			$this->validate($request, [
				'project_id' => 'required|string|min:100',
				'start' => 'required|string|date',
				'end' => 'required|string|date',
				'submission_date' => 'nullable|string|date',
			]);
			DB::transaction(function () use ($request) {
				if (isset($request->ace_officer)) {
					$ace_id = User::find(Crypt::decrypt($request->ace_officer))->ace;
				} else {
					$ace_id = Auth::user()->ace;
				}
				$indicators = Project::find(1);
				$parent_indicators = $indicators->indicators->where('parent_id', '=', 0)->where('status', '=', 1);

				$submission_date = $request->submission_date;
				if ($submission_date == null) {
					$submission_date = date('Y-m-d');
				}

				$project_id = Crypt::decrypt($request->project_id);
				$report = new Report();
				$report->project_id = $project_id;
				$report->ace_id = $ace_id;
				$report->start_date = $request->start;
				$report->end_date = $request->end;
				$report->submission_date = $submission_date;
				$report->status = 99;
				if (isset($request->ace_officer)) {
					$report->user_id = Crypt::decrypt($request->ace_officer);
				} else {
					$report->user_id = Auth::id();
				}
				$report->save();

				foreach ($request->indicators as $indicator => $value) {
					$report_values = new ReportValue();
					$report_values->report_id = $report->id;
					$report_values->indicator_id = $indicator;
					$report_values->value = $value;
					$report_values->save();
				}

				foreach ($parent_indicators as $parent_indicator) {
					$indicator_st = new ReportIndicatorsStatus();
					$indicator_st->report_id = $report->id;
					$indicator_st->indicator_id = $parent_indicator->id;
					$indicator_st->status = 99;
					$indicator_st->save();
				}

				ReportStatusTracker::create([
					'report_id' => $report->id,
					'status_code' => 99,
				]);
				notify(new ToastNotification('Successful!', 'Report saved.', 'success'));
			});

            if (isset($request->toIndicators)){
                $exist = Report::where('project_id', '=', Crypt::decrypt($request->project_id))
                    ->where('ace_id', '=', $ace_id)
                    ->where('start_date', '=', $request->start)
                    ->where('end_date', '=', $request->end)
                    ->first();
                return redirect()->route('report_submission.upload_indicator',[Crypt::encrypt($exist->id)]);
            }
		}
		return redirect()->route('report_submission.reports');
	}

	/**
	 *Save and Continue report
	 */
	public function save_continue_report(Request $request) {
		$this->validate($request, [
			'ace_id' => 'required|string|min:100',
			'project_id' => 'required|string|min:100',
			'start' => 'required|string|date',
			'end' => 'required|string|date',
			'submission_date' => 'nullable|string|date',
		]);
		if (isset($request->ace_officer)) {
			$ace_id = User::find(Crypt::decrypt($request->ace_officer))->ace;
		} else {
			$ace_id = Auth::user()->ace;
		}
		$exist = Report::where('project_id', '=', Crypt::decrypt($request->project_id))
			->where('ace_id', '=', $ace_id)
			->where('start_date', '=', $request->start)
			->where('end_date', '=', $request->end)
			->first();
		if ($exist) {
			notify(new ToastNotification('Duplicate', 'The report already exist.', 'error'));
			return back()->withInput();
		}

		$indicators = Project::find(1);
		$parent_indicators = $indicators->indicators->where('parent_id', '=', 0)->where('status', '=', 1);

		$submission_date = $request->submission_date;
		if ($submission_date == null) {
			$submission_date = date('Y-m-d');
		}
//        return $submission_date;
		$ace_id = Crypt::decrypt($request->ace_id);
		$project_id = Crypt::decrypt($request->project_id);
		$report = new Report();
		$report->project_id = $project_id;
		$report->ace_id = $ace_id;
		$report->start_date = $request->start;
		$report->end_date = $request->end;
		$report->submission_date = $submission_date;
		$report->status = 99;
		if (isset($request->ace_officer)) {
			$report->user_id = Crypt::decrypt($request->ace_officer);
		} else {
			$report->user_id = Auth::id();
		}
		$report->save();

		foreach ($request->indicators as $indicator => $value) {
			$report_values = new ReportValue();
			$report_values->report_id = $report->id;
			$report_values->indicator_id = $indicator;
			$report_values->value = $value;
			$report_values->save();
		}

		foreach ($parent_indicators as $parent_indicator) {
			$indicator_st = new ReportIndicatorsStatus();
			$indicator_st->report_id = $report->id;
			$indicator_st->indicator_id = $parent_indicator->id;
			$indicator_st->status = 99;
			$indicator_st->save();
		}

		ReportStatusTracker::create([
			'report_id' => $report->id,
			'status_code' => 99,
		]);
		notify(new ToastNotification('Successful!', 'Report Saved!', 'success'));
		return redirect()->route('report_submission.reports');
	}

	/**
	 *View report
	 */
	public function view_report($id) {
		$id = Crypt::decrypt($id);
		$project = Project::where('id', '=', 1)->where('status', '=', 1)->first();
		$report = Report::find($id);
		if (Auth::id() == $report->user_id || Auth::user()->hasRole(['webmaster|super-admin|admin|manager'])){

            $values = ReportValue::where('report_id', '=', $id)->pluck('value', 'indicator_id');
            $aces = Ace::where('active', '=', 1)->get();
            return view('report-form.view', compact('project', 'report', 'aces', 'values', 'ace_officers'));

		}else{
            notify(new ToastNotification('Sorry!', 'The report does not exist', 'alert'));
		}

	}

	/**
	 * Indicator report
	 * @param $id
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function indicators_status($id) {
		$id = Crypt::decrypt($id);
		$project = Project::where('id', '=', 1)->where('status', '=', 1)->first();
		$report = Report::find($id);
		$current_status = ReportIndicatorsStatus::where('report_id', '=', $id)->orderBy('status_date', 'desc')->get();
		$status_history = ReportIndicatorsStatus::where('report_id', '=', $id)->get();
		$all_status = new CommonFunctions();
		$aces = Ace::where('active', '=', 1)->get();
		return view('report-form.report-indicator-status', compact('project', 'report', 'aces',
			'current_status', 'all_status', 'status_history'));
	}

	public function delete($id) {
		$id = Crypt::decrypt($id);
		Report::destroy($id);

		notify(new ToastNotification('Successful!', 'Report Deleted!', 'success'));
		return back();

	}

	/**
	 * @param Request $request
	 * @param $report_id
	 * @param $indicator_id
	 * @return array
	 */
	public function indicators_status_save(Request $request, $report_id, $indicator_id) {
		$this->validate($request, [
			'status_label' => 'required|numeric',
			'status_rep' => 'required|numeric',
			'sub_date' => 'required|date',
		]);
		ReportIndicatorsStatus::updateOrCreate([
			'report_id' => Crypt::decrypt($report_id),
			'indicator_id' => $indicator_id,
			'status' => $request->status_label,
		], [
			'responsibility' => $request->status_rep,
			'status_date' => $request->sub_date,
		]);
		notify(new ToastNotification('Successful!', 'Report Indicator Changed.', 'success'));
		return back();
	}
	public function report_status_save(Request $request, $report_id) {
		$this->validate($request, [
			'status_label' => 'required|numeric',
			'sub_date' => 'required|date',
		]);

		$setReport = Report::find(Crypt::decrypt($report_id));
		$setReport->status = $request->status_label;
		if ($request->status_label == 99){
            $setReport->editable = 1;
        }else{
            $setReport->editable = 0;
        }
		$setReport->save();

        $setTracker = ReportStatusTracker::updateOrCreate([
            'report_id' => Crypt::decrypt($report_id),
            'status_code' => $request->status_label,
        ], [
            'status_date' => $request->sub_date,
        ]);
		if ($setTracker){
            notify(new ToastNotification('Successful!', 'Report Status Updated.', 'success'));
        }
        else{
            notify(new ToastNotification('Sorry!', 'Something went wrong with the update.', 'warning'));

        }
		return back();
	}

	/**
	 * Edit report
	 * @param $id
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit_report($id) {
//        return $id;
		$id = Crypt::decrypt($id);
		$project = Project::where('id', '=', 1)->where('status', '=', 1)->first();
		$report = Report::find($id);
		if ($report->editable <= 0 && Auth::user()->hasRole('ace-officer')){
            notify(new ToastNotification('Sorry!', 'This report is unavailable for editing!', 'warning'));
            return redirect()->route('report_submission.reports');
        }
		$values = ReportValue::where('report_id', '=', $id)->pluck('value', 'indicator_id');
//        return $values;
		$ace_officers = User::join('role_user', 'users.id', '=', 'role_user.user_id')
			->join('roles', 'role_user.role_id', '=', 'roles.id')
			->where('roles.name', '=', 'ace-officer')->pluck('users.name', 'users.id');
		$aces = Ace::where('active', '=', 1)->get();
		return view('report-form.edit', compact('project', 'report', 'aces', 'values', 'ace_officers'));
	}

	/**
	 * Update report
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update_report(Request $request) {
		if (isset($request->submit)) {
			DB::transaction(function () use ($request) {
				$this->validate($request, [
					'report_id' => 'required|string|min:100',
					'indicators' => 'required|array|min:1',
					'indicators.*' => 'required|numeric|min:0',
					'start' => 'required|string|date',
					'end' => 'required|string|date',
				]);

				$report_id = Crypt::decrypt($request->report_id);

				$report = Report::find($report_id);
				$report->start_date = $request->start;
				$report->end_date = $request->end;
				$report->status = 1;
				if (isset($request->ace_officer)) {
					$ace_id = User::find(Crypt::decrypt($request->ace_officer))->ace;
					$report->user_id = Crypt::decrypt($request->ace_officer);
					$report->ace_id = $ace_id;
				} else {
					$report->user_id = Auth::id();
				}
				$report->save();

				foreach ($request->indicators as $indicator => $value) {
					ReportValue::where('report_id', '=', $report_id)
						->where('indicator_id', '=', $indicator)
						->update(['value' => $value]);
				}

				ReportIndicatorsStatus::where('report_id', '=', $report_id)->update(['status' => 1]);
				ReportStatusTracker::where('report_id', '=', $report_id)->update(['status_code' => 1]);
				notify(new ToastNotification('Successful!', 'Report Submitted!', 'success'));
			});
			return redirect()->route('report_submission.upload_indicator', [$request->report_id]);
		}
		else {
			$this->validate($request, [
				'report_id' => 'required|string|min:100',
				'start' => 'required|string|date',
				'end' => 'required|string|date',
			]);
			DB::transaction(function () use ($request) {
				$report_id = Crypt::decrypt($request->report_id);

				$report = Report::find($report_id);
				$report->start_date = $request->start;
				$report->end_date = $request->end;
				$report->status = 0;
				if (isset($request->ace_officer)) {
					$ace_id = User::find(Crypt::decrypt($request->ace_officer))->ace;
					$report->user_id = Crypt::decrypt($request->ace_officer);
					$report->ace_id = $ace_id;
				}
				else {
					$report->user_id = Auth::id();
				}
				$report->save();

				foreach ($request->indicators as $indicator => $value) {

					ReportValue::updateOrCreate([
						'report_id' => $report_id,
						'indicator_id' => $indicator,
					], [
						'value' => $value,
					]);
				}

				notify(new ToastNotification('Successful!', 'Report Saved!', 'success'));
			});
            if (isset($request->toIndicators)){
                return redirect()->route('report_submission.upload_indicator',$request->report_id);
            }

			return redirect()->route('report_submission.reports');
		}
	}

	/**
	 * Report Edit and Review Status Check
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	function report_review($id) {

		$report = Report::find($id);
		if ($report->editable == 1) {
			$report->editable = 0;
			$report->save();
			$message = "Review mode has been enabled.";
			$note = "In Review Mode";
			$status = 0;
			$btnclass = "btn-secondary";
		} else {
			$report->editable = 1;
			$report->save();
			$message = "Review mode has been disabled.";
			$note = "In Edit Mode";
			$status = 1;
			$btnclass = "btn-primary";
		}

		return response()->json(['message' => $message, 'note' => $note, 'btnclass' => $btnclass, 'status' => $status]);
	}
}

<?php

namespace App\Http\Controllers;

use App\Ace;
use App\AceComment;
use App\Classes\CommonFunctions;
use App\Classes\ToastNotification;
use App\Indicator;
use App\Indicator3;
use App\IndicatorDetails;
use App\Notifications\ReportSubmission;
use App\Project;
use App\Report;
use App\ReportIndicatorsStatus;
use App\ReportingPeriod;
use App\ReportStatusTracker;
use App\ReportValue;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;


class ReportFormController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *Shows all submitted the reports
     */
    public function index()
    {
        $me = new CommonFunctions();
        $notsubmitted = False;
        if (Auth::user()->hasRole('ace-officer')) {
            $notcompleted = Report::Uncompleted()->where('user_id', '=', Auth::id())->get();
            if (!empty($notcompleted)) {
                $notsubmitted = True;
            }
        }

        $periods = ReportingPeriod::orderBy('id', 'desc')->get();


        return view('report-form.index', compact('me', 'notsubmitted', 'periods'));
    }

    public static function aceReports($reporting_period)
    {
        if (Auth::user()->hasRole('webmaster|super-admin')) {
            $ace_reports = Report::where('reporting_period_id', '=', $reporting_period)->orderBy('reporting_period_id', 'desc')->get();
        } elseif (Auth::user()->hasRole('admin')) {
            $ace_reports = Report::where('reporting_period_id', '=', $reporting_period)->submitted()->orderBy('reporting_period_id', 'desc')->get();
        } else {
            $ace_reports = Report::where('reporting_period_id', '=', $reporting_period)->SubmittedAndUncompleted()->where('user_id', '=', Auth::id())->orderBy('reporting_period_id', 'desc')->get();
        }
        return $ace_reports;
    }

    /**
     *Shows all the reports (Archives)
     */
    public function archive()
    {

    }

    /**
     *Add new report form
     */
    public function add_report()
    {
        $me = new CommonFunctions();
        $project = Project::where('id', '=', 1)->where('status', '=', 1)->first();
        $indicators = Indicator::where('parent_id','=', 0)
            ->where('is_parent', '=', 1)
            ->where('status', '=', 1)
            ->orderBy('identifier','asc')
            ->get();
        $aces = Ace::where('active', '=', 1)->get();
        $ace_officers = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('roles.name', '=', 'ace-officer')->pluck('users.name', 'users.id');

        $reporting_periods = ReportingPeriod::all();
        $active_period = ReportingPeriod::where('active_period', '=', true)->get();

        if ($project) {
            return view('report-form.new', compact('project', 'aces', 'me', 'ace_officers', 'indicators', 'reporting_periods', 'active_period'));
        } else {
            notify(new ToastNotification('Notice!', 'Please add the project first!', 'warning'));
            return back();
        }
    }

    /**
     * Save new report
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save_report(Request $request)
    {

        $this->validate($request, [
            'project_id' => 'required|string|min:100',
            'reporting_period' => 'required|string',
            'submission_date' => 'nullable|string|date',
            "indicator" => "required|numeric|min:1",
            "language" => "required|string",
        ]);
        if ($request->has('ace_officer')) {
            $this->validate($request, [
                'ace_officer' => 'required',
            ]);
        }
        $report_id = null;
        if (isset($request->ace_officer)) {
            $ace_id = User::find(Crypt::decrypt($request->ace_officer))->ace;
        } else {
            $ace_id = Auth::user()->ace;
        }
        $submission_date = $request->submission_date;
        if ($submission_date == null) {
            $submission_date = date('Y-m-d');
        }
        $project_id = Crypt::decrypt($request->project_id);
        $dlr_id = $request->indicator;

        #since dlrs 4.1,5.1,5.3 are submitted as at when, allow users to submit as many full reports of this DLR as possible.
        $report_exists = Report::where('ace_id', $ace_id)->where('reporting_period_id', $request->reporting_period)
           ->join('indicators','indicator_id','=','indicators.id')
            ->where('indicator_id', '=', $dlr_id)
//            ->where('language', '=', $request->language)
            ->where(function ($query) {
                $query->where('indicators.identifier', '!=', '4.1')
                    ->orWhere('indicators.identifier', '!=', '5.1')
                    ->orWhere('indicators.identifier', '!=', '5.3');

            })
            ->get();

        if (empty($report_exists)) {
//            if ($report_exists->status != 1) {
//                notify(new ToastNotification('Error!', 'You have a pending report on this DLR and period- Go and submit!', 'error'));
//            }
            notify(new ToastNotification('Error!', 'A report with similar DLR & Reporting period has already been created!', 'error'));
            return back()->withInput();
        }
        $report = new Report();
        $report->project_id = $project_id;
        $report->ace_id = $ace_id;
        $report->status = 99;
        $report->reporting_period_id = $request->reporting_period;
        $report->submission_date = date('y-m-d', strtotime($submission_date));
        $report->indicator_id = $dlr_id;
        $report->language = $request->language;
        if (isset($request->ace_officer)) {
            $report->user_id = Crypt::decrypt($request->ace_officer);
        } else {
            $report->user_id = Auth::id();
        }
        $report->save();
        $report_id = $report->id;

        ReportStatusTracker::create([
            'report_id' => $report->id,
            'status_code' => 99,
        ]);


        notify(new ToastNotification('Successful!', 'Report Saved!', 'success'));

        return redirect()->route('report_submission.upload_indicator', [\Illuminate\Support\Facades\Crypt::encrypt($report_id)]);
    }

    public function save_report_old(Request $request)
    {
        if (isset($request->ace_officer)) {
            $ace_id = User::find(Crypt::decrypt($request->ace_officer))->ace;
        } else {
            $ace_id = Auth::user()->ace;
        }
        $exist = Report::where('project_id', '=', Crypt::decrypt($request->project_id))
            ->where('ace_id', '=', $ace_id)
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
                'reporting_period_id' => 'required|string|min:100',
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
                $report->status = 1;
                $report->editable = False;
                $report->reporting_period_id = $request->reporting_period;
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
                'reporting_period_id' => 'required|string',
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
                $report->reporting_period_id = $request->reporting_period;
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

            if (isset($request->toIndicators)) {
                $exist = Report::where('project_id', '=', Crypt::decrypt($request->project_id))
                    ->where('ace_id', '=', $ace_id)
                    ->where('reporting_period_id', '=', $request->reporting_period)
                    ->first();
                return redirect()->route('report_submission.upload_indicator', [Crypt::encrypt($exist->id)]);
            }
        }
        return redirect()->route('report_submission.reports');
    }

    /**
     *Save and Continue report
     */
    public function save_continue_report(Request $request)
    {
        $this->validate($request, [
            'ace_id' => 'required|string|min:100',
            'project_id' => 'required|string|min:100',
            'submission_date' => 'nullable|string|date',
        ]);
        if (isset($request->ace_officer)) {
            $ace_id = User::find(Crypt::decrypt($request->ace_officer))->ace;
        } else {
            $ace_id = Auth::user()->ace;
        }
        $exist = Report::where('project_id', '=', Crypt::decrypt($request->project_id))
            ->where('ace_id', '=', $ace_id)
            ->where('reporting_period_id', '=', $request->reporting_period)
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

        $ace_id = Crypt::decrypt($request->ace_id);
        $project_id = Crypt::decrypt($request->project_id);
        $report = new Report();
        $report->project_id = $project_id;
        $report->ace_id = $ace_id;
        $report->reporting_period_id = $request->reporting_period;
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
    public function view_report($id)
    {
        $id = Crypt::decrypt($id);
        $project = Project::where('id', '=', 1)->where('status', '=', 1)->first();
        $report = Report::find($id);

        $get_form = IndicatorDetails::query()
            ->where('report_id', '=', $id)
            ->orderBy('order', 'asc')
            ->pluck('language');


        $reporting_period = ReportingPeriod::find($report->reporting_period_id);
        $reporting_periods = ReportingPeriod::all();

        if ($report->editable == 0 && Auth::user()->hasRole('ace-officer')) {
            notify(new ToastNotification('Sorry!', 'This report is unavailable for editing!', 'warning'));
            return redirect()->route('report_submission.reports');
        }
        $values = ReportValue::where('report_id', '=', $id)->pluck('value', 'indicator_id');


        $indicators = Indicator::where('id', '=', $report->indicator_id)->orderBy('identifier', 'asc')->first();


        $comment = AceComment::where('report_id', $id)->first();

        $language = collect($get_form)->toArray();

        if (in_array('french', collect($get_form)->toArray())) {
            $pdo_1 = $this->generateAggregatedIndicator3Results_fr($id);
            $pdo_2 = $this->generateAggregatedIndicator73Results_fr($id);
            $pdo_52 = $this->generateAggregatedIndicator52Results_fr($id);
            $pdo_41 = $this->generateAggregatedIndicator41Results_fr($id);
            $pdo_42 = $this->generateAggregatedIndicator42Results_fr($id);
            $pdo_51 = $this->generateAggregatedIndicator51Results_fr($id);


        }
        $pdo_1 = $this->generateAggregatedIndicator3Results($id);
        $pdo_2 = $this->generateAggregatedIndicator73Results($id);
        $pdo_52 = $this->generateAggregatedIndicator52Results($id);
        $pdo_41 = $this->generateAggregatedIndicator41Results($id);
        $pdo_42 = $this->generateAggregatedIndicator42Results($id);
        $pdo_51 = $this->generateAggregatedIndicator51Results($id);

        $ace_officers = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('roles.name', '=', 'ace-officer')->pluck('users.name', 'users.id');
        $aces = Ace::where('active', '=', 1)->get();
        $the_indicator = $indicators;

        return view('report-form.view', compact('project', 'language', 'reporting_period',
            'reporting_periods', 'report', 'aces', 'comment', 'values', 'ace_officers',
            'indicators', 'the_indicator', 'pdo_1', 'pdo_41', 'pdo_2', 'pdo_52','pdo_42','pdo_51'));

    }

    /**
     * Indicator report
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indicators_status($id)
    {
        $id = Crypt::decrypt($id);
        $project = Project::where('id', '=', 1)->where('status', '=', 1)->first();
        $report = Report::find($id);
        $current_status = ReportIndicatorsStatus::where('report_id', '=', $id)->orderBy('status_date', 'desc')->get();
        $status_history = ReportIndicatorsStatus::where('report_id', '=', $id)->get();
        $all_status = new CommonFunctions();

        $indicators = Indicator::where('is_parent', '=', 1)
            ->where('status', '=', 1)
            ->where('show_on_report', '=', 1)
            ->orderBy('identifier', 'asc')
            ->get();
        $aces = Ace::where('active', '=', 1)->get();
        return view('report-form.report-indicator-status', compact('project', 'report', 'aces',
            'current_status', 'all_status', 'status_history', 'indicators'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $id = Crypt::decrypt($id);
        Report::destroy($id);

        notify(new ToastNotification('Successful!', 'Report Deleted!', 'success'));
        return back();

    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function downloadReportFile($report_id)
    {
        $id = Crypt::decrypt($report_id);
        $report = Report::find($id);

        return Storage::download($report->report_upload->file_path);
    }

    /**
     * @param Request $request
     * @param $report_id
     * @param $indicator_id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function indicators_status_save(Request $request, $report_id, $indicator_id)
    {
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

    /**
     * @param Request $request
     * @param $report_id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function report_status_save(Request $request, $report_id)
    {
        $this->validate($request, [
            'status_label' => 'required|numeric',
            'sub_date' => 'required|date',
        ]);

        $setReport = Report::find(Crypt::decrypt($report_id));
        $setReport->status = $request->status_label;
        if ($request->status_label == 99) {
            $setReport->editable = 1;
        } else {
            $setReport->editable = 0;
        }
        $setReport->save();

        $setTracker = ReportStatusTracker::updateOrCreate([
            'report_id' => Crypt::decrypt($report_id),
            'status_code' => $request->status_label,
        ], [
            'status_date' => $request->sub_date,
        ]);
        if ($setTracker) {
            notify(new ToastNotification('Successful!', 'Report Status Updated.', 'success'));
        } else {
            notify(new ToastNotification('Sorry!', 'Something went wrong with the update.', 'warning'));

        }
        return back();
    }

    /**
     * Edit report
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit_report($id)
    {
        $id = Crypt::decrypt($id);
        $report = Report::find($id);
        if ($report->editable == 0) {
            notify(new ToastNotification('Sorry!', 'This report is unavailable for editing!', 'warning'));
            return redirect()->route('report_submission.reports');
        }
        $project = Project::where('id', '=', 1)->where('status', '=', 1)->first();

        $get_form = IndicatorDetails::query()
            ->where('report_id', '=', $id)
            ->orderBy('order', 'asc')
            ->pluck('language');


        $reporting_period = ReportingPeriod::find($report->reporting_period_id);
        $reporting_periods = ReportingPeriod::all();
        $values = ReportValue::where('report_id', '=', $id)->pluck('value', 'indicator_id');

        $indicators = Indicator::where('id', '=', $report->indicator_id)->orderBy('identifier', 'asc')->first();
        $the_indicator = $indicators;

        $comment = AceComment::where('report_id', $id)->first();

        $language = collect($get_form)->toArray();
        $pdo_41 = $pdo_2 = [];

        if (in_array('french', collect($get_form)->toArray())) {
            $pdo_1 = $this->generateAggregatedIndicator3Results_fr($id);
            $pdo_2 = $this->generateAggregatedIndicator73Results_fr($id);
            $pdo_52 = $this->generateAggregatedIndicator52Results_fr($id);
            $pdo_41 = $this->generateAggregatedIndicator41Results_fr($id);
            $pdo_42 = $this->generateAggregatedIndicator42Results_fr($id);
            $pdo_51 = $this->generateAggregatedIndicator51Results_fr($id);
        }
        else {
            $pdo_1 = $this->generateAggregatedIndicator3Results($id);
            $pdo_2 = $this->generateAggregatedIndicator73Results($id);
            $pdo_52 = $this->generateAggregatedIndicator52Results($id);
            $pdo_41 = $this->generateAggregatedIndicator41Results($id);
            $pdo_42 = $this->generateAggregatedIndicator42Results($id);
            $pdo_51 = $this->generateAggregatedIndicator51Results($id);
        }

        $ace_officers = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('roles.name', '=', 'ace-officer')->pluck('users.name', 'users.id');
        $aces = Ace::where('active', '=', 1)->get();

        return view('report-form.edit', compact('project', 'language', 'reporting_period', 'reporting_periods', 'report', 'aces', 'comment', 'values', 'ace_officers',
            'indicators', 'the_indicator', 'pdo_1', 'pdo_41', 'pdo_2', 'pdo_52','pdo_42','pdo_51'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update_report(Request $request)
    {

        if (isset($request->submit)) {
            $report_id = Crypt::decrypt($request->report_id);
            $report = Report::find($report_id);
            $report->reporting_period_id = $request->reporting_period;

            $report->status = 1;
            $identifier = $report->indicator->identifier;
            $message = null;
            if ($report->language == 'french') {
                if ($identifier == "3") {
                    $message = $this->generateAggregatedIndicator3Results_fr($report_id,true);
                    session()->flash('message',$message[0]);
                    return back();
                }
                elseif ($identifier == "4.2") {
                    $message = $this->generateAggregatedIndicator42Results_fr($report_id,true);
                }
                elseif ($identifier == "5.1") {
                    $message = $this->generateAggregatedIndicator51Results_fr($report_id,true);
                }
                elseif ($identifier == "5.2") {
                    $message = $this->generateAggregatedIndicator52Results_fr($report_id,true);
                }
                elseif ($identifier == "7.3") {
                    $message = $this->generateAggregatedIndicator73Results_fr($report_id,true);
                }
            }
            else {
                if ($identifier == "3") {
                    $message = $this->generateAggregatedIndicator3Results($report_id,true);
                }
                elseif ($identifier == "4.2") {
                    $message = $this->generateAggregatedIndicator42Results($report_id,true);
                }
                elseif ($identifier == "5.1") {
                    $message = $this->generateAggregatedIndicator51Results($report_id, true);
                }
                elseif ($identifier == "5.2") {
                    $message = $this->generateAggregatedIndicator52Results($report_id,true);
                }
                elseif ($identifier == "7.3") {
                    $message = $this->generateAggregatedIndicator73Results($report_id,true);}
            }

            if (isset($message)) {
                session()->flash('message',$message[0]);
                return back();
            }

            DB::transaction(function () use ($request,$report,$report_id) {
                $this->validate($request, [
                    'report_id' => 'required|string|min:100',
                    'indicators' => 'required|array|min:1',
                    'indicators.*' => 'required|numeric|min:0',
                ]);

                if (isset($request->ace_officer)) {
                    $ace_id = User::find(Crypt::decrypt($request->ace_officer))->ace;
                    $report->user_id = Crypt::decrypt($request->ace_officer);
                    $report->ace_id = $ace_id;
                    $report->editable = false;
                } else {
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

                ReportIndicatorsStatus::where('report_id', '=', $report_id)->update(['status' => 1]);
                ReportStatusTracker::where('report_id', '=', $report_id)->update(['status_code' => 1]);

                $user_id = Auth::user()->id;
                $comment_object = AceComment::where('report_id', $report_id)->first();
                if (isset($request->report_comment)) {
                    if ($comment_object) {
                        $comment_object->update([
                            'user_id' => $user_id,
                            'report_id' => $report_id,
                            'comments' => $request->report_comment,
                        ]);
                    } else {
                        AceComment::updateorCreate([
                            'user_id' => $user_id,
                            'report_id' => $report_id,
                            'comments' => $request->report_comment,
                        ]);
                    }

                }
                $email_ace = Ace::query()->where('id', $report->ace_id);

                $emails = array_merge($email_ace->pluck('email')->toArray(), [config('mail.aau_email')]);


               Mail::send('mail.report-mail', ['the_ace' => $email_ace, 'report' => $report],
                    function ($message) use ($emails) {
                        $message->to($emails)
                            ->subject("Report Submitted");
                    });
               notify(new ToastNotification('Successful!', 'Report Submitted!', 'success'));
            });
            return redirect()->route('report_submission.reports');
        }
        else {
            $this->validate($request, [
                'report_id' => 'required|string|min:100',
            ]);
            DB::transaction(function () use ($request) {
                $report_id = Crypt::decrypt($request->report_id);
                $report = Report::find($report_id);
                $report->reporting_period_id = $request->reporting_period;
                $report->status = 99;
                $report->editable = true;
                if (isset($request->ace_officer)) {
                    $ace_id = User::find(Crypt::decrypt($request->ace_officer))->ace;
                    $report->user_id = Crypt::decrypt($request->ace_officer);
                    $report->ace_id = $ace_id;
                } else {
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
                $parent_indicators = Indicator::where('is_parent', '=', 1)
                    ->where('project_id', '=', 1)
                    ->where('status', '=', 1)
                    ->where('show_on_report', '=', 1)
                    ->orderBy('identifier', 'asc')
                    ->get();

                foreach ($parent_indicators as $parent_indicator) {
                    ReportIndicatorsStatus::updateOrCreate([
                        'report_id' => $report->id,
                        'indicator_id' => $parent_indicator->id,
                    ], [
                        'status' => 99,
                    ]);
                }

                ReportStatusTracker::updateOrCreate([
                    'report_id' => $report->id,
                    'status_code' => 99,
                ]);

                $user_id = Auth::user()->id;
                $comment_object = AceComment::where('report_id', $report_id)->first();
                if (isset($request->report_comment)) {
                    if ($comment_object) {
                        $comment_object->update([
                            'user_id' => $user_id,
                            'report_id' => $report_id,
                            'comments' => $request->report_comment,
                        ]);
                    } else {
                        AceComment::updateorCreate([
                            'user_id' => $user_id,
                            'report_id' => $report_id,
                            'comments' => $request->report_comment,
                        ]);
                    }

                }

                notify(new ToastNotification('Successful!', 'Report Saved!', 'success'));
            });
            if (isset($request->continue)) {
                return redirect()->route('report_submission.reports');
            }

            return back();
        }
    }

    /**
     * Report Edit and Review Status Check
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    function report_review($id)
    {
        $report = Report::find($id);
        if ($report->editable == 1) {
            $report->editable = false;
            $report->save();
            $message = "Review mode has been enabled.";
            $note = "In Review Mode";
            $status = 0;
            $btnclass = "btn-secondary";
        } else {
            $report->editable = true;
            $report->save();
            $message = "Review mode has been disabled.";
            $note = "In Edit Mode";
            $status = 1;
            $btnclass = "btn-primary";
        }

        return response()->json(['message' => $message, 'note' => $note, 'btnclass' => $btnclass, 'status' => $status]);
    }

    /**
     * Generate Aggregated results for Indicator 3
     * @param $report_id
     * @param bool $submit
     * @return array
     */
    public function generateAggregatedIndicator3Results($report_id,$submit=false)
    {
        $pdo_1_values = array();
        $phd = config('app.filters.phd_text');
        $masters = config('app.filters.masters_text');
        $bachelors = config('app.filters.bachelors_text');
        $course = config('app.filters.Course_text');

        $all_students = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id);
        $total_students = $all_students->count();

        /**
         * PDO Indicator 1
         */
        $regional = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('regional-status', '=', "Regional")
                    ->orWhere('regional-status', '=', "regional")
                    ->orWhere('regional-status', 'like', "r%")
                    ->orWhere('regional-status', 'like', "R%");
            });

        $regional_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('gender', '=', "Female")
                    ->orWhere('gender', '=', "female")
                    ->orWhere('gender', 'like', "f%")
                    ->orWhere('gender', 'like', "F%");
            })
            ->where(function ($query) {
                $query->where('regional-status', '=', "Regional")
                    ->orWhere('regional-status', '=', "regional")
                    ->orWhere('regional-status', 'like', "r%")
                    ->orWhere('regional-status', 'like', "R%");
            });

        $national = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('regional-status', '=', "National")
                    ->orWhere('regional-status', '=', "national")
                    ->orWhere('regional-status', 'like', "n%")
                    ->orWhere('regional-status', 'like', "N%");
            });

        if($submit) {
            $message = null;
            if ($national->count() + $regional->count() != $total_students) {
                $message = 'Please ensure the total number of National and Regional students equal the total number of students uploaded.';
                return [$message];
            }
            return $message;
        }

        $national_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('gender', '=', "Female")
                    ->orWhere('gender', '=', "female")
                    ->orWhere('gender', 'like', "f%")
                    ->orWhere('gender', 'like', "F%");
            })
            ->where(function ($query) {
                $query->where('regional-status', '=', "National")
                    ->orWhere('regional-status', '=', "national")
                    ->orWhere('regional-status', 'like', "n%")
                    ->orWhere('regional-status', 'like', "N%");
            });

        $pdo_1_values["pdo_indicator_1"]["total_no_students"] = $total_students;
        $pdo_1_values["pdo_indicator_1"]["regional_total"] = $regional->count();
        $pdo_1_values["pdo_indicator_1"]["regional_female"] = $regional_female->count();
        $pdo_1_values["pdo_indicator_1"]["national_total"] = $national->count();
        $pdo_1_values["pdo_indicator_1"]["national_female"] = $national_female->count();

        /**
         * PDO Indicator 1.a (PhD)
         */
        $phd_regional = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where("level", "=", $phd)
            ->where(function ($query) {
                $query->where('regional-status', '=', "Regional")
                    ->orWhere('regional-status', '=', "regional")
                    ->orWhere('regional-status', 'like', "r%")
                    ->orWhere('regional-status', 'like', "R%");
            });

        $phd_regional_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where("level", "=", $phd)
            ->where(function ($query) {
                $query->where('regional-status', '=', "Regional")
                    ->orWhere('regional-status', '=', "regional")
                    ->orWhere('regional-status', 'like', "r%")
                    ->orWhere('regional-status', 'like', "R%");
            })
            ->where(function ($query) {
                $query->where('gender', '=', "Female")
                    ->orWhere('gender', '=', "female")
                    ->orWhere('gender', 'like', "f%")
                    ->orWhere('gender', 'like', "F%");
            });

        $phd_national = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where("level", "=", $phd)
            ->where(function ($query) {
                $query->where('regional-status', '=', "National")
                    ->orWhere('regional-status', '=', "national")
                    ->orWhere('regional-status', 'like', "n%")
                    ->orWhere('regional-status', 'like', "N%");
            });

        $phd_national_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('regional-status', '=', "National")
                    ->orWhere('regional-status', '=', "national")
                    ->orWhere('regional-status', 'like', "n%")
                    ->orWhere('regional-status', 'like', "N%");
            })
            ->where("level", "=", $phd)
            ->where(function ($query) {
                $query->where('gender', '=', "Female")
                    ->orWhere('gender', '=', "female")
                    ->orWhere('gender', 'like', "f%")
                    ->orWhere('gender', 'like', "F%");
            });

        $pdo_1_values["pdo_indicator_1a"]["phd_regional_total"] = $phd_regional->count();
        $pdo_1_values["pdo_indicator_1a"]["phd_regional_female"] = $phd_regional_female->count();
        $pdo_1_values["pdo_indicator_1a"]["phd_national_total"] = $phd_national->count();
        $pdo_1_values["pdo_indicator_1a"]["phd_national_female"] = $phd_national_female->count();

        /**
         * PDO Indicator 1.a (Masters)
         */
        $masters_regional = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where("level", '=', $masters)
            ->orWhere("level", 'like', '%'.$masters)
            ->orWhere("level", '=', $masters.'%')
            ->where(function ($query) {
                $query->where('regional-status', '=', "Regional")
                    ->orWhere('regional-status', '=', "regional")
                    ->orWhere('regional-status', 'like', "r%")
                    ->orWhere('regional-status', 'like', "R%");
            });




        $masters_regional_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where("level", '=', $masters)
            ->orWhere("level", 'like', '%'.$masters)
            ->orWhere("level", '=', $masters.'%')
            ->where(function ($query) {
                $query->where('regional-status', '=', "Regional")
                    ->orWhere('regional-status', '=', "regional")
                    ->orWhere('regional-status', 'like', "r%")
                    ->orWhere('regional-status', 'like', "R%");
            })
            ->where(function ($query) {
                $query->where('gender', '=', "Female")
                    ->orWhere('gender', '=', "female")
                    ->orWhere('gender', 'like', "f%")
                    ->orWhere('gender', 'like', "F%");
            });

        $masters_national = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where("level", '=', $masters)
            ->orWhere("level", 'like', '%'.$masters)
            ->orWhere("level", '=', $masters.'%')
            ->where(function ($query) {
                $query->where('regional-status', '=', "National")
                    ->orWhere('regional-status', '=', "national")
                    ->orWhere('regional-status', 'like', "n%")
                    ->orWhere('regional-status', 'like', "N%");
            });

        $masters_national_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('regional-status', '=', "National")
                    ->orWhere('regional-status', '=', "national")
                    ->orWhere('regional-status', 'like', "n%")
                    ->orWhere('regional-status', 'like', "N%");
            })
            ->where("level", '=', $masters)
            ->orWhere("level", 'like', '%'.$masters)
            ->orWhere("level", '=', $masters.'%')
            ->where(function ($query) {
                $query->where('gender', '=', "Female")
                    ->orWhere('gender', '=', "female")
                    ->orWhere('gender', 'like', "f%")
                    ->orWhere('gender', 'like', "F%");
            });

        $pdo_1_values["pdo_indicator_1b"]["masters_regional_total"] = $masters_regional->count();
        $pdo_1_values["pdo_indicator_1b"]["masters_regional_female"] = $masters_regional_female->count();
        $pdo_1_values["pdo_indicator_1b"]["masters_national_total"] = $masters_national->count();
        $pdo_1_values["pdo_indicator_1b"]["masters_national_female"] = $masters_national_female->count();

        /**
         * PDO Indicator 1.c (Regional All Courses)
         */
        $bachelors_regional = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('regional-status', '=', "Regional")
                    ->orWhere('regional-status', '=', "regional")
                    ->orWhere('regional-status', 'like', "r%")
                    ->orWhere('regional-status', 'like', "R%");
            })
            ->where("level", "=", $bachelors);

        $bachelors_regional_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('regional-status', '=', "Regional")
                    ->orWhere('regional-status', '=', "regional")
                    ->orWhere('regional-status', 'like', "r%")
                    ->orWhere('regional-status', 'like', "R%");
            })
            ->where("level", "=", $bachelors)
            ->where(function ($query) {
                $query->where('gender', '=', "Female")
                    ->orWhere('gender', '=', "female")
                    ->orWhere('gender', 'like', "f%")
                    ->orWhere('gender', 'like', "F%");
            });
        $course_regional = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('regional-status', '=', "Regional")
                    ->orWhere('regional-status', '=', "regional")
                    ->orWhere('regional-status', 'like', "r%")
                    ->orWhere('regional-status', 'like', "R%");
            })
            ->where("level", 'like', "%$course%");
//        dd($course_regional);

        $course_regional_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('regional-status', '=', "Regional")
                    ->orWhere('regional-status', '=', "regional")
                    ->orWhere('regional-status', 'like', "r%")
                    ->orWhere('regional-status', 'like', "R%");
            })
            ->where("level", 'like', "%$course%")
            ->where(function ($query) {
                $query->where('gender', '=', "Female")
                    ->orWhere('gender', '=', "female")
                    ->orWhere('gender', 'like', "f%")
                    ->orWhere('gender', 'like', "F%");
            });

        $pdo_1_values["pdo_indicator_1c"]["regional_total"] = $regional->count();
        $pdo_1_values["pdo_indicator_1c"]["regional_phd_total"] = $phd_regional->count();
        $pdo_1_values["pdo_indicator_1c"]["regional_phd_female"] = $phd_regional_female->count();
        $pdo_1_values["pdo_indicator_1c"]["regional_masters_total"] = $masters_regional->count();
        $pdo_1_values["pdo_indicator_1c"]["regional_masters_female"] = $masters_regional_female->count();
        $pdo_1_values["pdo_indicator_1c"]["regional_bachelors_total"] = $bachelors_regional->count();
        $pdo_1_values["pdo_indicator_1c"]["regional_bachelors_female"] = $bachelors_regional_female->count();
        $pdo_1_values["pdo_indicator_1c"]["regional_short_course_total"] = $course_regional->count();
        $pdo_1_values["pdo_indicator_1c"]["regional_short_course_female"] = $course_regional_female->count();

        /**
         * PDO Indicator 1.d (Female All Courses)
         */
        $female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('gender', '=', "Female")
                    ->orWhere('gender', '=', "female")
                    ->orWhere('gender', 'like', "f%")
                    ->orWhere('gender', 'like', "F%");
            });

        $bachelors_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where("level", "=", $bachelors)
            ->where(function ($query) {
                $query->where('gender', '=', "Female")
                    ->orWhere('gender', '=', "female")
                    ->orWhere('gender', 'like', "f%")
                    ->orWhere('gender', 'like', "F%");
            });

        $course_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where("level", 'like', "%$course%")
            ->where(function ($query) {
                $query->where('gender', '=', "Female")
                    ->orWhere('gender', '=', "female")
                    ->orWhere('gender', 'like', "f%")
                    ->orWhere('gender', 'like', "F%");
            });

        $pdo_1_values["pdo_indicator_1d"]["female_total"] = $female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_phd_total"] = $phd_regional_female->count() + $phd_national_female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_phd_regional"] = $phd_regional_female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_masters_total"] = $masters_regional_female->count() + $masters_national_female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_masters_regional"] = $masters_regional_female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_bachelors_total"] = $bachelors_female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_bachelors_regional"] = $bachelors_regional_female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_short_course_total"] = $course_female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_short_course_regional"] = $course_regional_female->count();

        /**
         * PDO Indicator 1.e (Short Courses)
         */
        $course_national = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where("level", 'like', "%$course%")
            ->where(function ($query) {
                $query->where('regional-status', '=', "National")
                    ->orWhere('regional-status', '=', "national")
                    ->orWhere('regional-status', 'like', "n%")
                    ->orWhere('regional-status', 'like', "N%");
            });

        $course_national_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('regional-status', '=', "National")
                    ->orWhere('regional-status', '=', "national")
                    ->orWhere('regional-status', 'like', "n%")
                    ->orWhere('regional-status', 'like', "N%");
            })
            ->where("level", 'like', "%$course%")
            ->where(function ($query) {
                $query->where('gender', '=', "Female")
                    ->orWhere('gender', '=', "female")
                    ->orWhere('gender', 'like', "f%")
                    ->orWhere('gender', 'like', "F%");
            });

        /**
         * IR Indicator 7 (Emerging Centres)
         */
        $report = Report::find($report_id);
        $value = 0;
        $masters = config('app.filters.masters_text');
        $bachelors = config('app.filters.bachelors_text');
        if ($report->ace->ace_type == 'emerging') {
            $value = DB::connection('mongodb')
                ->collection('indicator_3')
                ->where('report_id', '=', $report_id)
                ->where(function ($query) use($masters,$bachelors) {
                    $query->where("level", 'like', "%$masters%")
                        ->orWhere("level", 'like', "%$bachelors%");
                })->count();
        }

        $pdo_1_values["pdo_indicator_1e"]["sc_regional_total"] = $course_regional->count();
        $pdo_1_values["pdo_indicator_1e"]["sc_regional_female"] = $course_regional_female->count();
        $pdo_1_values["pdo_indicator_1e"]["sc_national_total"] = $course_national->count();
        $pdo_1_values["pdo_indicator_1e"]["sc_national_female"] = $course_national_female->count();
        $pdo_1_values["ir_indicator_7"] = $value;

        return $pdo_1_values;
    }
    public function generateAggregatedIndicator3Results_fr($report_id,$submit=false)
    {

        $pdo_1_values = array();
        $phd = config('app.filters_fr.phd_text');
        $masters = config('app.filters_fr.masters_text');
        $bachelors = config('app.filters_fr.bachelors_text');
        $course = config('app.filters_fr.Course_text');

        $all_students = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id);
        $total_students = $all_students->count();

        /**
         * PDO Indicator 1
         */
        $regional = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('regionalite', '=', "Regional")
                    ->orWhere('regionalite', '=', "regional")
                    ->orWhere('regionalite', 'like', "r%")
                    ->orWhere('regionalite', 'like', "R%");
            });

        $regional_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('genre', '=', "Femme")
                    ->orWhere('genre', '=', "F");
            })
            ->where(function ($query) {
                $query->where('regionalite', '=', "Regional")
                    ->orWhere('regionalite', '=', "regional")
                    ->orWhere('regionalite', 'like', "r%")
                    ->orWhere('regionalite', 'like', "R%");
            });

        $national = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('regionalite', '=', "National")
                    ->orWhere('regionalite', '=', "national")
                    ->orWhere('regionalite', 'like', "n%")
                    ->orWhere('regionalite', 'like', "N%");
            });

        if($submit) {
            $message = null;
            if ($national->count() + $regional->count() != $total_students) {
                $message = 'Veuillez vous assurer que le nombre total de Les étudiants régionaux n\'ont pas beaucoup le nombre total d\'étudiants téléchargés.';
                return [$message];
            }
            return $message;
        }

        $national_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('genre', '=', "F")
                    ->orWhere('genre', '=', "Femme");
            })
            ->where(function ($query) {
                $query->where('regionalite', '=', "National")
                    ->orWhere('regionalite', '=', "national")
                    ->orWhere('regionalite', 'like', "n%")
                    ->orWhere('regionalite', 'like', "N%");
            });

        $pdo_1_values["pdo_indicator_1"]["total_no_students"] = $total_students;
        $pdo_1_values["pdo_indicator_1"]["regional_total"] = $regional->count();
        $pdo_1_values["pdo_indicator_1"]["regional_female"] = $regional_female->count();
        $pdo_1_values["pdo_indicator_1"]["national_total"] = $national->count();
        $pdo_1_values["pdo_indicator_1"]["national_female"] = $national_female->count();

        /**
         * PDO Indicator 1.a (PhD)
         */
        $phd_regional = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where("level", "=", $phd)
            ->where(function ($query) {
                $query->where('regional-status', '=', "Regional")
                    ->orWhere('regional-status', '=', "regional")
                    ->orWhere('regional-status', 'like', "r%")
                    ->orWhere('regional-status', 'like', "R%");
            });

        $phd_regional_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where("level", "=", $phd)
            ->where(function ($query) {
                $query->where('regionalite', '=', "Regional")
                    ->orWhere('regionalite', '=', "regional")
                    ->orWhere('regionalite', 'like', "r%")
                    ->orWhere('regionalite', 'like', "R%");
            })
            ->where(function ($query) {
                $query->where('gender', '=', "Female")
                    ->orWhere('gender', '=', "female")
                    ->orWhere('gender', 'like', "f%")
                    ->orWhere('gender', 'like', "F%");
            });

        $phd_national = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where("level", "=", $phd)
            ->where(function ($query) {
                $query->where('regionalite', '=', "National")
                    ->orWhere('regionalite', '=', "national")
                    ->orWhere('regionalite', 'like', "n%")
                    ->orWhere('regionalite', 'like', "N%");
            });

        $phd_national_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('regionalite', '=', "National")
                    ->orWhere('regionalite', '=', "national")
                    ->orWhere('regionalite', 'like', "n%")
                    ->orWhere('regionalite', 'like', "N%");
            })
            ->where("level", "=", $phd)
            ->where(function ($query) {
                $query->where('genre', '=', "F")
                    ->orWhere('genre', '=', "Femme");
            });

        $pdo_1_values["pdo_indicator_1a"]["phd_regional_total"] = $phd_regional->count();
        $pdo_1_values["pdo_indicator_1a"]["phd_regional_female"] = $phd_regional_female->count();
        $pdo_1_values["pdo_indicator_1a"]["phd_national_total"] = $phd_national->count();
        $pdo_1_values["pdo_indicator_1a"]["phd_national_female"] = $phd_national_female->count();

        /**
         * PDO Indicator 1.a (Masters)
         */
        $masters_regional = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where("level", "=", $masters)
            ->where(function ($query) {
                $query->where('regionalite', '=', "Regional")
                    ->orWhere('regionalite', '=', "regional")
                    ->orWhere('regionalite', 'like', "r%")
                    ->orWhere('regionalite', 'like', "R%");
            });

        $masters_regional_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where("level", "=", $masters)
            ->where(function ($query) {
                $query->where('regionalite', '=', "Regional")
                    ->orWhere('regionalite', '=', "regional")
                    ->orWhere('regionalite', 'like', "r%")
                    ->orWhere('regionalite', 'like', "R%");
            })
            ->where(function ($query) {
                $query->where('genre', '=', "F")
                    ->orWhere('genre', '=', "Femme");
            });

        $masters_national = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where("level", "=", $masters)
            ->where(function ($query) {
                $query->where('regionalite', '=', "national")
                    ->orWhere('regionalite', '=', "national")
                    ->orWhere('regionalite', 'like', "n%")
                    ->orWhere('regionalite', 'like', "N%");
            });

        $masters_national_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('regionalite', '=', "National")
                    ->orWhere('regionalite', '=', "national")
                    ->orWhere('regionalite', 'like', "n%")
                    ->orWhere('regionalite', 'like', "N%");
            })
            ->where("level", "=", $masters)
            ->where(function ($query) {
                $query->where('genre', '=', "F")
                    ->orWhere('genre', '=', "Femme");
            });

        $pdo_1_values["pdo_indicator_1b"]["masters_regional_total"] = $masters_regional->count();
        $pdo_1_values["pdo_indicator_1b"]["masters_regional_female"] = $masters_regional_female->count();
        $pdo_1_values["pdo_indicator_1b"]["masters_national_total"] = $masters_national->count();
        $pdo_1_values["pdo_indicator_1b"]["masters_national_female"] = $masters_national_female->count();

        /**
         * PDO Indicator 1.c (Regional All Courses)
         */
        $bachelors_regional = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('regionalite', '=', "Regional")
                    ->orWhere('regionalite', '=', "regional")
                    ->orWhere('regionalite', 'like', "r%")
                    ->orWhere('regionalite', 'like', "R%");
            })
            ->where("level", "=", $bachelors);

        $bachelors_regional_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('regionalite', '=', "Regional")
                    ->orWhere('regionalite', '=', "regional")
                    ->orWhere('regionalite', 'like', "r%")
                    ->orWhere('regionalite', 'like', "R%");
            })
            ->where("level", "=", $bachelors)
            ->where(function ($query) {
                $query->where('genre', '=', "F")
                    ->orWhere('genre', '=', "Femme");
            });
        $course_regional = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('regionalite', '=', "Regional")
                    ->orWhere('regionalite', '=', "regional")
                    ->orWhere('regionalite', 'like', "r%")
                    ->orWhere('regionalite', 'like', "R%");
            })
            ->where("level", 'like', "%$course%");
//        dd($course_regional);

        $course_regional_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('regionalite', '=', "Regional")
                    ->orWhere('regionalite', '=', "regional")
                    ->orWhere('regionalite', 'like', "r%")
                    ->orWhere('regionalite', 'like', "R%");
            })
            ->where("level", 'like', "%$course%")
            ->where(function ($query) {
                $query->where('genre', '=', "F")
                    ->orWhere('genre', '=', "Femme");
            });

        $pdo_1_values["pdo_indicator_1c"]["regional_total"] = $regional->count();
        $pdo_1_values["pdo_indicator_1c"]["regional_phd_total"] = $phd_regional->count();
        $pdo_1_values["pdo_indicator_1c"]["regional_phd_female"] = $phd_regional_female->count();
        $pdo_1_values["pdo_indicator_1c"]["regional_masters_total"] = $masters_regional->count();
        $pdo_1_values["pdo_indicator_1c"]["regional_masters_female"] = $masters_regional_female->count();
        $pdo_1_values["pdo_indicator_1c"]["regional_bachelors_total"] = $bachelors_regional->count();
        $pdo_1_values["pdo_indicator_1c"]["regional_bachelors_female"] = $bachelors_regional_female->count();
        $pdo_1_values["pdo_indicator_1c"]["regional_short_course_total"] = $course_regional->count();
        $pdo_1_values["pdo_indicator_1c"]["regional_short_course_female"] = $course_regional_female->count();

        /**
         * PDO Indicator 1.d (Female All Courses)
         */
        $female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('genre','like', "f%")
                    ->orWhere('genre', '=', "Femme")
                     ->orWhere('gender', '=', "Female")
                    ->orWhere('gender', 'like', "f%");

            });

        $bachelors_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where("level", "=", $bachelors)
            ->where(function ($query) {
                $query->where('genre', 'like', "F%")
                    ->orWhere('genre', '=', "Femme")
                ->orWhere('gender', '=', "Female");
            });

        $course_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where("level", 'like', "%$course%")
            ->where(function ($query) {
                $query->where('genre', 'like', "F%")
                    ->orWhere('gender','=','Female')
                    ->orWhere('genre', '=', "Femme");
            });

        $pdo_1_values["pdo_indicator_1d"]["female_total"] = $female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_phd_total"] = $phd_regional->count() + $phd_national_female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_phd_regional"] = $phd_regional_female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_masters_total"] = $masters_regional_female->count() + $masters_national_female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_masters_regional"] = $masters_regional_female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_bachelors_total"] = $bachelors_female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_bachelors_regional"] = $bachelors_regional_female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_short_course_total"] = $course_female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_short_course_regional"] = $course_regional_female->count();

        /**
         * PDO Indicator 1.e (Short Courses)
         */
        $course_national = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where("level", 'like', "%$course%")
            ->where(function ($query) {
                $query->where('regionalite', '=', "National")
                    ->orWhere('regionalite', '=', "national")
                    ->orWhere('regionalite', 'like', "n%")
                    ->orWhere('regionalite', 'like', "N%");
            });

        $course_national_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('regionalite', '=', "national")
                    ->orWhere('regionalite', '=', "national")
                    ->orWhere('regionalite', 'like', "n%")
                    ->orWhere('regionalite', 'like', "N%");
            })
            ->where("level", 'like', "%$course%")
            ->where(function ($query) {
                $query->where('genre', '=', "F")
                    ->orWhere('genre', '=', "Femme");
            });

        /**
         * IR Indicator 7 (Emerging Centres)
         */
        $report = Report::find($report_id);
        $value = 0;
        if ($report->ace->ace_type == 'emerging') {
            $value = DB::connection('mongodb')
                ->collection('indicator_3')
                ->where('report_id', '=', $report_id)
                ->where(function ($query) use($masters,$bachelors) {
                    $query->where("level", 'like', "%$masters%")
                        ->orWhere("level", 'like', "%$bachelors%");
                })->count();
        }

        $pdo_1_values["pdo_indicator_1e"]["sc_regional_total"] = $course_regional->count();
        $pdo_1_values["pdo_indicator_1e"]["sc_regional_female"] = $course_regional_female->count();
        $pdo_1_values["pdo_indicator_1e"]["sc_national_total"] = $course_national->count();
        $pdo_1_values["pdo_indicator_1e"]["sc_national_female"] = $course_national_female->count();
        $pdo_1_values["ir_indicator_7"] = $value;

        return $pdo_1_values;

    }

    /**
     * Generate Aggregated results for Indicator 4.1
     * WEBFORM
     * @param $report_id
     * @return array
     */
    public function generateAggregatedIndicator41Results($report_id)
    {
        $indicator_4_1_values = array();
        $report = Report::find($report_id);
        $emerging = 0;

        $national = DB::connection('mongodb')
            ->collection('indicator_4.1')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('typeofaccreditation', '=', "National")
                    ->orWhere('typeofaccreditation', '=', "national")
                    ->orWhere('typeofaccreditation', 'like', "n%")
                    ->orWhere('typeofaccreditation', 'like', "N%");
            })->count();

        $regional = DB::connection('mongodb')
            ->collection('indicator_4.1')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('typeofaccreditation', '=', "Regional")
                    ->orWhere('typeofaccreditation', '=', "regional")
                    ->orWhere('typeofaccreditation', 'like', "r%")
                    ->orWhere('typeofaccreditation', 'like', "R%");
            })->count();

        $international = DB::connection('mongodb')
            ->collection('indicator_4.1')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('typeofaccreditation', '=', "International")
                    ->orWhere('typeofaccreditation', '=', "international")
                    ->orWhere('typeofaccreditation', 'like', "i%")
                    ->orWhere('typeofaccreditation', 'like', "I%");
            })->count();

        $gap_assessment = DB::connection('mongodb')
            ->collection('indicator_4.1')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('typeofaccreditation', '=', "Gap")
                    ->orWhere('typeofaccreditation', '=', "gap")
                    ->orWhere('typeofaccreditation', 'like', "gap%")
                    ->orWhere('typeofaccreditation', 'like', "Gap%");
            })->count();

        $self_evaluation = DB::connection('mongodb')
            ->collection('indicator_4.1')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('typeofaccreditation', '=', "Self Fvaluation")
                    ->orWhere('typeofaccreditation', '=', "self evaluation")
                    ->orWhere('typeofaccreditation', 'like', "self%")
                    ->orWhere('typeofaccreditation', 'like', "Self%");
            })->count();

//        $course = DB::connection('mongodb')
//            ->collection('indicator_4.1')
//            ->where('report_id', '=', $report_id)
//            ->where(function ($query) {
//                $query->where('typeofaccreditation', '=', "New Course")
//                    ->orWhere('typeofaccreditation', '=', "new course")
//                    ->orWhere('typeofaccreditation', 'like', "new%")
//                    ->orWhere('typeofaccreditation', 'like', "New%");
//            })->count();

        if ($report->ace->ace_type == 'emerging') {
            $emerging = DB::connection('mongodb')
                ->collection('indicator_4.1')
                ->where('report_id', '=', $report_id)
                ->where('newly_accredited_programme', '=', 'Yes')
                ->where(function ($query) {
                    $query->where('level', 'like', "Master%")
                        ->orWhere('level', 'like', "Bachelor%");
                })
                ->where(function ($query) {
                    $query->where('typeofaccreditation', '=', "Regional")
                        ->orWhere('typeofaccreditation', '=', "National");
                })->count();
        }

        $indicator_4_1_values["pdo_indicator_41"]["national"] = $national;
        $indicator_4_1_values["pdo_indicator_41"]["regional"] = $regional;
        $indicator_4_1_values["pdo_indicator_41"]["international"] = $international;
        $indicator_4_1_values["pdo_indicator_41"]["self_evaluation"] = $self_evaluation;
        $indicator_4_1_values["pdo_indicator_41"]["gap_assessment"] = $gap_assessment;
//        $indicator_4_1_values["pdo_indicator_41"]["course"] = $course;
        $indicator_4_1_values["pdo_indicator_41"]["emerging"] = $emerging;


        return $indicator_4_1_values;
    }
    public function generateAggregatedIndicator41Results_fr($report_id)
    {
        $indicator_4_1_values = array();
        $report = Report::find($report_id);
        $national = DB::connection('mongodb')
            ->collection('indicator_4.1')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('typeofaccreditation', '=', "National")
                    ->orWhere('typeofaccreditation', '=', "national")
                    ->orWhere('typeofaccreditation', 'like', "n%")
                    ->orWhere('typeofaccreditation', 'like', "N%");
            })->count();

        $regional = DB::connection('mongodb')
            ->collection('indicator_4.1')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('typeofaccreditation', '=', "Regional")
                    ->orWhere('typeofaccreditation', '=', "regional")
                    ->orWhere('typeofaccreditation', 'like', "r%")
                    ->orWhere('typeofaccreditation', 'like', "R%");
            })->count();

        $international = DB::connection('mongodb')
            ->collection('indicator_4.1')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('typeofaccreditation', '=', "International")
                    ->orWhere('typeofaccreditation', '=', "international")
                    ->orWhere('typeofaccreditation', 'like', "i%")
                    ->orWhere('typeofaccreditation', 'like', "I%");
            })->count();

        $gap_assessment = DB::connection('mongodb')
            ->collection('indicator_4.1')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('typeofaccreditation', '=', "Gap")
                    ->orWhere('typeofaccreditation', '=', "gap")
                    ->orWhere('typeofaccreditation', 'like', "gap%")
                    ->orWhere('typeofaccreditation', 'like', "Gap%");
            })->count();

        $self_evaluation = DB::connection('mongodb')
            ->collection('indicator_4.1')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('typeofaccreditation', '=', "Self Fvaluation")
                    ->orWhere('typeofaccreditation', '=', "self evaluation")
                    ->orWhere('typeofaccreditation', 'like', "self%")
                    ->orWhere('typeofaccreditation', 'like', "Self%");
            })->count();

//        $course = DB::connection('mongodb')
//            ->collection('indicator_4.1')
//            ->where('report_id', '=', $report_id)
//            ->where(function ($query) {
//                $query->where('typeofaccreditation', '=', "New Course")
//                    ->orWhere('typeofaccreditation', '=', "new course")
//                    ->orWhere('typeofaccreditation', 'like', "new%")
//                    ->orWhere('typeofaccreditation', 'like', "New%");
//            })->count();
        $masters = config('app.filters_fr.masters_text');
        $bachelors = config('app.filters_fr.bachelors_text');

        if ($report->ace->ace_type == 'emerging') {
            $emerging = DB::connection('mongodb')
                ->collection('indicator_4.1')
                ->where('report_id', '=', $report_id)
                ->where('newly_accredited_programme', '=', 'Oui')
                ->where(function ($query) use($bachelors,$masters) {
                    $query->where('level', '=', "$masters")
                        ->orWhere('level', '=', "$bachelors")
                        ->orWhere('level', 'like', "Ba%");
                })
                ->where(function ($query) {
                    $query->where('typeofaccreditation', 'like', "Reg%")
                        ->orWhere('typeofaccreditation', 'like', "Nat%");
                })->count();
        }

        $indicator_4_1_values["pdo_indicator_41"]["national"] = $national;
        $indicator_4_1_values["pdo_indicator_41"]["regional"] = $regional;
        $indicator_4_1_values["pdo_indicator_41"]["international"] = $international;
        $indicator_4_1_values["pdo_indicator_41"]["self_evaluation"] = $self_evaluation;
        $indicator_4_1_values["pdo_indicator_41"]["gap_assessment"] = $gap_assessment;
//        $indicator_4_1_values["pdo_indicator_41"]["course"] = $course;
        $indicator_4_1_values["pdo_indicator_41"]["emerging"] = $emerging;


        return $indicator_4_1_values;
    }

    /**
     * Generate Aggregated results for Indicator 4.2 (Publications)
     * @param $report_id
     * @return array
     */
    public function generateAggregatedIndicator42Results($report_id,$submit=false)
    {
        $indicator_4_2_values = array();


        $total_publications = DB::connection('mongodb')
            ->collection('indicator_4.2')
            ->where('report_id', '=', $report_id)
            ->count();

        $regional_publications =  DB::connection('mongodb')
            ->collection('indicator_4.2')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('collaboration', 'LIKE', "%Regional")
                    ->where('collaboration', 'LIKE', "regional")
                    ->orWhere('collaboration','like', "r%")
                    ->orWhere('collaboration','like', "R%");
            })->count();

        $national_publications = DB::connection('mongodb')
            ->collection('indicator_4.2')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('collaboration', 'LIKE', "%National")
                    ->where('collaboration', 'LIKE', "%National")
                    ->orWhere('collaboration','like', "n%")
                    ->orWhere('collaboration','like', "N%");
            })->count();

        if ($submit) {
            $message = null;
            if ($total_publications != $regional_publications + $national_publications) {
                $message = 'Please ensure National and
                    Regional publications equals the total publications.';
                return [$message];
            }
            return $message;
        }

        $indicator_4_2_values["ir_indicator_2"]["total_publications"] = $total_publications;
        $indicator_4_2_values["ir_indicator_2"]["regional_publications"] = $regional_publications;
        $indicator_4_2_values["ir_indicator_2"]["national_publications"] = $national_publications;

        return $indicator_4_2_values;
    }
    public function generateAggregatedIndicator42Results_fr($report_id,$submit=false)
    {
        $indicator_4_2_values = array();

        $total_publications = DB::connection('mongodb')
            ->collection('indicator_4.2')
            ->where('report_id', '=', $report_id)
            ->count();

        $regional_publications =  DB::connection('mongodb')
            ->collection('indicator_4.2')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('collaboration', 'LIKE', "%Regional")
                    ->where('collaboration', 'LIKE', "regional")
                    ->orWhere('collaboration','like', "r%")
                    ->orWhere('collaboration','like', "R%");
            })->count();

        $national_publications = DB::connection('mongodb')
            ->collection('indicator_4.2')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('collaboration', 'LIKE', "%National")
                    ->where('collaboration', 'LIKE', "%National")
                    ->orWhere('collaboration','like', "n%")
                    ->orWhere('collaboration','like', "N%");
            })->count();

        if ($submit) {
            $message = null;
            if ($total_publications != $regional_publications + $national_publications) {
                $message = 'Veuillez vous assurer que les Les publications régionales sont égales au nombre total de publications.';
                return [$message];
            }
            return $message;
        }

        $indicator_4_2_values["ir_indicator_2"]["total_publications"] = $total_publications;
        $indicator_4_2_values["ir_indicator_2"]["regional_publications"] = $regional_publications;
        $indicator_4_2_values["ir_indicator_2"]["national_publications"] = $national_publications;

        return $indicator_4_2_values;
    }

    /**
     * Generate Aggregated results for Indicator 5.1 on revenue sources
     * WEBFORM
     * @param $report_id
     * @return array
     */

    public function generateAggregatedIndicator51Results($report_id, $submit=false)
    {
        $indicator_5_1_values = array();

        $data =  DB::table('indicator_5_1')
            ->where('report_id', '=', $report_id)
//            ->select('amountindollars')
            ->get();

        $total_revenue = $data->sum('amountindollars');

        $national_sources = DB::table('indicator_5_1')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('source', 'like', "Nat%")
                    ->orWhere('source', 'like', "nat%");
            })->sum('amountindollars');

        $regional_sources = DB::table('indicator_5_1')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('source', 'like', "Reg%")
                    ->orWhere('source', 'like', "reg%");
            })->sum('amountindollars');

        if ($submit) {
            $message = null;
            if ($total_revenue != $national_sources + $regional_sources) {
                $message = 'Please ensure National and Regional sources equal the total amount.';
                return [$message];
            }
            return $message;
        }

        $indicator_5_1_values["ir_indicator_4"]["total_revenue"] = money_format($total_revenue,2);
        $indicator_5_1_values["ir_indicator_4"]["national_sources"] = money_format($national_sources,2);
        $indicator_5_1_values["ir_indicator_4"]["regional_sources"] = money_format($regional_sources,2);


        return $indicator_5_1_values;
    }
    public function generateAggregatedIndicator51Results_fr($report_id,$submit=false)
    {
        $indicator_5_1_values = array();

        $query =  DB::connection('mongodb')
            ->collection('indicator_5.1')
            ->where('report_id', '=', $report_id)
            ->select('amountindollars')
            ->get();

        $total_revenue= collect($query)->sum('amountindollars');
        $national_sources = DB::connection('mongodb')->collection('indicator_5.1')
            ->where('report_id', $report_id)
            ->where(function ($query) {
                $query->where('source', 'like', "N%")
                    ->orWhere('source', 'like', "n%");
            })->sum('amountindollars');
        $regional_sources = DB::connection('mongodb')->collection('indicator_5.1')
            ->where('report_id', $report_id)
            ->where(function ($query) {
                $query->where('source', 'like', "R%")
                    ->orWhere('source', 'like', "r%");
            })->sum('amountindollars');

        if ($submit) {
            $message = null;
            if ($total_revenue != $national_sources + $regional_sources) {
                $message = 'Veuillez vous assurer que les Les sources régionales sont égales au montant total.';
                return [$message];
            }
            return $message;
        }

        $indicator_5_1_values["ir_indicator_4"]["total_revenue"] = $total_revenue;
        $indicator_5_1_values["ir_indicator_4"]["national_sources"] = $national_sources;
        $indicator_5_1_values["ir_indicator_4"]["regional_sources"] = $regional_sources;


        return $indicator_5_1_values;
    }

    /**
     * Generate Aggregated results for Indicator 5.2
     * @param $report_id
     * @return array
     */
    public function generateAggregatedIndicator52Results($report_id,$submit=false)
    {
        $indicator_5_2_values = array();

        $total_number_of_interns = DB::connection('mongodb')
            ->collection('indicator_5.2')
            ->where('report_id', '=', $report_id)
            ->count();


        $students = DB::connection('mongodb')
            ->collection('indicator_5.2')
            ->where('report_id', $report_id)
            ->where(function ($query) {
                $query->where('studentfaculty', 'like', "Student%")
                    ->orWhere('studentfaculty', 'like', "stud%");
            })->count();

        $faculty = DB::connection('mongodb')
            ->collection('indicator_5.2')
            ->where('report_id', $report_id)
            ->where(function ($query) {
                $query->where('studentfaculty', 'like', "F%")
                    ->orWhere('studentfaculty', 'like', "f%");
            })->count();

        if ($submit) {
            $message = null;
            if ($total_number_of_interns != $students + $faculty) {
                $message = 'Please ensure Students and Faculty types equal the total number of Interns.';
                return [$message];
            }
            return $message;
        }

        $indicator_5_2_values["pdo_indicator_5"]["total_number_of_interns"] = $total_number_of_interns;
        $indicator_5_2_values["pdo_indicator_5"]["students"] = $students;
        $indicator_5_2_values["pdo_indicator_5"]["faculty"] = $faculty;


        return $indicator_5_2_values;
    }
    public function generateAggregatedIndicator52Results_fr($report_id,$submit=false)
    {
        $indicator_5_2_values = array();

        $total_number_of_interns = DB::connection('mongodb')
            ->collection('indicator_5.2')
            ->where('report_id', '=', $report_id)
            ->count();


        $students = DB::connection('mongodb')
            ->collection('indicator_5.2')
            ->where('report_id', $report_id)
            ->where(function ($query) {
                $query->where('studentfaculty', 'like', "Etudiant%")
                    ->orWhere('studentfaculty', 'like', "E%")
                    ->orWhere('studentfaculty', 'like', "e%");
            })->count();
        $faculty = DB::connection('mongodb')->collection('indicator_5.2')
            ->where('report_id', $report_id)
            ->where(function ($query) {
                $query->where('studentfaculty', 'like', "Professeur%")
                    ->orWhere('studentfaculty', 'like', "P%")
                    ->orWhere('studentfaculty', 'like', "p%");
            })->count();

        if ($submit) {
            if ($total_number_of_interns != $students + $faculty) {
                $message = 'Veuillez vous assurer que les étudiants et Le type de faculté est égal au nombre total de stagiaires.';
                return [$message];
            }
        }

        $indicator_5_2_values["pdo_indicator_5"]["total_number_of_interns"] = $total_number_of_interns;
        $indicator_5_2_values["pdo_indicator_5"]["students"] = $students;
        $indicator_5_2_values["pdo_indicator_5"]["faculty"] = $faculty;


        return $indicator_5_2_values;
    }

    /**
     * Generate Aggregated results for Indicator 7.3
     * @param $report_id
     * @return array
     */
    public function generateAggregatedIndicator73Results($report_id,$submit=false)
    {
        $pdo_7_3_values = array();
        /** pdo 2
         *'pdo_indicaror_2' => [
         * 'total_accreditations','international_accreditation','regional_accreditation','national_accreditation',
         * 'gap_assessment','self_evaluation'
         * ],
         */
        $total_accreditations = DB::connection('mongodb')
            ->collection('indicator_7.3')
            ->where('report_id', '=', $report_id)
            ->count();


        $international_accreditation = DB::connection('mongodb')
            ->collection('indicator_7.3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('typeofaccreditation', '=', "International")
                    ->orWhere('typeofaccreditation', '=', "international")
                    ->orWhere('typeofaccreditation', 'like', "i%")
                    ->orWhere('typeofaccreditation', 'like', "I%");
            });
        $national_accreditation = DB::connection('mongodb')
            ->collection('indicator_7.3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('typeofaccreditation', '=', "National")
                    ->orWhere('typeofaccreditation', '=', "national")
                    ->orWhere('typeofaccreditation', 'like', "n%")
                    ->orWhere('typeofaccreditation', 'like', "N%");
            });
        $self_evaluation = DB::connection('mongodb')
            ->collection('indicator_7.3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('typeofaccreditation', '=', "Self-evaluation")
                    ->orWhere('typeofaccreditation', 'like', "self%")
                    ->orWhere('typeofaccreditation', 'like', "Self%");
            });

        $gap_assessment = DB::connection('mongodb')
            ->collection('indicator_7.3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('typeofaccreditation', '=', "Gap Assessment")
                    ->orWhere('typeofaccreditation', 'like', "gap%")
                    ->orWhere('typeofaccreditation', 'like', "Gap%");
            });

        $regional_accreditation = DB::connection('mongodb')
            ->collection('indicator_7.3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('typeofaccreditation', '=', "Regional")
                    ->orWhere('typeofaccreditation', '=', "regional")
                    ->orWhere('typeofaccreditation', 'like', "r%")
                    ->orWhere('typeofaccreditation', 'like', "R%");
            });

        if ($submit) {
            if ($international_accreditation->count()
                +$regional_accreditation->count()
                +$national_accreditation->count()
                +$gap_assessment->count()
                +$self_evaluation->count() != $total_accreditations) {
                $message = 'Please ensure the total number of National, Regional, International, Gap Assessment and Self Evaluation Accreditations equal the total 
                number of students uploaded.';
                return [$message];
            }
        }


        $pdo_7_3_values["pdo_indicator_2"]["total_accreditations"] = $total_accreditations;
        $pdo_7_3_values["pdo_indicator_2"]["international_accreditation"] = $international_accreditation->count();
        $pdo_7_3_values["pdo_indicator_2"]["regional_accreditation"] = $regional_accreditation->count();
        $pdo_7_3_values["pdo_indicator_2"]["national_accreditation"] = $national_accreditation->count();
        $pdo_7_3_values["pdo_indicator_2"]["gap_assessment"] = $gap_assessment->count();
        $pdo_7_3_values["pdo_indicator_2"]["self_evaluation"] = $self_evaluation->count();

        $pdo_7_3_values["pdo_indicator_2a"]["total_accreditations"] = $total_accreditations;
        $pdo_7_3_values["pdo_indicator_2a"]["international_accreditation"] = $international_accreditation->count();
        $pdo_7_3_values["pdo_indicator_2a"]["regional_accreditation"] = $regional_accreditation->count();
        $pdo_7_3_values["pdo_indicator_2a"]["national_accreditation"] = $national_accreditation->count();
        $pdo_7_3_values["pdo_indicator_2a"]["gap_assessment"] = $gap_assessment->count();
        $pdo_7_3_values["pdo_indicator_2a"]["self_evaluation"] = $self_evaluation->count();

        $pdo_7_3_values["pdo_indicator_2b"]["total_accreditations"] = $total_accreditations;
        $pdo_7_3_values["pdo_indicator_2b"]["international_accreditation"] = $international_accreditation->count();
        $pdo_7_3_values["pdo_indicator_2b"]["regional_accreditation"] = $regional_accreditation->count();
        $pdo_7_3_values["pdo_indicator_2b"]["gap_assessment"] = $gap_assessment->count();
        $pdo_7_3_values["pdo_indicator_2b"]["self_evaluation"] = $self_evaluation->count();

        return $pdo_7_3_values;
    }
    public function generateAggregatedIndicator73Results_fr($report_id,$submit=false)
    {
        $pdo_7_3_values = array();


        /** pdo 2
         *'pdo_indicaror_2' => [
         * 'total_accreditations','international_accreditation','regional_accreditation','national_accreditation',
         * 'gap_assessment','self_evaluation'
         * ],
         */


        $total_accreditations = DB::connection('mongodb')
            ->collection('indicator_7.3')
            ->where('report_id', '=', $report_id)
            ->count();


        $international_accreditation = DB::connection('mongodb')
            ->collection('indicator_7.3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('typeofaccreditation', '=', "International")
                    ->orWhere('typeofaccreditation', '=', "international")
                    ->orWhere('typeofaccreditation', 'like', "i%")
                    ->orWhere('typeofaccreditation', 'like', "I%");
            });
        $national_accreditation = DB::connection('mongodb')
            ->collection('indicator_7.3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('typeofaccreditation', '=', "National")
                    ->orWhere('typeofaccreditation', '=', "national")
                    ->orWhere('typeofaccreditation', 'like', "n%")
                    ->orWhere('typeofaccreditation', 'like', "N%");
            });
        $self_evaluation = DB::connection('mongodb')
            ->collection('indicator_7.3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('typeofaccreditation', '=', "Self-evaluation")
                    ->orWhere('typeofaccreditation', 'like', "self%")
                    ->orWhere('typeofaccreditation', 'like', "Self%");
            });

        $gap_assessment = DB::connection('mongodb')
            ->collection('indicator_7.3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('typeofaccreditation', '=', "Gap Assessment")
                    ->orWhere('typeofaccreditation', 'like', "gap%")
                    ->orWhere('typeofaccreditation', 'like', "Gap%");
            });

        $regional_accreditation = DB::connection('mongodb')
            ->collection('indicator_7.3')
            ->where('report_id', '=', $report_id)
            ->where(function ($query) {
                $query->where('typeofaccreditation', '=', "Regional")
                    ->orWhere('typeofaccreditation', '=', "regional")
                    ->orWhere('typeofaccreditation', 'like', "r%")
                    ->orWhere('typeofaccreditation', 'like', "R%");
            });

        if ($submit) {
            if ($international_accreditation->count()
                +$regional_accreditation->count()
                +$national_accreditation->count()
                +$gap_assessment->count()
                +$self_evaluation->count() != $total_accreditations) {
                $message ='Veuillez vous assurer que le nombre total 
                d\'accréditations nationales, régionales, internationales, d\'évaluation des écarts et 
                d\'auto-évaluation ne correspond pas beaucoup au nombre total d\'étudiants téléchargés.';
                return [$message];
            }
        }

        $pdo_7_3_values["pdo_indicator_2"]["total_accreditations"] = $total_accreditations;
        $pdo_7_3_values["pdo_indicator_2"]["international_accreditation"] = $international_accreditation->count();
        $pdo_7_3_values["pdo_indicator_2"]["regional_accreditation"] = $regional_accreditation->count();
        $pdo_7_3_values["pdo_indicator_2"]["national_accreditation"] = $national_accreditation->count();
        $pdo_7_3_values["pdo_indicator_2"]["gap_assessment"] = $gap_assessment->count();
        $pdo_7_3_values["pdo_indicator_2"]["self_evaluation"] = $self_evaluation->count();

        $pdo_7_3_values["pdo_indicator_2a"]["total_accreditations"] = $total_accreditations;
        $pdo_7_3_values["pdo_indicator_2a"]["international_accreditation"] = $international_accreditation->count();
        $pdo_7_3_values["pdo_indicator_2a"]["regional_accreditation"] = $regional_accreditation->count();
        $pdo_7_3_values["pdo_indicator_2a"]["national_accreditation"] = $national_accreditation->count();
        $pdo_7_3_values["pdo_indicator_2a"]["gap_assessment"] = $gap_assessment->count();
        $pdo_7_3_values["pdo_indicator_2a"]["self_evaluation"] = $self_evaluation->count();

        $pdo_7_3_values["pdo_indicator_2b"]["total_accreditations"] = $total_accreditations;
        $pdo_7_3_values["pdo_indicator_2b"]["international_accreditation"] = $international_accreditation->count();
        $pdo_7_3_values["pdo_indicator_2b"]["regional_accreditation"] = $regional_accreditation->count();
        $pdo_7_3_values["pdo_indicator_2b"]["gap_assessment"] = $gap_assessment->count();
        $pdo_7_3_values["pdo_indicator_2b"]["self_evaluation"] = $self_evaluation->count();

        return $pdo_7_3_values;
    }

    public function generateAggregatedIndicator3Result($report_id)
    {
        $indicators = Indicator::where('is_parent', '=', 1)
            ->where('status', '=', 1)
            ->where('show_on_report', '=', 1)
            ->where('parent_id', '=', 3)
            ->orderBy('identifier', 'asc')
            ->get();

        $filters = ["PhD", "Master", "Bachelors", "Course"];
        $indicator_3_values = array();

        foreach ($indicators as $key => $indicator) {
            $national_and_men = DB::connection('mongodb')
                ->collection('indicator_3')
                ->where('report_id', '=', $report_id)
                ->where(function ($query) {
                    $query->where('gender', '=', "Male")
                        ->orWhere('gender', '=', "male")
                        ->orWhere('gender', 'like', "m%")
                        ->orWhere('gender', 'like', "M%");
                })
                ->where(function ($query) {
                    $query->where('regional-status', '=', "National")
                        ->orWhere('regional-status', '=', "national")
                        ->orWhere('regional-status', 'like', "n%")
                        ->orWhere('regional-status', 'like', "N%");
                });
//                ->where('regional-status','=', "National");
            $national_and_women = DB::connection('mongodb')
                ->collection('indicator_3')
                ->where('report_id', '=', $report_id)
                ->where(function ($query) {
                    $query->where('gender', '=', "Female")
                        ->orWhere('gender', '=', "female")
                        ->orWhere('gender', 'like', "f%")
                        ->orWhere('gender', 'like', "F%");
                })
                ->where(function ($query) {
                    $query->where('regional-status', '=', "National")
                        ->orWhere('regional-status', '=', "national")
                        ->orWhere('regional-status', 'like', "n%")
                        ->orWhere('regional-status', 'like', "N%");
                });
//                ->where('regional-status','=', "National");
            $regional_and_men = DB::connection('mongodb')
                ->collection('indicator_3')
                ->where('report_id', '=', $report_id)
                ->where(function ($query) {
                    $query->where('regional-status', '=', "Regional")
                        ->orWhere('regional-status', '=', "regional")
                        ->orWhere('regional-status', 'like', "r%")
                        ->orWhere('regional-status', 'like', "R%");
                })
//                ->where('regional-status','=', "Regional")
                ->where(function ($query) {
                    $query->where('gender', '=', "male")
                        ->orWhere('gender', '=', "Male")
                        ->orWhere('gender', 'like', "m%")
                        ->orWhere('gender', 'like', "M%");
                });
            $regional_and_women = DB::connection('mongodb')
                ->collection('indicator_3')
                ->where('report_id', '=', $report_id)
                ->where(function ($query) {
                    $query->where('regional-status', '=', "Regional")
                        ->orWhere('regional-status', '=', "regional")
                        ->orWhere('regional-status', 'like', "r%")
                        ->orWhere('regional-status', 'like', "R%");
                })
//                ->where('regional-status','=', "Regional")
                ->where(function ($query) {
                    $query->where('gender', '=', "Female")
                        ->orWhere('gender', '=', "female")
                        ->orWhere('gender', 'like', "f%")
                        ->orWhere('gender', 'like', "F%");
                });

            $identifier = $indicator->identifier;
            $filter_value = $filters[$key];

            $indicator_3_values["$identifier"]["national_and_men"] = $national_and_men->where("level", "like", "%$filter_value%")->count();
            $indicator_3_values["$identifier"]["national_and_women"] = $national_and_women->where("level", "like", "%$filter_value%")->count();
            $indicator_3_values["$identifier"]["regional_and_men"] = $regional_and_men->where("level", "like", "%$filter_value%")->count();
            $indicator_3_values["$identifier"]["regional_and_women"] = $regional_and_women->where("level", "like", "%$filter_value%")->count();
        }

        return $indicator_3_values;

    }

    public static function getReportingName($id)
    {
        $period = ReportingPeriod::find($id);
        $monthNum1 = date('m', strtotime($period->period_start));
        $monthName1 = date("M", mktime(0, 0, 0, $monthNum1, 10));
        $year1 = date('Y', strtotime($period->period_start));
        $start = $monthName1 . ', ' . $year1;
        $monthNum2 = date('m', strtotime($period->period_end));
        $monthName2 = date("M", mktime(0, 0, 0, $monthNum2, 10));
        $year2 = date('Y', strtotime($period->period_end));
        $end = $monthName2 . ', ' . $year2;
        $full_period = $start . "   -    " . $end;


        return $full_period;
    }

    public function setEditMode($id)
    {
        $report_id = Crypt::decrypt($id);
        $report = Report::find($report_id);

        if ($report->editable != 1) {
//            $report->status = 99;
            $report->editable = true;
            $report->save();
            notify(new ToastNotification('Success!', 'The report submission has been reopened for edit!', 'success'));
        } else {
            notify(new ToastNotification('Notice!', 'The report is already in edit mode!', 'warning'));
        }
        return back();

    }
}
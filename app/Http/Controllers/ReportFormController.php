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
        $notsubmitted = False;
        if(Auth::user()->hasRole('ace-officer')){
            $notcompleted = Report::Uncompleted()->where('user_id', '=', Auth::id())->get();
            if(!empty($notcompleted)){
                $notsubmitted = True;
            }
        }
        if (Auth::user()->hasRole('webmaster|super-admin')) {
            $ace_reports = Report::get();
        } elseif (Auth::user()->hasRole('admin')) {
            $ace_reports = Report::submitted()->get();
        } else {
            $ace_reports = Report::SubmittedAndUncompleted()->where('user_id', '=', Auth::id())->get();
        }

        return view('report-form.index', compact('ace_reports', 'me','notsubmitted'));
    }


    /**
     *Shows all the reports (Archives)
     */
    public function archive() {

    }


    /**
     *Add new report form
     */
    public function add_report() {
        $me = new CommonFunctions();
        $project = Project::where('id', '=', 1)->where('status', '=', 1)->first();

        $indicators = Indicator::where('is_parent','=', 1)->where('status','=', 1)->where('upload','=', 1)->orderBy('identifier','asc')->get();
        $aces = Ace::where('active', '=', 1)->get();
        $ace_officers = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('roles.name', '=', 'ace-officer')->pluck('users.name', 'users.id');

        $reporting_periods = ReportingPeriod::all();
        $active_period = ReportingPeriod::where('active_period','=',true)->get();

        if ($project) {
            return view('report-form.new', compact('project', 'aces', 'me', 'ace_officers','indicators','reporting_periods','active_period'));
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
    public function save_report(Request $request) {
        $this->validate($request, [
            'project_id' => 'required|string|min:100',
            'reporting_period' => 'required|string',
            'submission_date' => 'nullable|string|date',
        ]);
        if($request->has('ace_officer')){
            $this->validate($request,[
                'ace_officer' =>'required',
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

        $report_exists= Report::where('ace_id',$ace_id)->where('reporting_period_id',$request->reporting_period)->first();

        if($report_exists){
            if($report_exists->status !=1){
                notify(new ToastNotification('Error!', 'You have a pending report on this period and ACE- Go and submit!', 'error'));
            }
            notify(new ToastNotification('Error!', 'A report by this ACE this Period has already been created and submitted!', 'error'));
            return back()->withInput();
        }
        $report = new Report();
        $report->project_id = $project_id;
        $report->ace_id = $ace_id;
        $report->status = 99;
        $report->reporting_period_id = $request->reporting_period;
        $report->submission_date = $submission_date;
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

    public function save_report_old(Request $request) {
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
                $report->fiduciary_report = $request->fiduciary_report;
                $report->editable =False;
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
                $report->fiduciary_report = $request->fiduciary_report;
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
                    ->where('reporting_period_id', '=', $request->reporting_period)
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
    public function view_report($id) {
        $id = Crypt::decrypt($id);
        $project = Project::where('id', '=', 1)->where('status', '=', 1)->first();
        $report = Report::find($id);
        $comment = AceComment::where('report_id',$id)->first();
        $indicators = Indicator::where('is_parent','=', 1)
            ->where('status','=', 1)
            ->where('show_on_report','=', 1)
            ->orderBy('identifier','asc')
            ->get();

        $reporting_period = ReportingPeriod::where('id',$report->reporting_period_id)->first();

        if (Auth::id() == $report->user_id || Auth::user()->hasRole(['webmaster|super-admin|admin|manager'])){

            //Get the aggregated result for Indicator 3
            $result = $this->generateAggregatedIndicator3Results($id);
            $indicator_5_2 = $this->generateAggregatedIndicator52Results($id);
            $indicator_4_1 = $this->generateAggregatedIndicator41Results($id);
            $indicator_7_3 = $this->generateAggregatedIndicator73Results($id);

            $values = ReportValue::where('report_id', '=', $id)->pluck('value', 'indicator_id');
            $aces = Ace::where('active', '=', 1)->get();

            return view('report-form.view', compact('project', 'report', 'reporting_period','comment','aces', 'values', 'indicators'
                , 'result', 'indicator_5_2','indicator_4_1','indicator_7_3'));

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

        $indicators = Indicator::where('is_parent','=', 1)
            ->where('status','=', 1)
            ->where('show_on_report','=', 1)
            ->orderBy('identifier','asc')
            ->get();
        $aces = Ace::where('active', '=', 1)->get();
        return view('report-form.report-indicator-status', compact('project', 'report', 'aces',
            'current_status', 'all_status', 'status_history','indicators'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
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
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
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

    /**
     * @param Request $request
     * @param $report_id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
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
        $id = Crypt::decrypt($id);
        $project = Project::where('id', '=', 1)->where('status', '=', 1)->first();
        $report = Report::find($id);

        $get_form = IndicatorDetails::query()
            ->where('report_id','=',$id)
            ->orderBy('order','asc')
            ->pluck('language');

        $reporting_period = ReportingPeriod::find($report->reporting_period_id);
        $reporting_periods = ReportingPeriod::all();


        if ($report->editable == 0 && Auth::user()->hasRole('ace-officer')){
            notify(new ToastNotification('Sorry!', 'This report is unavailable for editing!', 'warning'));
            return redirect()->route('report_submission.reports');
        }
        $values = ReportValue::where('report_id', '=', $id)->pluck('value', 'indicator_id');
        $indicators = Indicator::where('is_parent','=', 1)
            ->where('status','=', 1)
            ->where('show_on_report','=', 1)
            ->orderBy('identifier','asc')
            ->get();
        $comment = AceComment::where('report_id',$id)->first();

        $language = collect($get_form)->toArray();

        if(in_array('french',collect($get_form)->toArray())){
            $pdo_1 = $this->generateAggregatedIndicator3Results_fr($id);
            $pdo_52 = $this->generateAggregatedIndicator52Results_fr($id);

        }elseif(in_array('english',collect($get_form)->toArray()))
        {
            $pdo_1 = $this->generateAggregatedIndicator3Results($id);

            $pdo_2 = $this->generateAggregatedIndicator73Results($id);

            $pdo_52 = $this->generateAggregatedIndicator52Results($id);

        }


        $ace_officers = User::join('role_user', 'users.id', '=', 'role_user.user_id')
			->join('roles', 'role_user.role_id', '=', 'roles.id')
			->where('roles.name', '=', 'ace-officer')->pluck('users.name', 'users.id');
		$aces = Ace::where('active', '=', 1)->get();

		return view('report-form.edit', compact('project','language', 'reporting_period','reporting_periods','report', 'aces','comment','values', 'ace_officers',
            'indicators','pdo_1','pdo_2','pdo_52'));
        }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update_report(Request $request) {

        if (isset($request->submit)) {
            DB::transaction(function () use ($request) {
                $this->validate($request, [
                    'report_id' => 'required|string|min:100',
                    'indicators' => 'required|array|min:1',
                    'indicators.*' => 'required|numeric|min:0',
                ]);

                $report_id = Crypt::decrypt($request->report_id);

                $report = Report::find($report_id);
                $report->reporting_period_id = $request->reporting_period;

                $report->status = 1;
                if (isset($request->ace_officer)) {
                    $ace_id = User::find(Crypt::decrypt($request->ace_officer))->ace;
                    $report->user_id = Crypt::decrypt($request->ace_officer);
                    $report->ace_id = $ace_id;
                } else {
                    $report->user_id = Auth::id();
                }
//                $report->notify(new ReportSubmission());

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
                $comment_object = AceComment::where('report_id',$report_id)->first();
                if(isset($request->report_comment)){
                    if($comment_object) {
                        $comment_object->update([
                            'user_id' => $user_id,
                            'report_id' => $report_id,
                            'comments' => $request->report_comment,
                        ]);
                    }else{
                        AceComment::updateorCreate([
                            'user_id' => $user_id,
                            'report_id' => $report_id,
                            'comments' => $request->report_comment,
                        ]);
                    }

                }
                $email_ace = Ace::query()->where('id',$report->ace_id);

                $emails = array_merge($email_ace->pluck('email')->toArray(),[config('mail.aau_email')]);

                Mail::send('mail.report-mail',['the_ace'=>$email_ace,'report'=>$report],
                    function ($message) use($emails) {
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
                $parent_indicators = Indicator::where('is_parent','=', 1)
                    ->where('project_id','=', 1)
                    ->where('status','=', 1)
                    ->where('show_on_report','=', 1)
                    ->orderBy('identifier','asc')
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
                $comment_object = AceComment::where('report_id',$report_id)->first();
                if(isset($request->report_comment)){
                    if($comment_object) {
                        $comment_object->update([
                            'user_id' => $user_id,
                            'report_id' => $report_id,
                            'comments' => $request->report_comment,
                        ]);
                    }else{
                        AceComment::updateorCreate([
                            'user_id' => $user_id,
                            'report_id' => $report_id,
                            'comments' => $request->report_comment,
                        ]);
                    }

                }

                notify(new ToastNotification('Successful!', 'Report Saved!', 'success'));
            });
            if (isset($request->continue)){
//                return redirect()->route('report_submission.upload_indicator',$request->report_id);
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
    function report_review($id) {

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
     * @return array
     */
    public function generateAggregatedIndicator3Results_fr($report_id)
    {

        $pdo_1_values = array();
        $phd = config('app.filters_fr.phd_text');
        $masters = config('app.filters_fr.masters_text');
        $bachelors = config('app.filters_fr.bachelors_text');
        $course = config('app.filters_fr.Course_text');

        $all_students = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id);
        $total_students = $all_students->count();

        /**
         * PDO Indicator 1
         */
        $regional = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('regionalite','=', "Regional")
                    ->orWhere('regionalite','=', "regional")
                    ->orWhere('regionalite','like', "r%")
                    ->orWhere('regionalite','like', "R%");
            });

        $regional_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where(function($query) {
                $query->where('genre','=', "Femme")
                    ->orWhere('genre','=', "F");
            })
            ->where(function($query)
            {
                $query->where('regionalite','=', "Regional")
                    ->orWhere('regionalite','=', "regional")
                    ->orWhere('regionalite','like', "r%")
                    ->orWhere('regionalite','like', "R%");
            });

        $national = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('regionalite','=', "National")
                    ->orWhere('regionalite','=', "national")
                    ->orWhere('regionalite','like', "n%")
                    ->orWhere('regionalite','like', "N%");
            });

        $national_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where(function($query) {
                $query->where('genre','=', "F")
                    ->orWhere('genre','=', "Femme");
            })
            ->where(function($query)
            {
                $query->where('regionalite','=', "National")
                    ->orWhere('regionalite','=', "national")
                    ->orWhere('regionalite','like', "n%")
                    ->orWhere('regionalite','like', "N%");
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
            ->where('report_id','=', $report_id)
            ->where("level","=",$phd)
            ->where(function($query)
            {
                $query->where('regional-status','=', "Regional")
                    ->orWhere('regional-status','=', "regional")
                    ->orWhere('regional-status','like', "r%")
                    ->orWhere('regional-status','like', "R%");
            });

        $phd_regional_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where("level","=",$phd)
            ->where(function($query)
            {
                $query->where('regionalite','=', "Regional")
                    ->orWhere('regionalite','=', "regional")
                    ->orWhere('regionalite','like', "r%")
                    ->orWhere('regionalite','like', "R%");
            })
            ->where(function($query) {
                $query->where('gender','=', "F")
                    ->orWhere('gender','=', "Female");
            });

        $phd_national = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where("level","=",$phd)
            ->where(function($query)
            {
                $query->where('regionalite','=', "National")
                    ->orWhere('regionalite','=', "national")
                    ->orWhere('regionalite','like', "n%")
                    ->orWhere('regionalite','like', "N%");
            });

        $phd_national_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('regionalite','=', "National")
                    ->orWhere('regionalite','=', "national")
                    ->orWhere('regionalite','like', "n%")
                    ->orWhere('regionalite','like', "N%");
            })
            ->where("level","=",$phd)
            ->where(function($query) {
                $query->where('genre','=', "F")
                    ->orWhere('genre','=', "Femme");
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
            ->where('report_id','=', $report_id)
            ->where("level","=",$masters)
            ->where(function($query)
            {
                $query->where('regionalite','=', "Regional")
                    ->orWhere('regionalite','=', "regional")
                    ->orWhere('regionalite','like', "r%")
                    ->orWhere('regionalite','like', "R%");
            });

        $masters_regional_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where("level","=",$masters)
            ->where(function($query)
            {
                $query->where('regionalite','=', "Regional")
                    ->orWhere('regionalite','=', "regional")
                    ->orWhere('regionalite','like', "r%")
                    ->orWhere('regionalite','like', "R%");
            })
            ->where(function($query) {
                $query->where('genre','=', "F")
                    ->orWhere('genre','=', "Femme");
            });

        $masters_national = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where("level","=",$masters)
            ->where(function($query)
            {
                $query->where('regionalite','=', "national")
                    ->orWhere('regionalite','=', "national")
                    ->orWhere('regionalite','like', "n%")
                    ->orWhere('regionalite','like', "N%");
            });

        $masters_national_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('regionalite','=', "National")
                    ->orWhere('regionalite','=', "national")
                    ->orWhere('regionalite','like', "n%")
                    ->orWhere('regionalite','like', "N%");
            })
            ->where("level","=",$masters)
            ->where(function($query) {
                $query->where('genre','=', "F")
                    ->orWhere('genre','=', "Femme");
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
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('regionalite','=', "Regional")
                    ->orWhere('regionalite','=', "regional")
                    ->orWhere('regionalite','like', "r%")
                    ->orWhere('regionalite','like', "R%");
            })
            ->where("level","=",$bachelors);

        $bachelors_regional_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('regionalite','=', "Regional")
                    ->orWhere('regionalite','=', "regional")
                    ->orWhere('regionalite','like', "r%")
                    ->orWhere('regionalite','like', "R%");
            })
            ->where("level","=",$bachelors)
            ->where(function($query) {
                $query->where('genre','=', "F")
                    ->orWhere('genre','=', "Femme");
            });
        $course_regional = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('regionalite','=', "Regional")
                    ->orWhere('regionalite','=', "regional")
                    ->orWhere('regionalite','like', "r%")
                    ->orWhere('regionalite','like', "R%");
            })
            ->where("level",'like',"%$course%");
//        dd($course_regional);

        $course_regional_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('regionalite','=', "Regional")
                    ->orWhere('regionalite','=', "regional")
                    ->orWhere('regionalite','like', "r%")
                    ->orWhere('regionalite','like', "R%");
            })
            ->where("level",'like',"%$course%")
            ->where(function($query) {
                $query->where('genre','=', "F")
                    ->orWhere('genre','=', "Femme");
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
            ->where('report_id','=', $report_id)
            ->where(function($query) {
                $query->where('genre','=', "F")
                    ->orWhere('genre','=', "Femme");
            });

        $bachelors_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where("level","=",$bachelors)
            ->where(function($query) {
                $query->where('genre','=', "F")
                    ->orWhere('genre','=', "Femme");
            });

        $course_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where("level",'like',"%$course%")
            ->where(function($query) {
                $query->where('genre','=', "F")
                    ->orWhere('genre','=', "Femme");
            });

        $pdo_1_values["pdo_indicator_1d"]["female_total"] = $female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_phd_total"] = $phd_regional->count()+$phd_national_female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_phd_regional"] = $phd_regional_female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_masters_total"] = $masters_regional_female->count()+$masters_national_female->count();
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
            ->where('report_id','=', $report_id)
            ->where("level",'like',"%$course%")
            ->where(function($query)
            {
                $query->where('regionalite','=', "National")
                    ->orWhere('regionalite','=', "national")
                    ->orWhere('regionalite','like', "n%")
                    ->orWhere('regionalite','like', "N%");
            });

        $course_national_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('regionalite','=', "national")
                    ->orWhere('regionalite','=', "national")
                    ->orWhere('regionalite','like', "n%")
                    ->orWhere('regionalite','like', "N%");
            })
            ->where("level",'like',"%$course%")
            ->where(function($query) {
                $query->where('genre','=', "F")
                    ->orWhere('genre','=', "Femme");
            });

        $pdo_1_values["pdo_indicator_1e"]["sc_regional_total"] = $course_regional->count();
        $pdo_1_values["pdo_indicator_1e"]["sc_regional_female"] = $course_regional_female->count();
        $pdo_1_values["pdo_indicator_1e"]["sc_national_total"] = $course_national->count();
        $pdo_1_values["pdo_indicator_1e"]["sc_national_female"] = $course_national_female->count();

        return $pdo_1_values;

	}

    public function generateAggregatedIndicator3Results($report_id)
    {
        $pdo_1_values = array();
        $phd = config('app.filters.phd_text');
        $masters = config('app.filters.masters_text');
        $bachelors = config('app.filters.bachelors_text');
        $course = config('app.filters.Course_text');

        $all_students = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id);
        $total_students = $all_students->count();

        /**
         * PDO Indicator 1
         */
        $regional = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('regional-status','=', "Regional")
                    ->orWhere('regional-status','=', "regional")
                    ->orWhere('regional-status','like', "r%")
                    ->orWhere('regional-status','like', "R%");
            });

        $regional_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where(function($query) {
                $query->where('gender','=', "F")
                    ->orWhere('gender','=', "Female");
            })
            ->where(function($query)
            {
                $query->where('regional-status','=', "Regional")
                    ->orWhere('regional-status','=', "regional")
                    ->orWhere('regional-status','like', "r%")
                    ->orWhere('regional-status','like', "R%");
            });

        $national = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('regional-status','=', "National")
                    ->orWhere('regional-status','=', "national")
                    ->orWhere('regional-status','like', "n%")
                    ->orWhere('regional-status','like', "N%");
            });

        $national_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where(function($query) {
                $query->where('gender','=', "F")
                    ->orWhere('gender','=', "Female");
            })
            ->where(function($query)
            {
                $query->where('regional-status','=', "National")
                    ->orWhere('regional-status','=', "national")
                    ->orWhere('regional-status','like', "n%")
                    ->orWhere('regional-status','like', "N%");
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
            ->where('report_id','=', $report_id)
            ->where("level","=",$phd)
            ->where(function($query)
            {
                $query->where('regional-status','=', "Regional")
                    ->orWhere('regional-status','=', "regional")
                    ->orWhere('regional-status','like', "r%")
                    ->orWhere('regional-status','like', "R%");
            });

        $phd_regional_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where("level","=",$phd)
            ->where(function($query)
            {
                $query->where('regional-status','=', "Regional")
                    ->orWhere('regional-status','=', "regional")
                    ->orWhere('regional-status','like', "r%")
                    ->orWhere('regional-status','like', "R%");
            })
            ->where(function($query) {
                $query->where('gender','=', "F")
                    ->orWhere('gender','=', "Female");
            });

        $phd_national = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where("level","=",$phd)
            ->where(function($query)
            {
                $query->where('regional-status','=', "National")
                    ->orWhere('regional-status','=', "national")
                    ->orWhere('regional-status','like', "n%")
                    ->orWhere('regional-status','like', "N%");
            });

        $phd_national_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('regional-status','=', "National")
                    ->orWhere('regional-status','=', "national")
                    ->orWhere('regional-status','like', "n%")
                    ->orWhere('regional-status','like', "N%");
            })
            ->where("level","=",$phd)
            ->where(function($query) {
                $query->where('gender','=', "F")
                    ->orWhere('gender','=', "Female");
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
            ->where('report_id','=', $report_id)
            ->where("level","=",$masters)
            ->where(function($query)
            {
                $query->where('regional-status','=', "Regional")
                    ->orWhere('regional-status','=', "regional")
                    ->orWhere('regional-status','like', "r%")
                    ->orWhere('regional-status','like', "R%");
            });

        $masters_regional_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where("level","=",$masters)
            ->where(function($query)
            {
                $query->where('regional-status','=', "Regional")
                    ->orWhere('regional-status','=', "regional")
                    ->orWhere('regional-status','like', "r%")
                    ->orWhere('regional-status','like', "R%");
            })
            ->where(function($query) {
                $query->where('gender','=', "F")
                    ->orWhere('gender','=', "Female");
            });

        $masters_national = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where("level","=",$masters)
            ->where(function($query)
            {
                $query->where('regional-status','=', "National")
                    ->orWhere('regional-status','=', "national")
                    ->orWhere('regional-status','like', "n%")
                    ->orWhere('regional-status','like', "N%");
            });

        $masters_national_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('regional-status','=', "National")
                    ->orWhere('regional-status','=', "national")
                    ->orWhere('regional-status','like', "n%")
                    ->orWhere('regional-status','like', "N%");
            })
            ->where("level","=",$masters)
            ->where(function($query) {
                $query->where('gender','=', "F")
                    ->orWhere('gender','=', "Female");
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
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('regional-status','=', "Regional")
                    ->orWhere('regional-status','=', "regional")
                    ->orWhere('regional-status','like', "r%")
                    ->orWhere('regional-status','like', "R%");
            })
            ->where("level","=",$bachelors);

        $bachelors_regional_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('regional-status','=', "Regional")
                    ->orWhere('regional-status','=', "regional")
                    ->orWhere('regional-status','like', "r%")
                    ->orWhere('regional-status','like', "R%");
            })
            ->where("level","=",$bachelors)
            ->where(function($query) {
                $query->where('gender','=', "F")
                    ->orWhere('gender','=', "Female");
            });
        $course_regional = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('regional-status','=', "Regional")
                    ->orWhere('regional-status','=', "regional")
                    ->orWhere('regional-status','like', "r%")
                    ->orWhere('regional-status','like', "R%");
            })
            ->where("level",'like',"%$course%");
//        dd($course_regional);

        $course_regional_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('regional-status','=', "Regional")
                    ->orWhere('regional-status','=', "regional")
                    ->orWhere('regional-status','like', "r%")
                    ->orWhere('regional-status','like', "R%");
            })
            ->where("level",'like',"%$course%")
            ->where(function($query) {
                $query->where('gender','=', "F")
                    ->orWhere('gender','=', "Female");
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
            ->where('report_id','=', $report_id)
            ->where(function($query) {
                $query->where('gender','=', "F")
                    ->orWhere('gender','=', "Female");
            });

        $bachelors_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where("level","=",$bachelors)
            ->where(function($query) {
                $query->where('gender','=', "F")
                    ->orWhere('gender','=', "Female");
            });

        $course_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where("level",'like',"%$course%")
            ->where(function($query) {
                $query->where('gender','=', "F")
                    ->orWhere('gender','=', "Female");
            });

        $pdo_1_values["pdo_indicator_1d"]["female_total"] = $female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_phd_total"] = $phd_regional->count()+$phd_national_female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_phd_regional"] = $phd_regional_female->count();
        $pdo_1_values["pdo_indicator_1d"]["female_masters_total"] = $masters_regional_female->count()+$masters_national_female->count();
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
            ->where('report_id','=', $report_id)
            ->where("level",'like',"%$course%")
            ->where(function($query)
            {
                $query->where('regional-status','=', "National")
                    ->orWhere('regional-status','=', "national")
                    ->orWhere('regional-status','like', "n%")
                    ->orWhere('regional-status','like', "N%");
            });

        $course_national_female = DB::connection('mongodb')
            ->collection('indicator_3')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('regional-status','=', "National")
                    ->orWhere('regional-status','=', "national")
                    ->orWhere('regional-status','like', "n%")
                    ->orWhere('regional-status','like', "N%");
            })
            ->where("level",'like',"%$course%")
            ->where(function($query) {
                $query->where('gender','=', "F")
                    ->orWhere('gender','=', "Female");
            });

        $pdo_1_values["pdo_indicator_1e"]["sc_regional_total"] = $course_regional->count();
        $pdo_1_values["pdo_indicator_1e"]["sc_regional_female"] = $course_regional_female->count();
        $pdo_1_values["pdo_indicator_1e"]["sc_national_total"] = $course_national->count();
        $pdo_1_values["pdo_indicator_1e"]["sc_national_female"] = $course_national_female->count();

        return $pdo_1_values;
	}
    /**
     * Generate Aggregated results for Indicator 7.3
     * @param $report_id
     * @return array
     */
    public function generateAggregatedIndicator73Results($report_id)
    {
        $pdo_7_3_values = array();



      /** pdo 2
       *'pdo_indicaror_2' => [
      'total_accreditations','international_accreditation','regional_accreditation','national_accreditation',
      'gap_assessment','self_evaluation'
      ],
       */


      $total_accreditations = DB::connection('mongodb')
          ->collection('indicator_7.3')
          ->where('report_id','=', $report_id)
      ->count();



      $international_accreditation =  DB::connection('mongodb')
        ->collection('indicator_7.3')
        ->where('report_id','=', $report_id)
        ->where(function($query)
        {
            $query->where('type-of-accreditation2','=', "International")
                ->orWhere('type-of-accreditation2','=', "international")
                ->orWhere('type-of-accreditation2','like', "i%")
                ->orWhere('type-of-accreditation2','like', "I%");
        });

      $national_accreditation = DB::connection('mongodb')
            ->collection('indicator_7.3')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('type-of-accreditation2','=', "National")
                    ->orWhere('type-of-accreditation2','=', "national")
                    ->orWhere('type-of-accreditation2','like', "n%")
                    ->orWhere('type-of-accreditation2','like', "N%");
            });

        $self_evaluation = DB::connection('mongodb')
            ->collection('indicator_7.3')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('type-of-accreditation2','=', "Self-evaluation")
                    ->orWhere('type-of-accreditation2','like', "self%")
                    ->orWhere('type-of-accreditation2','like', "Self%");
            });

        $gap_assessment = DB::connection('mongodb')
            ->collection('indicator_7.3')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('type-of-accreditation2','=', "Gap Assessment")
                    ->orWhere('type-of-accreditation2','like', "gap%")
                    ->orWhere('type-of-accreditation2','like', "Gap%");
            });

        $regional_accreditation = DB::connection('mongodb')
            ->collection('indicator_7.3')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('type-of-accreditation2','=', "Regional")
                    ->orWhere('type-of-accreditation2','=', "regional")
                    ->orWhere('type-of-accreditation2','like', "r%")
                    ->orWhere('type-of-accreditation2','like', "R%");
            });


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
        $indicators = Indicator::where('is_parent','=', 1)
            ->where('status','=', 1)
            ->where('show_on_report','=', 1)
            ->where('parent_id','=', 3)
            ->orderBy('identifier','asc')
            ->get();

        $filters = ["PhD","Master","Bachelors","Course"];
        $indicator_3_values = array();

        foreach ($indicators as $key=>$indicator) {
            $national_and_men = DB::connection('mongodb')
                ->collection('indicator_3')
                ->where('report_id','=', $report_id)
                ->where(function($query)
                {
                    $query->where('gender','=', "M")
                        ->orWhere('gender','=', "Male");
                })
                ->where(function($query)
                {
                    $query->where('regional-status','=', "National")
                        ->orWhere('regional-status','=', "national")
                        ->orWhere('regional-status','like', "n%")
                        ->orWhere('regional-status','like', "N%");
                });
//                ->where('regional-status','=', "National");
            $national_and_women = DB::connection('mongodb')
                ->collection('indicator_3')
                ->where('report_id','=', $report_id)
                ->where(function($query)
                {
                    $query->where('gender','=', "F")
                        ->orWhere('gender','=', "Female");
                })
                ->where(function($query)
                {
                    $query->where('regional-status','=', "National")
                        ->orWhere('regional-status','=', "national")
                        ->orWhere('regional-status','like', "n%")
                        ->orWhere('regional-status','like', "N%");
                });
//                ->where('regional-status','=', "National");
            $regional_and_men = DB::connection('mongodb')
                ->collection('indicator_3')
                ->where('report_id','=', $report_id)
                ->where(function($query)
                {
                    $query->where('regional-status','=', "Regional")
                        ->orWhere('regional-status','=', "regional")
                        ->orWhere('regional-status','like', "r%")
                        ->orWhere('regional-status','like', "R%");
                })
//                ->where('regional-status','=', "Regional")
                ->where(function($query)
                {
                    $query->where('gender','=', "M")
                        ->orWhere('gender','=', "Male");
                });
            $regional_and_women = DB::connection('mongodb')
                ->collection('indicator_3')
                ->where('report_id','=', $report_id)
                ->where(function($query)
                {
                    $query->where('regional-status','=', "Regional")
                        ->orWhere('regional-status','=', "regional")
                        ->orWhere('regional-status','like', "r%")
                        ->orWhere('regional-status','like', "R%");
                })
//                ->where('regional-status','=', "Regional")
                ->where(function($query)
                {
                    $query->where('gender','=', "F")
                        ->orWhere('gender','=', "Female");
                });

            $identifier = $indicator->identifier;
            $filter_value = $filters[$key];

            $indicator_3_values["$identifier"]["national_and_men"] = $national_and_men->where("level","like","%$filter_value%")->count();
            $indicator_3_values["$identifier"]["national_and_women"] = $national_and_women->where("level","like","%$filter_value%")->count();
            $indicator_3_values["$identifier"]["regional_and_men"] = $regional_and_men->where("level","like","%$filter_value%")->count();
            $indicator_3_values["$identifier"]["regional_and_women"] = $regional_and_women->where("level","like","%$filter_value%")->count();
        }

        return $indicator_3_values;

    }

    /**
     * Generate Aggregated results for Indicator 5.2
     * @param $report_id
     * @return array
     */
    public function generateAggregatedIndicator52Results($report_id)
    {

//        'total_number_of_interns','students','faculty'

        $indicator_5_2_values = array();

        $total_number_of_interns= DB::connection('mongodb')
            ->collection('indicator__p_d_o_indicator5')
            ->where('report_id','=', $report_id)
            ->count();


        $students= DB::connection('mongodb')->collection('indicator__p_d_o_indicator5')
            ->where('report_id', $report_id)
            ->where(function ($query) {
                $query->where('studentfaculty', 'like', "Student%")
                    ->orWhere('studentfaculty', 'like', "stud%");
            });
        $faculty = DB::connection('mongodb')->collection('indicator__p_d_o_indicator5')
            ->where('report_id', $report_id)
            ->where(function ($query) {
                $query->where('studentfaculty', 'like', "F%")
                    ->orWhere('studentfaculty', 'like', "f%");
            });

        $indicator_5_2_values["pdo_indicator_5"]["total_number_of_interns"] = $total_number_of_interns;
        $indicator_5_2_values["pdo_indicator_5"]["students"] = $students->count();
        $indicator_5_2_values["pdo_indicator_5"]["faculty"] = $faculty->count();
//        dd($indicator_5_2_values);

        return $indicator_5_2_values;
    }

    public function generateAggregatedIndicator52Results_fr($report_id)
    {


        $indicator_5_2_values = array();

        $total_number_of_interns= DB::connection('mongodb')
            ->collection('indicator__p_d_o_indicator5')
            ->where('report_id','=', $report_id)
            ->count();


        $students= DB::connection('mongodb')->collection('indicator__p_d_o_indicator5')
            ->where('report_id', $report_id)
            ->where(function ($query) {
                $query->where('Etudiant/ Professeur', 'like', "Etudiant%")
                    ->orWhere('Etudiant/ Professeur', 'like', "etudiant%");
            });
        $faculty = DB::connection('mongodb')->collection('indicator__p_d_o_indicator5')
            ->where('report_id', $report_id)
            ->where(function ($query) {
                $query->where('Etudiant/ Professeur', 'like', "Professeur%")
                    ->orWhere('Etudiant/ Professeur', 'like', "p%");
            });

        $indicator_5_2_values["pdo_indicator_5"]["total_number_of_interns"] = $total_number_of_interns;
        $indicator_5_2_values["pdo_indicator_5"]["students"] = $students->count();
        $indicator_5_2_values["pdo_indicator_5"]["faculty"] = $faculty->count();


        return $indicator_5_2_values;
    }

    /**
     * Generate Aggregated results for Indicator 4.1
     * @param $report_id
     * @return array
     */
    public function generateAggregatedIndicator41Results($report_id)
    {
        $indicator_4_1_values = array();

        $indicator_4_1_values['national']= DB::connection('mongodb')
            ->collection('indicator_4.1')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('type-of-accreditation2','=', "National")
                    ->orWhere('type-of-accreditation2','=', "national")
                    ->orWhere('type-of-accreditation2','like', "n%")
                    ->orWhere('type-of-accreditation2','like', "N%");
            })->count();

        $indicator_4_1_values['regional'] = DB::connection('mongodb')
            ->collection('indicator_4.1')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('type-of-accreditation2','=', "Regional")
                    ->orWhere('type-of-accreditation2','=', "regional")
                    ->orWhere('type-of-accreditation2','like', "r%")
                    ->orWhere('type-of-accreditation2','like', "R%");
            })->count();

        $indicator_4_1_values['international'] = DB::connection('mongodb')
            ->collection('indicator_4.1')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('type-of-accreditation2','=', "International")
                    ->orWhere('type-of-accreditation2','=', "international")
                    ->orWhere('type-of-accreditation2','like', "i%")
                    ->orWhere('type-of-accreditation2','like', "I%");
            })->count();

        $indicator_4_1_values['gap-assessment'] = DB::connection('mongodb')
            ->collection('indicator_4.1')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('type-of-accreditation2','=', "Gap")
                    ->orWhere('type-of-accreditation2','=', "gap")
                    ->orWhere('type-of-accreditation2','like', "gap%")
                    ->orWhere('type-of-accreditation2','like', "Gap%");
            })->count();

        $indicator_4_1_values['self-evaluation'] = DB::connection('mongodb')
            ->collection('indicator_4.1')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('type-of-accreditation2','=', "Self Fvaluation")
                    ->orWhere('type-of-accreditation2','=', "self evaluation")
                    ->orWhere('type-of-accreditation2','like', "self%")
                    ->orWhere('type-of-accreditation2','like', "Self%");
            })->count();

        $indicator_4_1_values['course'] = DB::connection('mongodb')
            ->collection('indicator_4.1')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('type-of-accreditation2','=', "New Course")
                    ->orWhere('type-of-accreditation2','=', "new course")
                    ->orWhere('type-of-accreditation2','like', "new%")
                    ->orWhere('type-of-accreditation2','like', "New%");
            })->count();

        return $indicator_4_1_values;
    }

    /**
     * Generate Aggregated results for Indicator 4.1
     * @param $report_id
     * @return array
     */
    public function generateAggregatedIndicator42Results($report_id)
    {
        $indicator_4_2_values = array();

        $indicator_4_2_values['non-regional']= DB::connection('mongodb')
            ->collection('indicator_4.2')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('country','<>', "Regional")
                    ->orWhere('country','<>', "regional");
//                    ->orWhere('type-of-accreditation2','like', "n%")
//                    ->orWhere('type-of-accreditation2','like', "N%");
            })->count();

        $indicator_4_2_values['regional'] = DB::connection('mongodb')
            ->collection('indicator_4.2')
            ->where('report_id','=', $report_id)
            ->where(function($query)
            {
                $query->where('country','=', "Regional")
                    ->orWhere('country','=', "regional");
//                    ->orWhere('type-of-accreditation2','like', "r%")
//                    ->orWhere('type-of-accreditation2','like', "R%");
            })->count();

        return $indicator_4_2_values;
    }

//    public function generateAggregatedIndicator42Results($report_id)
//    {
//        $indicator_4_2_values = array();
//
//        $indicator_4_2_values['national']= DB::connection('mongodb')
//            ->collection('indicator_4.2')
//            ->where('report_id','=', $report_id)
//            ->where(function($query)
//            {
//                $query->where('type-of-accreditation2','=', "National")
//                    ->orWhere('type-of-accreditation2','=', "national")
//                    ->orWhere('type-of-accreditation2','like', "n%")
//                    ->orWhere('type-of-accreditation2','like', "N%");
//            })->count();
//
//        $indicator_4_2_values['regional'] = DB::connection('mongodb')
//            ->collection('indicator_4.1')
//            ->where('report_id','=', $report_id)
//            ->where(function($query)
//            {
//                $query->where('type-of-accreditation2','=', "Regional")
//                    ->orWhere('type-of-accreditation2','=', "regional")
//                    ->orWhere('type-of-accreditation2','like', "r%")
//                    ->orWhere('type-of-accreditation2','like', "R%");
//            })->count();
//
//        $indicator_4_2_values['international'] = DB::connection('mongodb')
//            ->collection('indicator_4.1')
//            ->where('report_id','=', $report_id)
//            ->where(function($query)
//            {
//                $query->where('type-of-accreditation2','=', "International")
//                    ->orWhere('type-of-accreditation2','=', "international")
//                    ->orWhere('type-of-accreditation2','like', "i%")
//                    ->orWhere('type-of-accreditation2','like', "I%");
//            })->count();
//
//        $indicator_4_2_values['gap-assessment'] = DB::connection('mongodb')
//            ->collection('indicator_4.1')
//            ->where('report_id','=', $report_id)
//            ->where(function($query)
//            {
//                $query->where('type-of-accreditation2','=', "Gap")
//                    ->orWhere('type-of-accreditation2','=', "gap")
//                    ->orWhere('type-of-accreditation2','like', "gap%")
//                    ->orWhere('type-of-accreditation2','like', "Gap%");
//            })->count();
//
//        $indicator_4_2_values['self-evaluation'] = DB::connection('mongodb')
//            ->collection('indicator_4.1')
//            ->where('report_id','=', $report_id)
//            ->where(function($query)
//            {
//                $query->where('type-of-accreditation2','=', "Self Fvaluation")
//                    ->orWhere('type-of-accreditation2','=', "self evaluation")
//                    ->orWhere('type-of-accreditation2','like', "self%")
//                    ->orWhere('type-of-accreditation2','like', "Self%");
//            })->count();
//
//        $indicator_4_2_values['course'] = DB::connection('mongodb')
//            ->collection('indicator_4.1')
//            ->where('report_id','=', $report_id)
//            ->where(function($query)
//            {
//                $query->where('type-of-accreditation2','=', "New Course")
//                    ->orWhere('type-of-accreditation2','=', "new course")
//                    ->orWhere('type-of-accreditation2','like', "new%")
//                    ->orWhere('type-of-accreditation2','like', "New%");
//            })->count();
//
//        return $indicator_4_2_values;
//    }




    public static function getReportingName($id){
        $period = ReportingPeriod::find($id);
        $start_period = date('m-Y',strtotime($period->period_start));
        $monthNum1=date('m',strtotime($period->period_start));
        $monthName1 = date("M", mktime(0, 0, 0, $monthNum1, 10));
        $year1 = date('Y',strtotime($period->period_start));
        $start = $monthName1 .', '.$year1;
        $monthNum2=date('m',strtotime($period->period_end));
        $monthName2 = date("M", mktime(0, 0, 0, $monthNum2, 10));
        $year2 = date('Y',strtotime($period->period_end));
        $end =$monthName2 .', '.$year2;
        $full_period = $start . "   -    " . $end;
        return $full_period;
    }



    public function setEditMode($id){
            $report_id = Crypt::decrypt($id);
            $report = Report::find($report_id);

            $status_tracker = ReportStatusTracker::find($report_id);

            $status= $status_tracker->status_code;

            if($report->editable !=1) {
                $report->status = 99;
                $report->editable=true;
                $report->save();
                notify(new ToastNotification('Success!', 'The report submission has been reopened for edit!', 'success'));
            }else {
                notify(new ToastNotification('Notice!', 'The report is already in edit mode!', 'warning'));
            }return back();


    }
}

<?php

namespace App\Http\Controllers;

use App\Classes\ToastNotification;
use App\ReportingPeriod;
use App\SystemOption;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ApplicationSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $apps = SystemOption::all();

        $periods = ReportingPeriod::all()->sortByDesc('id');
        return view('settings.app.settings', compact('apps', 'periods'));
    }

    public function setName(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:3'
        ]);
        $option_name = 'app_name';
        $name = SystemOption::updateOrCreate(
            [
                'option_name' => $option_name
            ], [
                'option_value' => $option_name,
                'slug' => str_slug($request->name),
                'display_name' => strtoupper($request->name),
            ]
        );
        if ($name) {
            notify(new ToastNotification('Successful', 'Application name updated.', 'success'));
        } else {
            notify(new ToastNotification('Error', 'Please try again.', 'error'));
        }
        return back();
    }

    public function setEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email'
        ]);
        $option_name = 'app_email';
        $name = SystemOption::updateOrCreate(
            [
                'option_name' => $option_name
            ], [
                'option_value' => $option_name,
//                'slug' => str_slug($request->name),
                'display_name' => strtoupper($request->email),
            ]
        );
        if ($name) {
            notify(new ToastNotification('Successful', 'Application email updated.', 'success'));
        } else {
            notify(new ToastNotification('Error', 'Please try again.', 'error'));
        }
        return back();
    }

    public function generation_status(Request $request)
    {
        $this->validate($request, [
            'status' => 'required|numeric'
        ]);
        $option_name = 'generation_status';
        if ($request->status == 1) {
            $display_name = 'Submitted';
        } else {
            $display_name = 'Report Verified';
        }
        $name = SystemOption::updateOrCreate(
            [
                'option_name' => $option_name
            ], [
                'option_value' => $request->status,
                'display_name' => strtoupper($display_name),
            ]
        );
//        dd($request->all());
        if ($name) {
            notify(new ToastNotification('Successful', 'Report Generation Status updated.', 'success'));
        } else {
            notify(new ToastNotification('Error', 'Please try again.', 'error'));
        }
        return back();
    }

    public function changeDeadlineStatus(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric|min:0|max:1'
        ]);
        if ($request->id == 1) {
            $key = 0;
            $worked = SystemOption::where('option_name', '=', 'app_deadline')->update(['status' => $key]);

            if ($worked) {
                $message = 'Report Submission closed.';
                $type = 'warning';
            } else {
                $message = 'Something went wrong!.';
                $type = 'danger';
            }
        } else {
            $key = 1;
            $worked = SystemOption::where('option_name', '=', 'app_deadline')->update(['status' => $key]);

            if ($worked) {
                $message = 'Report Submission opened.';
                $type = 'success';
            } else {
                $message = 'Something went wrong!';
                $type = 'danger';
            }
        }
        return response()->json(['key' => $key, 'message' => $message, 'type' => $type]);
    }

    public function setDeadline(Request $request)
    {
        $this->validate($request, [
            'deadline' => 'required|date'
        ]);
        $option_name = 'app_deadline';
        $name = SystemOption::updateOrCreate(
            [
                'option_name' => $option_name
            ], [
                'option_value' => $option_name,
//                'slug' => str_slug($request->name),
                'display_name' => $request->deadline,
            ]
        );
        if ($name) {
            notify(new ToastNotification('Successful', 'Report submission deadline updated.', 'success'));
        } else {
            notify(new ToastNotification('Error', 'Please try again.', 'error'));
        }
        return back();
    }

    public function saveReportingPeriod(Request $request)
    {
        $start = Carbon::createFromFormat('m-Y', $request->period_start)->startOfMonth();
        $end = Carbon::createFromFormat('m-Y', $request->period_end)->endOfMonth();
        $this->validate($request, [
            'period_start' => ['required', 'string',
                function ($attribute, $value, $fail) use ($request, $start, $end) {
                    if ($start->greaterThan($end)) {
                        notify(new ToastNotification('Oops!', 'Start date should be less than the end date', 'error'));
                        $fail($attribute . ' ');
                    }
                },],
            'period_end' => ['required', 'string']
        ]);
        $record = ReportingPeriod::where('period_start', $start->format('Y-m-d'))
            ->where('period_end', $end->format('Y-m-d'))->first();
        if (empty($record)) {
            $new_period = new ReportingPeriod();
            $new_period->period_start = $start->format('Y-m-d');
            $new_period->period_end = $end->format('Y-m-d');
            $new_period->active_period = true;
            $saved = $new_period->save();

            if ($saved) {
                ReportingPeriod::where('id', '!=', $new_period->id)->update(['active_period' => false]);
                notify(new ToastNotification('Successful', 'Reporting Period Added.', 'success'));
                return back();
            }
            notify(new ToastNotification('Error', 'Please try again.', 'error'));
            return back()->withInput();
        }
        notify(new ToastNotification('Error', 'This reporting period already exists', 'error'));
        return back()->withInput();
    }

    public function deleteReportingPeriod($id)
    {
        $id = Crypt::decrypt($id);
        ReportingPeriod::destroy($id);

        notify(new ToastNotification('Successful!', 'Reporting Period Deleted!', 'success'));
        return back();
    }


    public function editReportingPeriod(Request $request)
    {
        $id = Crypt::decrypt($request->id);

        $update_this_period = ReportingPeriod::find($id);

        $startdate = explode('-', $update_this_period->period_start);
        $toupdate_start_period = $startdate[1] . '-' . $startdate[0];

        $enddate = explode('-', $update_this_period->period_end);
        $toupdate_end_period = $enddate[1] . '-' . $enddate[0];

        $view = view('settings.app.edit_reporting_period_view', compact('update_this_period', 'toupdate_start_period', 'toupdate_end_period'))->render();
        return response()->json(['theView' => $view]);
    }


    public function updateReportingPeriod(Request $request)
    {
        $start = Carbon::createFromFormat('m-Y', $request->period_start)->startOfMonth();
        $end = Carbon::createFromFormat('m-Y', $request->period_end)->endOfMonth();
        $this->validate($request, [
            'period_start' => ['required', 'string',
                function ($attribute, $value, $fail) use ($request, $start, $end) {
                    if ($start->greaterThan($end)) {
                        notify(new ToastNotification('Oops!', 'Start period should be less than the end period', 'error'));
                        $fail($attribute . ' ');
                    }
                },],
            'period_end' => ['required', 'string']
        ]);

        $update_id =  Crypt::decrypt($request->id);

        $record = ReportingPeriod::where('period_start', $start->format('Y-m-d'))
            ->where('period_end', $end->format('Y-m-d'))->first();
        if (empty($record)) {
            $updatePeriod = ReportingPeriod::find($update_id);
            $updated = $updatePeriod->update([
                'period_start' => $start->format('Y-m-d'),
                'period_end' => $end->format('Y-m-d'),
                'active_period' => true,
            ]);
            if ($updated) {
                notify(new ToastNotification('Successful', 'Reporting Period updated.', 'success'));
                return back();
            }
            notify(new ToastNotification('Error', 'Please try again.', 'error'));
            return back()->withInput();
        }
        notify(new ToastNotification('Error', 'This reporting period already exists', 'error'));
        return back()->withInput();


}





}

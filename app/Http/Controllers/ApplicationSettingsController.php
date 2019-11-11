<?php

namespace App\Http\Controllers;

use App\Classes\ToastNotification;
use App\ReportingPeriod;
use App\SystemOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ApplicationSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $apps = SystemOption::all();

        $periods = ReportingPeriod::all();
        return view('settings.app.settings', compact('apps','periods'));
    }

    public function setName(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string|min:3'
        ]);
        $option_name = 'app_name';
        $name = SystemOption::updateOrCreate(
            [
                'option_name' => $option_name
            ],[
                'option_value' => $option_name,
                'slug' => str_slug($request->name),
                'display_name' => strtoupper($request->name),
            ]
        );
        if ($name){
            notify(new ToastNotification('Successful', 'Application name updated.', 'success'));
        }
        else{
            notify(new ToastNotification('Error', 'Please try again.', 'error'));
        }
        return back();
    }

    public function setEmail(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|email'
        ]);
        $option_name = 'app_email';
        $name = SystemOption::updateOrCreate(
            [
                'option_name' => $option_name
            ],[
                'option_value' => $option_name,
//                'slug' => str_slug($request->name),
                'display_name' => strtoupper($request->email),
            ]
        );
        if ($name){
            notify(new ToastNotification('Successful', 'Application email updated.', 'success'));
        }
        else{
            notify(new ToastNotification('Error', 'Please try again.', 'error'));
        }
        return back();
    }

    public function generation_status(Request $request)
    {
        $this->validate($request,[
            'status' => 'required|numeric'
        ]);
        $option_name = 'generation_status';
        if ($request->status == 1){
            $display_name = 'Submitted';
        }
        else{
            $display_name = 'Report Verified';
        }
        $name = SystemOption::updateOrCreate(
            [
                'option_name' => $option_name
            ],[
                'option_value' => $request->status,
                'display_name' => strtoupper($display_name),
            ]
        );
//        dd($request->all());
        if ($name){
            notify(new ToastNotification('Successful', 'Report Generation Status updated.', 'success'));
        }
        else{
            notify(new ToastNotification('Error', 'Please try again.', 'error'));
        }
        return back();
    }

    public function changeDeadlineStatus(Request $request)
    {
        $this->validate($request,[
            'id' => 'required|numeric|min:0|max:1'
        ]);
        if ($request->id == 1){
            $key = 0;
            $worked = SystemOption::where('option_name','=', 'app_deadline')->update(['status'=>$key]);

            if ($worked){
                $message = 'Report Submission closed.';
                $type = 'warning';
            }
            else{
                $message = 'Something went wrong!.';
                $type = 'danger';
            }
        }
        else{
            $key = 1;
            $worked = SystemOption::where('option_name','=', 'app_deadline')->update(['status'=>$key]);

            if ($worked){
                $message = 'Report Submission opened.';
                $type = 'success';
            }
            else{
                $message = 'Something went wrong!';
                $type = 'danger';
            }
        }
        return response()->json(['key'=>$key,'message'=>$message,'type'=>$type]);
    }

    public function setDeadline(Request $request)
    {
        $this->validate($request,[
            'deadline' => 'required|date'
        ]);
        $option_name = 'app_deadline';
        $name = SystemOption::updateOrCreate(
            [
                'option_name' => $option_name
            ],[
                'option_value' => $option_name,
//                'slug' => str_slug($request->name),
                'display_name' => $request->deadline,
            ]
        );
        if ($name){
            notify(new ToastNotification('Successful', 'Report submission deadline updated.', 'success'));
        }
        else{
            notify(new ToastNotification('Error', 'Please try again.', 'error'));
        }
        return back();
    }

    public function saveReportingPeriod(Request $request){
        $this->validate($request,[
            'period_start' => 'required|string',
            'period_end' => 'required|string'
        ]);


        $new_period=new ReportingPeriod();
        $new_period->period_start = $request->period_start;
        $new_period->period_end = $request->period_end;
         $saved=$new_period->save();
        if ($saved){
            notify(new ToastNotification('Successful', 'Reporting Period Added.', 'success'));
        }
        else{
            notify(new ToastNotification('Error', 'Please try again.', 'error'));
        }
        return back();

    }

    public function deleteReportingPeriod($id){
        $id = Crypt::decrypt($id);
        ReportingPeriod::destroy($id);

        notify(new ToastNotification('Successful!', 'Reporting Period Deleted!', 'success'));
        return back();
    }
    public function editReportingPeriod($id){
        $id = Crypt::decrypt($id);
        $period = ReportingPeriod::find($id);
        $apps = SystemOption::all();
        $periods = ReportingPeriod::all();
        return view('settings.app.edit_reporting_period_view', compact('period', 'x','y','apps','periods'));
    }

    public function updateReportingPeriod(Request $request){
        $id = Crypt::decrypt($request->id);
        $this->validate($request,[
            'period_start' => 'required|string',
            'period_end' => 'required|string'
        ]);
        $updatePeriod = ReportingPeriod::find($id);

        $updatePeriod->period_start = $request->period_start;
        $updatePeriod->period_end = $request->period_end;
        $saved=$updatePeriod->save();
        if ($saved){
            notify(new ToastNotification('Successful', 'Reporting Period updated.', 'success'));
            return redirect('/settings/application');
        }
        else{
            notify(new ToastNotification('Error', 'Please try again.', 'error'));
            return back();
        }




    }



}

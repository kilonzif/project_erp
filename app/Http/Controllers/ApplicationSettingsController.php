<?php

namespace App\Http\Controllers;

use App\Classes\ToastNotification;
use App\SystemOption;
use Illuminate\Http\Request;

class ApplicationSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $apps = SystemOption::all();
        return view('settings.app.settings', compact('apps'));
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
}

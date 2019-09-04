<?php

namespace App\Http\Controllers;

use App\Institution;
use App\Report;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        if(session()->has('default_project')) {
            if (Auth::user()->ability('webmaster', 'admin-dashboard')){
                $reports = Report::get()->count();
                if (Auth::user()->hasRole('webmaster')){
                    $users = User::get()->count();
                }else{
                    $W_users = User::whereRoleIs('webmaster')->pluck('id');
                    $users = User::whereNotIn('id', $W_users)->get()->count();
                }
                $new_reports = Report::where('status', '=',1)->get()->count();
                $institutions = Institution::where('active', '=',1)->get()->count();
                return view('admin-home', compact('users','reports','new_reports','institutions'));
            }else{
                return redirect()->route('report_submission.reports');
//            }
//        } else {
//            return view('project-options');
        }
    }

    public function calendar()
    {
        if (Auth::user()->hasRole('webmaster|super-admin|admin|manager')){
            $reports = Report::where('status', '=',1)->get(['id','updated_at','ace_id','status']);
        }else{
            $reports = Report::where('status', '=',1)->where('user_id', '=',Auth::id())->get(['id','updated_at','ace_id','status']);
        }

        return view('calendar',compact('reports','calendar'));
    }
}

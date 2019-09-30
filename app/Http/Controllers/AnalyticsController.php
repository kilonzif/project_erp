<?php

namespace App\Http\Controllers;

use App\Project;
use App\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
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


    public function index()
    {
        $males = DB::connection('mongodb')->collection('indicator_3')->where('gender',"=","M")->orWhere('gender',"=","Male")->count();
        $females = DB::connection('mongodb')->collection('indicator_3')->where('gender',"=","F")->orWhere('gender',"=","Female")->count();

        return view('analytics.index');
    }


    public function getGenderDistribution(Request $request)
    {
        $males = DB::connection('mongodb')->collection('indicator_3')->where('gender',"=","M")->orWhere('gender',"=","Male")->count();
        $females = DB::connection('mongodb')->collection('indicator_3')->where('gender',"=","F")->orWhere('gender',"=","Female")->count();

        return response()->json(['male'=>$males,'female'=>$females]);
    }

    public function getCumulativePDO(Request $request)
    {
        $start_date = date("Y-m-d",strtotime($request->start_date));
        $end_date = date("Y-m-d",strtotime($request->end_date));
        $report_ids = Report::where("status", "<>", 99)
            ->where("start_date", ">=", $start_date)
            ->where("end_date", "<=", $end_date)
            ->pluck("id");

        $cumulative_report_ids = Report::where("status","<>",99)->pluck("id");

//        $regional = substr('Regional', 0, 1);

        $total_students = DB::connection('mongodb')->collection('indicator_3')
            ->whereIn('report_id',$report_ids)
            ->count();

        $cum_total_students = DB::connection('mongodb')->collection('indicator_3')
            ->whereIn('report_id',$cumulative_report_ids)
            ->count();
        $regional_students = DB::connection('mongodb')->collection('indicator_3')
            ->whereIn('report_id',$report_ids)
            ->where(function($query)
            {
                $query->where('regional-status','like', "R%")
                    ->orWhere('regional-status','like', "r%");
            })
            ->count();
        $cum_regional_students = DB::connection('mongodb')->collection('indicator_3')
            ->whereIn('report_id',$cumulative_report_ids)
            ->where(function($query)
            {
                $query->where('regional-status','like', "R%")
                    ->orWhere('regional-status','like', "r%");
            })
            ->count();

        $total_internships = DB::connection('mongodb')->collection('indicator_5.2')
            ->whereIn('report_id',$report_ids)
            ->count();
        $cum_total_internships = DB::connection('mongodb')->collection('indicator_5.2')
            ->whereIn('report_id',$cumulative_report_ids)
            ->count();

        $total_accreditation =  DB::connection('mongodb')->collection('indicator_7.3')
            ->whereIn('report_id',$report_ids)
            ->count();
        $cum_total_accreditation =  DB::connection('mongodb')->collection('indicator_7.3')
            ->whereIn('report_id',$cumulative_report_ids)
            ->count();

        $external_revenue = DB::connection('mongodb') ->collection('indicator_5.1')
            ->whereIn('report_id',$report_ids)
            ->count();
        $cum_external_revenue = DB::connection('mongodb') ->collection('indicator_5.1')
            ->whereIn('report_id',$cumulative_report_ids)
            ->count();
//        $total_students = DB::connection('mongodb')->collection('indicator_3')->where('gender',"=","M")->orWhere('gender',"=","Male")->count();
        $the_view = view('analytics.cumulative_pdo', compact('start_date','end_date', 'total_students','cum_total_students',
            'regional_students','cum_regional_students','total_internships','cum_total_internships','external_revenue','cum_external_revenue','total_accreditation','cum_total_accreditation'))->render();
        return response()->json(['the_view'=>$the_view,'$reports'=>$report_ids]);
    }
}

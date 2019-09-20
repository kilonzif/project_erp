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

        $total_students = DB::connection('mongodb')->collection('indicator_3')->whereIn('report_id',$report_ids)->count();
        $regional_students = DB::connection('mongodb')->collection('indicator_3')->where('regional-status','=', 'Regional')->orWhere('regional-status','=', 'regional')->count();
//        $total_students = DB::connection('mongodb')->collection('indicator_3')->where('gender',"=","M")->orWhere('gender',"=","Male")->count();
        $the_view = view('analytics.cumulative_pdo', compact('start_date','end_date', 'total_students', 'regional_students'))->render();
        return response()->json(['the_view'=>$the_view,'$reports'=>$report_ids]);
    }
}

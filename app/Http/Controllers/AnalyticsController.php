<?php

namespace App\Http\Controllers;

use App\Ace;
use App\AceIndicatorsTarget;
use App\Project;
use App\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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

        $aces = Ace::all();
        $all_ace_ids = Ace::where('id' ,'>' ,0)->pluck('id');
        $tv=AceIndicatorsTarget::get_target_value('2018-01-01','2019-10-02',$all_ace_ids,50);
//        dd($tv);
        $indicator_total_students = DB::table('indicators')
            ->where('title', 'Quantity of students with focus on gender and regionalization')->value('id');
//        dd($indicator_total_students);
        $target_values_total_students = intval(AceIndicatorsTarget::get_target_value('2018-01-01','2019-10-02',$all_ace_ids,3));
//dd(intval($target_values_total_students));
        return view('analytics.index',compact('aces','all_ace_ids'));
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


            $this_ace=$request->this_ace;

            $indicator_total_students = DB::table('indicators')
                ->select('id')
                ->where('title', 'Quantity of students with focus on gender and regionalization')
                ->value('id');
            $target_values_total_students = intval(AceIndicatorsTarget::get_target_value($start_date,$end_date,$this_ace,$indicator_total_students));


            $report_ids = Report::where("status", "<>", 99)
                ->where("start_date", ">=", $start_date)
                ->where("end_date", "<=", $end_date)
                ->pluck("id");

            $cumulative_report_ids = Report::where("status", "<>", 99)->pluck("id");

            $total_students = DB::connection('mongodb')->collection('indicator_3')
                ->whereIn('report_id', $report_ids)
                ->count();

            $cum_total_students = DB::connection('mongodb')->collection('indicator_3')
                ->whereIn('report_id', $cumulative_report_ids)
                ->count();
            $regional_students = DB::connection('mongodb')->collection('indicator_3')
                ->whereIn('report_id', $report_ids)
                ->where(function ($query) {
                    $query->where('regional-status', 'like', "R%")
                        ->orWhere('regional-status', 'like', "r%");
                })
                ->count();
            $cum_regional_students = DB::connection('mongodb')->collection('indicator_3')
                ->whereIn('report_id', $cumulative_report_ids)
                ->where(function ($query) {
                    $query->where('regional-status', 'like', "R%")
                        ->orWhere('regional-status', 'like', "r%");
                })
                ->count();

            $total_internships = DB::connection('mongodb')->collection('indicator_5.2')
                ->whereIn('report_id', $report_ids)
                ->count();
            $cum_total_internships = DB::connection('mongodb')->collection('indicator_5.2')
                ->whereIn('report_id', $cumulative_report_ids)
                ->count();

            $internship_indicator = DB::table('indicators')->where('identifier', '=', '5.2')
                ->value('id');
            $internship_target = intval(AceIndicatorsTarget::get_target_value($start_date,$end_date,$this_ace,$internship_indicator));

            $total_accreditation = DB::connection('mongodb')->collection('indicator_7.3')
                ->whereIn('report_id', $report_ids)
                ->count();
            $cum_total_accreditation = DB::connection('mongodb')->collection('indicator_7.3')
                ->whereIn('report_id', $cumulative_report_ids)
                ->count();

            $accreditation_indicator = DB::table('indicators')->where('identifier', '=', '7.3')
                ->value('id');
            $accreditation_target = intval(AceIndicatorsTarget::get_target_value($start_date,$end_date,$this_ace,$accreditation_indicator));
            $external_revenue = DB::connection('mongodb')->collection('indicator_5.1')
                ->whereIn('report_id', $report_ids)
                ->count();
            $cum_external_revenue = DB::connection('mongodb')->collection('indicator_5.1')
                ->whereIn('report_id', $cumulative_report_ids)
                ->count();

            $external_revenue_indicator = DB::table('indicators')->where('identifier', '=', '5.1')
                ->value('id');
            $external_revenue_target = intval(AceIndicatorsTarget::get_target_value($start_date,$end_date,$this_ace,$external_revenue_indicator));



//        $total_students = DB::connection('mongodb')->collection('indicator_3')->where('gender',"=","M")->orWhere('gender',"=","Male")->count();
        $the_view = view('analytics.cumulative_pdo', compact('start_date','end_date', 'total_students','cum_total_students',
            'regional_students','cum_regional_students','total_internships','cum_total_internships','external_revenue','cum_external_revenue',
            'total_accreditation','cum_total_accreditation',
            'target_values_total_students','internship_target','accreditation_target','external_revenue_target'))->render();
        return response()->json(['the_view'=>$the_view,'$reports'=>$report_ids]);
    }
}

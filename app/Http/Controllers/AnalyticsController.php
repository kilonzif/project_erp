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
        $t_value = AceIndicatorsTarget::get_target_value($start_date,$end_date,$this_ace,$indicator_total_students);
        $target_values_total_students = (integer)$t_value;
        $report_ids = Report::where("status", "<>", 99)
            ->where("start_date", ">=", $start_date)
            ->where("end_date", "<=", $end_date)
            ->pluck("id");

        $total_students = DB::connection('mongodb')->collection('indicator_3')
            ->whereIn('report_id', $report_ids)
            ->count();

        $cum_total_students = DB::connection('mongodb')->collection('indicator_3')
            ->count();
        $regional_students = DB::connection('mongodb')->collection('indicator_3')
            ->whereIn('report_id', $report_ids)
            ->where(function ($query) {
                $query->where('regional-status', 'like', "R%")
                    ->orWhere('regional-status', 'like', "r%");
            })
            ->count();
        $cum_regional_students = DB::connection('mongodb')->collection('indicator_3')
            ->where(function ($query) {
                $query->where('regional-status', 'like', "R%")
                    ->orWhere('regional-status', 'like', "r%");
            })
            ->count();


        $regional_students_indicator_id= DB::table('indicators')->where('identifier', '=', '3.5')
            ->value('id');
        $rs_tvalue=AceIndicatorsTarget::get_target_value($start_date,$end_date,$this_ace,$regional_students_indicator_id);

        $regional_students_target =(integer)$rs_tvalue;

        $total_internships = DB::connection('mongodb')->collection('indicator_5.2')
            ->whereIn('report_id', $report_ids)
            ->count();
        $cum_total_internships = DB::connection('mongodb')->collection('indicator_5.2')
            ->count();

        $internship_indicator = DB::table('indicators')->where('identifier', '=', '5.2')
            ->value('id');
        $intern_tv = AceIndicatorsTarget::get_target_value($start_date,$end_date,[13],$internship_indicator);
        $internship_target =(integer)$intern_tv;
        $total_accreditation = DB::connection('mongodb')->collection('indicator_7.3')
            ->whereIn('report_id', $report_ids)
            ->count();
        $cum_total_accreditation = DB::connection('mongodb')->collection('indicator_7.3')
            ->count();

        $accreditation_indicator = DB::table('indicators')->where('identifier', '=', '7.3')
            ->value('id');
        $accreditation_tv = AceIndicatorsTarget::get_target_value($start_date,$end_date,$this_ace,$accreditation_indicator);
        $accreditation_target = (integer)$accreditation_tv;
        $external_revenue = DB::connection('mongodb')->collection('indicator_5.1')
            ->whereIn('report_id', $report_ids)
            ->count();
        $cum_external_revenue = DB::connection('mongodb')->collection('indicator_5.1')
            ->count();

        $external_revenue_indicator = DB::table('indicators')->where('identifier', '=', '5.1')
            ->value('id');
        $er_tv = AceIndicatorsTarget::get_target_value($start_date,$end_date,$this_ace,$external_revenue_indicator);
        $external_revenue_target = (integer)$er_tv;

        $the_view = view('analytics.cumulative_pdo', compact('this_ace','start_date','end_date', 'total_students','cum_total_students',
            'regional_students','cum_regional_students','total_internships','cum_total_internships','external_revenue','cum_external_revenue',
            'total_accreditation','cum_total_accreditation',
            'target_values_total_students','internship_target','accreditation_target','external_revenue_target','regional_students_target'))->render();
        return response()->json(['the_view'=>$the_view,'$reports'=>$report_ids]);
    }


    public function calculateAggregate(Request $request){

        $start_year = date("Y-m-d",strtotime($request->start_year));
        $end_year= date("Y-m-d",strtotime($request->end_year));
        $selected_ace = $request->selected_ace;
        $years = array();
        $start = date('Y',strtotime($request->start_year));
        $end = date('Y',strtotime($request->end_year));
        while($start <= $end){
            $years[] = (int)$start;
            $start++;
        }
        $topic_name = $request->topic_name;
        $publication_year =[];
        $research_publication=[];
        $actual_external_revenue=[];
        $target_external_revenue=[];
        $international_accreditation=[];
        $national_accreditation=[];
        $national_students = []; $regional_students = []; $total_students = [];
        $target_students=[];

        foreach ($years as $key=>$this_year) {
            $regional_students[$key] = DB::connection('mongodb')->collection('indicator_3')
                ->where('calender-year-of-enrollment', $this_year)
                ->where(function ($query) {
                    $query->where('regional-status', 'like', "R%")
                        ->orWhere('regional-status', 'like', "r%");
                })
                ->count();

            $national_students[$key]  = DB::connection('mongodb')->collection('indicator_3')
                ->where('calender-year-of-enrollment', $this_year)
                ->where(function ($query) {
                    $query->where('regional-status', 'like', "N%")
                        ->orWhere('regional-status', 'like', "n%");
                })
                ->count();

            $total_students[$key] = $national_students[$key] + $regional_students[$key];

            $indicator_total_students = DB::table('indicators')
                ->select('id')
                ->where('title', 'Quantity of students with focus on gender and regionalization')
                ->value('id');
            $t_value = AceIndicatorsTarget::get_target_by_year($start_year, $end_year, $selected_ace, 50);
            $target_students = (integer)$t_value;

               //AGGREGATE PROGRAMME ACCREDITATION

            $national_accreditation [$key]= DB::connection('mongodb')->collection('indicator_7.3')
                ->where(function ($query) {
                    $query->where('type-of-accreditation2', 'like', "n%")
                        ->orWhere('type-of-accreditation2', 'like', "N%");
                })
                ->where('date-of-accreditation-ddmmyyyy','like',"%$this_year")
                ->count();

            $international_accreditation [$key]= DB::connection('mongodb')->collection('indicator_7.3')
                ->where(function ($query) {
                    $query->where('type-of-accreditation2', 'like', "International%")
                        ->orWhere('type-of-accreditation2', 'like', "international%");
                })
                ->where('date-of-accreditation-ddmmyyyy','like',"%$this_year")
                ->count();



            $actual_external_revenue[] = DB::connection('mongodb')->collection('indicator_5.1')
                ->count();

            $external_revenue_indicator = DB::table('indicators')->where('identifier', '=', '5.1')
                ->value('id');
            $er_tv=AceIndicatorsTarget::get_target_value($start_year,$end_year,$selected_ace,$external_revenue_indicator);
            $target_external_revenue []= (integer)$er_tv;


//            course
            $phd_students [] = DB::connection('mongodb')->collection('indicator_3')
                ->where('calender-year-of-enrollment', $this_year)
                ->where(function ($query) {
                    $query->where('level', 'like', "phD%")
                        ->orWhere('level', 'like', "PHD%");
                })
                ->count();
            $masters_students [] = DB::connection('mongodb')->collection('indicator_3')
                ->where('calender-year-of-enrollment', $this_year)
                ->where(function ($query) {
                    $query->where('level', 'like', "M%")
                        ->orWhere('level', 'like', "m%");
                })
                ->count();
            $prof_students [] = DB::connection('mongodb')->collection('indicator_3')
                ->where('calender-year-of-enrollment', $this_year)
                ->where(function ($query) {
                    $query->where('level', 'like', "prof%")
                        ->orWhere('level', 'like', "Pro%");
                })
                ->count();




        }

        $research_publication= DB::connection('mongodb')->collection('indicator_4.2')
            ->select('publication-name')
            ->groupBy('publication-year');
//            ->count();

        foreach ($research_publication as $i){
            $publication_year[]=DB::connection('mongodb')->collection('indicator_4.2')
                ->select('publication-year');
        }

        return response() ->json(['publication_year'=>$publication_year,'research_publication'=>$research_publication,
            'years'=>$years,'actual_external_revenue'=>$actual_external_revenue,'target_external_revenue'=>$target_external_revenue,
            'international_accreditation'=>$international_accreditation,'national_accreditation'=>$national_accreditation,
            'total_students'=>$total_students,'regional_students'=>$regional_students,
            'national_students'=>$national_students,'target_students'=>$target_students,
            'phd_students'=>$phd_students,'masters_students'=>$masters_students,'prof_students'=>$prof_students
        ]);
    }
}

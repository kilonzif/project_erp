<?php

namespace App\Http\Controllers;

use App\Ace;
use App\AceIndicatorsTarget;
use App\Project;
use App\Report;
use App\ReportingPeriod;
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
        $countries = DB::table('aces')->join('institutions', 'aces.institution_id', '=', 'institutions.id')
            ->join('countries', 'institutions.country_id', '=', 'countries.id')
            ->distinct('countries.id')
            ->select('countries.*')
            ->get();

        $fields = ['Agriculture', 'Health', 'STEM','Education','Applied Soc. Sc.'];
        $type_of_centres = ['Colleges of Engineering','Emerging Centre','ACE'];
        $periods = ReportingPeriod::all();

        return view('analytics.index',compact('aces','all_ace_ids', 'periods','countries','fields','type_of_centres'));
    }








    public function getGenderDistribution(Request $request)
    {
        $filters_selected = array();

        $filters_selected[] = [ ];
        $reporting_periods = $request->selected_period;

//        dd($reporting_periods);
        $selected_aces = $selected_fields = $selected_countries = $selected_typeofcentre =[];
        $reports = DB::table('reports')
            ->join('reporting_period', 'reports.reporting_period_id', '=', 'reporting_period.id')
            ->join('aces', 'reports.ace_id', '=', 'aces.id')
            ->join('institutions', 'aces.institution_id', '=', 'institutions.id')
            ->join('countries', 'institutions.country_id', '=', 'countries.id')
            ->select(DB::raw('reports.*,aces.id as aceID, aces.*,countries.id as countryID, countries.*,reports.id'))
            ->whereIn('reports.reporting_period_id', $reporting_periods)
            ->get();

        foreach ($request->filter_by as $filter) {
            //Filter by field
            if ($filter == "Field of Study") {
                if (isset($request->field) && sizeof($request->field) > 0) {
                    $selected_fields = $request->field;
                    $reports = $reports->whereIn('aces.field', $selected_fields);

                }
            }
            //Filter by Country
            if ($filter == "Countries") {
                if (isset($request->country) && sizeof($request->country) > 0) {
                    $selected_countries = $request->country;
                    $reports = $reports->whereIn('countryID', $selected_countries);
                }
            }
            //Filter by Type of Centre
            if ($filter == "Type of Centre") {
                if (isset($request->typeofcentre) && sizeof($request->typeofcentre) > 0) {
                    $selected_typeofcentre = $request->typeofcentre;
                    $reports = $reports->whereIn('aces.ace_type', $selected_typeofcentre);
                }
            }
            //Filter by Ace
            if ($filter == "ACE") {
                if (isset($request->selected_ace) && sizeof($request->selected_ace) > 0) {
                    $selected_aces = $request->selected_ace;
                    $reports = $reports->whereIn('ace_id', $selected_aces);
                }
            }
        }

        $report_ids = $reports->pluck('id')->toArray();


        $resultset = new \stdClass();
        $aces = Ace::all();
        $all_ace_ids = Ace::where('id' ,'>' ,0)->pluck('id');
        $countries = DB::table('aces')->join('institutions', 'aces.institution_id', '=', 'institutions.id')
            ->join('countries', 'institutions.country_id', '=', 'countries.id')
            ->distinct('countries.id')
            ->select('countries.*')
            ->get();

        $fields = ['Agriculture', 'Health', 'STEM','Education','Applied Soc. Sc.'];
        $type_of_centres = ['Colleges of Engineering','Emerging Centre','ACE'];
        $periods = ReportingPeriod::all();

        $aggregate_topic = $request->topic_name;

        if ($aggregate_topic == "Gender Distribution") {
            $query = DB::connection('mongodb')->collection('indicator_3');

            $males = $query->where('gender',"=","M")
                ->orWhere('gender',"=","Male")
                ->whereIn('report_id',$report_ids)
                ->count();
            $females = $query->where('gender',"=","F")
                ->orWhere('gender',"=","Female")
                ->whereIn('report_id',$report_ids)
                ->count();

            $resultset->males = $males;
            $resultset->females = $females;
        }elseif ($aggregate_topic == "Aggregate Student"){
            $query = DB::connection('mongodb')->collection('indicator_3');
            $regional_students = $query
//                ->whereIn('report_id', $reports)
//                ->where('calender-year-of-enrollment', $this_year)
                ->where(function ($query) {
                    $query->where('regional-status', 'like', "R%")
                        ->orWhere('regional-status', 'like', "r%");
                })
                ->whereIn('report_id', $report_ids)
                ->count();


            $national_students  = $query
//                ->whereIn('report_id', $reports)
//                ->where('calender-year-of-enrollment', $this_year)
                ->where(function ($query) {
                    $query->where('regional-status', 'like', "N%")
                        ->orWhere('regional-status', 'like', "n%");
                })
                ->whereIn('report_id', $reports)
                ->count();

            $total_students = $regional_students + $national_students;

            $resultset->national_students = $national_students;
            $resultset->regional_students = $regional_students;
            $resultset->total_students = $total_students;


        }else if($aggregate_topic == "Student Enrollment"){
            $query = DB::connection('mongodb')->collection('indicator_3');
            $phd_students =$query->where(function ($query) {
                $query->where('level', 'like', "phD%")
                    ->orWhere('level', 'like', "PHD%");
                 })
                ->whereIn('report_id', $report_ids)
                ->count();
            $masters_students = $query->where(function ($query) {
                $query->where('level', 'like', "M%")
                ->orWhere('level', 'like', "m%");
                 })
                ->whereIn('report_id', $report_ids)
                ->count();
            $prof_students =$query ->where(function ($query) {
                    $query->where('level', 'like', "prof%")
                        ->orWhere('level', 'like', "Pro%");
                })
                ->whereIn('report_id', $report_ids)
                ->count();

            $total_enrolled=$masters_students +$prof_students +$phd_students;

            $resultset->masters_students = $masters_students;
            $resultset->prof_students = $prof_students;
            $resultset->phd_students = $phd_students;
            $resultset->total_enrolled = $total_enrolled;

        }else if($aggregate_topic == "list of donors"){
            $query = DB::connection('mongodb')->collection('indicator_5.1');
            $individual_donor =$query->where(function ($query) {
                $query->DISTINCT('source')
                ->SELECT('source');
                })
                ->whereIn('report_id', $report_ids)
                ->pluck('source')->toArray();
            $donation_amount =DB::connection('mongodb')->collection('indicator_5.1')
                ->whereIn('report_id', $report_ids)
                ->pluck('amount-usd')->toArray();


            $resultset->Source = $individual_donor;
            $resultset->Amount = $donation_amount;

        }else if($aggregate_topic == "Aggregate Internships"){

            $student_internship = DB::connection('mongodb')->collection('indicator_5.2')
                ->whereIn('report_id', $report_ids)
                ->where(function ($query) {
                    $query->where('studentfaculty', 'like', "Student%")
                        ->orWhere('studentfaculty', 'like', "stud%");
                })
                ->count();
            $faculty_internship = DB::connection('mongodb')->collection('indicator_5.2')
                ->whereIn('report_id', $report_ids)
                ->where(function ($query) {
                    $query->where('studentfaculty', 'like', "F%")
                        ->orWhere('studentfaculty', 'like', "f%");
                })
                ->count();

            $resultset->Student_Interns =$student_internship;
            $resultset->Faculty_Interns =$faculty_internship;
        }else if($aggregate_topic == "Aggregate Programme Accreditation"){
            //AGGREGATE PROGRAMME ACCREDITATION
            $national_accreditation = DB::connection('mongodb')->collection('indicator_7.3')
                ->where(function ($query) {
                    $query->where('type-of-accreditation2', 'like', "n%")
                        ->orWhere('type-of-accreditation2', 'like', "N%");
                })
                ->whereIn('report_id', $report_ids)
                ->count();

            $international_accreditation= DB::connection('mongodb')->collection('indicator_7.3')
                ->where(function ($query) {
                    $query->where('type-of-accreditation2', 'like', "International%")
                        ->orWhere('type-of-accreditation2', 'like', "international%");
                })
                ->whereIn('report_id', $report_ids)
                ->count();
            $gap_assessment_accreditation= DB::connection('mongodb')->collection('indicator_7.3')
                ->where(function ($query) {
                    $query->where('type-of-accreditation2', 'like', "Gap%")
                        ->orWhere('type-of-accreditation2', 'like', "Self Assessment%")
                        ->orWhere('type-of-accreditation2', 'like', "gap%");
                })
                ->whereIn('report_id', $report_ids)
                ->count();

            $resultset->National_Accreditation = $national_accreditation;
            $resultset->International_Accreditation = $international_accreditation;
            $resultset->Gap_Assessment_Accreditation = $gap_assessment_accreditation;

        }


        return view('analytics.index',compact('resultset','request','aces','all_ace_ids', 'periods','countries','fields','type_of_centres'));
    }

    public function add_filter(){
        $filtercount = rand ( 10000 , 99999 );
        $aces = Ace::all();
        $all_ace_ids = Ace::where('id' ,'>' ,0)->pluck('id');
        $countries = DB::table('aces')->join('institutions', 'aces.institution_id', '=', 'institutions.id')
            ->join('countries', 'institutions.country_id', '=', 'countries.id')
            ->distinct('countries.id')
            ->select('countries.*')
            ->get();

        $fields = ['Agriculture', 'Health', 'STEM','Education','Applied Soc. Sc.'];
        $type_of_centres = ['Colleges of Engineering','Emerging Centre','ACE'];
        $periods = ReportingPeriod::all();

        return view('analytics.add_filter',compact('filtercount','aces','all_ace_ids', 'periods','countries','fields','type_of_centres'));
    }




    public function getCumulativePDO(Request $request)
    {
        $this->validate($request, [
            'this_ace' => 'required|array',
            'this_period' => 'required|array'
        ]);
        $this_ace=$request->this_ace;
        $years_between = array();
        foreach ($request->this_period as $sp){
            $period = ReportingPeriod::query()->where('id',$sp)->first();
            $start_year = date('Y',strtotime($period->period_start));
            $end_year =date('Y',strtotime($period->period_end));
            while($start_year <= $end_year){
                $years_between[] = (int)$start_year;
                $start_year++;
            }
            $start_period  []= $period->period_start;

            $end_period[] = $period->period_end;
        }

        $report_ids[]= Report::getReports($start_period,$end_period);

        $data = AceIndicatorsTarget::getIndicatorID();

        $indicator_total_students = $data['total_students_ID'];
        $regional_students_indicator_id = $data['regional_students_ID'];
        $internship_indicator = $data['internships_ID'];
        $external_revenue_indicator = $data["revenue_ID"];
        $accreditation_indicator = $data['accreditation_ID'];

        $target_values_total_students = AceIndicatorsTarget::get_target_value($years_between,$this_ace,$indicator_total_students);

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
        $regional_students_target=AceIndicatorsTarget::get_target_value($years_between,$this_ace,$regional_students_indicator_id);
        $total_internships = DB::connection('mongodb')->collection('indicator_5.2')
            ->whereIn('report_id', $report_ids)
            ->count();
        $cum_total_internships = DB::connection('mongodb')->collection('indicator_5.2')
            ->count();

        $internship_target = AceIndicatorsTarget::get_target_value($years_between,$this_ace,$internship_indicator);
        $total_accreditation = DB::connection('mongodb')->collection('indicator_7.3')
            ->whereIn('report_id', $report_ids)
            ->count();
        $cum_total_accreditation = DB::connection('mongodb')->collection('indicator_7.3')
            ->count();

        $accreditation_target = AceIndicatorsTarget::get_target_value($years_between,$this_ace,$accreditation_indicator);
        $external_revenue = DB::connection('mongodb')->collection('indicator_5.1')
            ->whereIn('report_id', $report_ids)
            ->count();
        $cum_external_revenue = DB::connection('mongodb')->collection('indicator_5.1')
            ->count();

        $external_revenue_target = AceIndicatorsTarget::get_target_value($years_between,$this_ace,$external_revenue_indicator);
        $the_view = view('analytics.cumulative_pdo', compact('this_ace','years_between','start_period','end_period', 'total_students','cum_total_students',
            'regional_students','cum_regional_students','total_internships','cum_total_internships','external_revenue','cum_external_revenue',
            'total_accreditation','cum_total_accreditation',
            'target_values_total_students','internship_target','accreditation_target','external_revenue_target','regional_students_target'))->render();
        return response()->json(['the_view'=>$the_view,'$reports'=>$report_ids]);
    }


    public function calculateAggregate(Request $request){
        $this->validate($request, [
            'selected_period' => 'required|array',
            'selected_ace' => 'required|array',
            'topic_name' => 'required|string'
        ]);
        foreach ($request->selected_period as $sp){
            $period = ReportingPeriod::query()->where('id',$sp)->first();
            $start_year = date('Y',strtotime($period->period_start));
            $end_year =date('Y',strtotime($period->period_end));
            while($start_year <= $end_year){
                $years[] = (int)$start_year;
                $start_year++;
            }
            $start_period  []= $period->period_start;

            $end_period[] = $period->period_end;
        }
        $filter = $request->filter;


        $topic_name = $request->topic_name;
        $publication_year =[];
        $research_publication=[];
        $actual_external_revenue=[];
        $target_external_revenue=[];
        $international_accreditation=[];
        $national_accreditation =[];
        $national_students = []; $regional_students = []; $total_students = [];
        $target_students = [];
        $total_enrolled = [];
        $student_internship = [];
        $faculty_internship = [];

        $reports= Report::getReports($start_period,$end_period);

        $data = AceIndicatorsTarget::getIndicatorID();

        $indicator_total_students = $data['total_students_ID'];
        $regional_students_indicator_id = $data['regional_students_ID'];
        $internship_indicator = $data['internships_ID'];
        $external_revenue_indicator = $data["revenue_ID"];
        $accreditation_indicator = $data['accreditation_ID'];

        //Filter by field
        if ($request->filter == "Field of Study") {
            if (isset($request->field)) {
                if (sizeof($request->field) > 0) {


                    $reports = $reports->whereIn('field', $request->field);
                }
            }
        }
        //Filter by Country
        if ($request->filter == "Countries") {
            if (isset($request->country)) {
                if (sizeof($request->country) > 0) {
                    $reports = $reports->whereIn('countryID', $request->country);
                }
            }
        }
        //Filter by Type of Centre
        if ($request->filter == "Type of Centre") {
            if (isset($request->typeofcentre)) {
                if (sizeof($request->typeofcentre) > 0) {
                    $reports = $reports->whereIn('centre_type', $request->typeofcentre);
                }
            }
        }


        foreach ($years as $key=>$this_year) {
            $regional_students[$key] = DB::connection('mongodb')->collection('indicator_3')
                ->whereIn('report_id', $reports)
                ->where('calender-year-of-enrollment', $this_year)
                ->where(function ($query) {
                    $query->where('regional-status', 'like', "R%")
                        ->orWhere('regional-status', 'like', "r%");
                })
                ->count();

            $national_students[$key]  = DB::connection('mongodb')->collection('indicator_3')
                ->whereIn('report_id', $reports)
                ->where('calender-year-of-enrollment', $this_year)
                ->where(function ($query) {
                    $query->where('regional-status', 'like', "N%")
                        ->orWhere('regional-status', 'like', "n%");
                })
                ->count();

            $total_students[$key] = $national_students[$key] + $regional_students[$key];


            $t_value = AceIndicatorsTarget::get_target_by_year($this_year,$indicator_total_students);
            $target_students [$key]= (integer)$t_value;



            //AGGREGATE PROGRAMME ACCREDITATION
            $national_accreditation [$key]= DB::connection('mongodb')->collection('indicator_7.3')
                ->whereIn('report_id', $reports)
                ->where(function ($query) {
                    $query->where('type-of-accreditation2', 'like', "n%")
                        ->orWhere('type-of-accreditation2', 'like', "N%");
                })
                ->where('date-of-accreditation-ddmmyyyy','like',"%$this_year")
                ->count();

            $international_accreditation [$key]= DB::connection('mongodb')->collection('indicator_7.3')
                ->whereIn('report_id', $reports)
                ->where(function ($query) {
                    $query->where('type-of-accreditation2', 'like', "International%")
                        ->orWhere('type-of-accreditation2', 'like', "international%");
                })
                ->where('date-of-accreditation-ddmmyyyy','like',"%$this_year")
                ->count();

//            external revenue

            $actual_external_revenue[$key] = DB::connection('mongodb')->collection('indicator_5.1')
                ->whereIn('report_id', $reports)
                ->where('date-of-receipt','like','%'.$this_year.'%')
                ->pluck('amount-usd')->toArray();

            $er_tv=AceIndicatorsTarget::get_target_by_year($this_year,$external_revenue_indicator);
            $target_external_revenue [$key]= (integer)$er_tv;

            $phd_students [$key] = DB::connection('mongodb')->collection('indicator_3')
                ->whereIn('report_id', $reports)
                ->where('calender-year-of-enrollment', $this_year)
                ->where(function ($query) {
                    $query->where('level', 'like', "phD%")
                        ->orWhere('level', 'like', "PHD%");
                })
                ->count();
            $masters_students [$key] = DB::connection('mongodb')->collection('indicator_3')
                ->whereIn('report_id', $reports)
                ->where('calender-year-of-enrollment', $this_year)
                ->where(function ($query) {
                    $query->where('level', 'like', "M%")
                        ->orWhere('level', 'like', "m%");
                })
                ->count();
            $prof_students [$key] = DB::connection('mongodb')->collection('indicator_3')
                ->whereIn('report_id', $reports)
                ->where('calender-year-of-enrollment', $this_year)
                ->where(function ($query) {
                    $query->where('level', 'like', "prof%")
                        ->orWhere('level', 'like', "Pro%");
                })
                ->count();

            $total_enrolled[$key]=$masters_students [$key]+$prof_students [$key]+$phd_students [$key] ;

            //Internship

            $student_internship[$key] = DB::connection('mongodb')->collection('indicator_5.2')
                ->whereIn('report_id', $reports)
                ->where('start-date-ddmmyyyy','=',$this_year)
                ->where(function ($query) {
                    $query->where('studentfaculty', 'like', "Student%")
                        ->orWhere('studentfaculty', 'like', "stud%");
                })
                ->count();
            $faculty_internship[$key] = DB::connection('mongodb')->collection('indicator_5.2')
                ->whereIn('report_id', $reports)
                ->where(function ($query) {
                    $query->where('studentfaculty', 'like', "F%")
                        ->orWhere('studentfaculty', 'like', "f%");
                })
                ->where('start-date-ddmmyyyy','=',$this_year)
                ->count();


        }

        return response() ->json(['years'=>$years,'actual_external_revenue'=>$actual_external_revenue,'target_external_revenue'=>$target_external_revenue,
            'international_accreditation'=>$international_accreditation,'national_accreditation'=>$national_accreditation,
            'total_students'=>$total_students,'regional_students'=>$regional_students,
            'national_students'=>$national_students,'target_students'=>$target_students,'total_enrolled'=>$total_enrolled,
            'phd_students'=>$phd_students,'masters_students'=>$masters_students,'prof_students'=>$prof_students,
            'student_internship'=>$student_internship,'faculty_internship'=>$faculty_internship
        ]);
    }
}

<?php

namespace App\Http\Controllers;
use App\Ace;
use App\AceCourse;
use App\AceDlrIndicator;
use App\AceDlrIndicatorCost;
use App\AceDlrIndicatorValue;
use App\Contacts;
use App\AceIndicatorsBaseline;
use App\AceIndicatorsTarget;
use App\AceIndicatorsTargetYear;
use App\Classes\CommonFunctions;
use App\Classes\ToastNotification;
use App\Course;
use App\Currency;
use App\Indicator;
use App\IndicatorOne;
use App\Institution;
use App\MilestonesDlrs;
use App\MilestonesDlrsTarget;
use App\Position;
use App\Project;
use App\SectoralBoard;
use App\WorkPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use vendor\project\StatusTest;
//use Illuminate\Support\Facades\Storage;
//use Illuminate\Support\Facades\File;
use File;



class AcesController extends Controller {
    //
    //
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $aces = Ace::orderBy('name', 'ASC')->get();
        $currency = Currency::orderBy('name', 'ASC')->get();
        $universities = Institution::where('university', '=', 1)->orderBy('name', 'ASC')->get();
        return view('aces.index', compact('aces', 'universities','currency'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request)
    {


        $this->validate($request, [
            'name' => 'required|string|min:3|unique:aces,name',
            'contact' => 'nullable|numeric|digits_between:10,17',
            'email' => 'required|string|email|min:3',
            'university' => 'required|integer|min:1',
            'field' => 'required|string',
            'active' => 'nullable|boolean',
            'grant1' => 'nullable|numeric',
            'currency1' =>'required|numeric',
            'grant2' => 'nullable|numeric',
            'currency2' =>'nullable|numeric',
            'acronym' => 'required|string|min:2',
            'ace_type' => 'required|string|min:2',
            'ace_state' => 'string|min:2',
            'impact_no' => 'required|numeric|min:1',
        ]);





        $addAce = new Ace();
        $addAce->name = $request->name;
        $addAce->acronym = $request->acronym;
        $addAce->field = $request->field;
        $addAce->contact = $request->contact;
        $addAce->currency1_id = $request->currency1;
        $addAce->currency2_id = $request->currency2;
        $addAce->grant1 = $request->grant1;
        $addAce->grant2 = $request->grant2;
        $addAce->email = $request->email;
        $addAce->institution_id = $request->university;
        $addAce->active = $request->active;
        $addAce->ace_type = $request->ace_type;
        $addAce->ace_state = $request->ace_state;
        $addAce->impact_no = $request->impact_no;
        $addAce->save();



        if ($addAce->save()) {
            $this_ace = Ace::find($addAce->id)->first();

            $currency1 =  Currency::where('id','=',$this_ace->currency1_id)->orderBy('name', 'ASC')->first();
            $currency2= Currency::where('id','=',$this_ace->currency2_id)->orderBy('name', 'ASC')->first();

            notify(new ToastNotification('Successful!', 'New ACE Added', 'success'));
            return back();
        } else {
            notify(new ToastNotification('Notice', 'Something might have happened. Please try again.', 'info'));
            return back();
        }
    }
    public function add_courses(Request $request,$id){
        $ace_id = Crypt::decrypt($id);
        $ace=Ace::find($ace_id);
        $ace->programmes.=";".$request->ace_programmes;
        $ace->save();
        if($ace->save()){
            notify(new ToastNotification('Successful!', 'Courses ACE Added', 'success'));
            return back();
        }else{
            notify(new ToastNotification('Notice', 'Something might have happened. Please try again.', 'info'));
            return back();
        }
    }
    public function delete_course($aceId,$course){
        $ace_id = Crypt::decrypt($aceId);
        $ace = Ace::find($ace_id);
        $all_programmes = explode(';',$ace->programmes);

        $key = array_search($course, $all_programmes);
        if (false !== $key) {
            unset($all_programmes[$key]);
        }
        $ace->programmes = implode(";",$all_programmes);
        $ace->save();
        if($ace->save()){
            notify(new ToastNotification('Successful!', 'Course Removed', 'success'));
            return back();
        }else{
            notify(new ToastNotification('Notice', 'Something might have happened. Please try again.', 'info'));
            return back();
        }


    }

    public function indicator_one($id)
    {
        $ace_id = Crypt::decrypt($id);

        $ace = Ace::find($ace_id);
        $all_aces = Ace::get();
        $getRequirements = Indicator::activeIndicator()->parentIndicator(1)->pluck('title');
        $indicatorOne = new CommonFunctions();
        $labels = $indicatorOne->getRequirementLabels(null);
        $indicator_ones =IndicatorOne::where('ace_id', '=', $ace_id)->get()->groupBy('requirement')->toArray();

        $sectoral_board = IndicatorOne::where('ace_id', '=', $ace_id)->where('requirement', '=', 'SECTORAL ADVISORY BOARD')->first();

        return view('aces.indicator-one', compact('ace', 'all_aces', 'indicator_ones','labels','sectoral_board'));

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public  function indicator_one_save(Request $request, $id)
    {
        $ace_id = Crypt::decrypt($id);
        $oldIndicator=IndicatorOne::find($ace_id);
        $requirement = $request->requirement;
        $submission_date = $request->submission_date;
        $file_one=$request->file_one;
        $file_two = $request -> file_two;
        $url = $request -> url;
        $comments = $request -> comments;
        $destinationPath = base_path() . '/public/indicator1/';
        $requirement = $request->requirement;
        $thefile_one = "";
        $thefile_two = "";

        foreach ($requirement as $key => $req) {
            $file1 = $request->file('file_one')[$key];
            $file2 = $request->file('file_two')[$key];
            if (isset($file1)) {
                $file1->move($destinationPath, $file1->getClientOriginalName());
                $thefile_one=$file_one[$key]->getClientOriginalName();
            }
            if (isset($file2)) {
                $file2->move($destinationPath, $file2->getClientOriginalName());
                $thefile_two = $file_two[$key]->getClientOriginalName();

            }
            $saveIndicatorOne = IndicatorOne::updateOrCreate(
                ['ace_id' => $ace_id,'requirement' => $request->requirement[$key]],
                ['submission_date' => $request->submission_date[$key],
                    'file_one' => $thefile_one,
                    'file_two' => $thefile_two,
                    'url' => $request->url[$key],
                    'comments' => $request->comments[$key]]
            );

        }

        if (isset($saveIndicatorOne)) {
            notify(new ToastNotification('Successful!', 'Indicator 1 Requirement Added', 'success'));
            return back();
        } else {
            notify(new ToastNotification('Notice', 'Something might have happened. Please try again.', 'info'));
            return back();
        }

    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save_sectoral_board(Request $request, $id)
    {

        $this->validate($request, [
            'ss_file_one' => 'required|file|mimes:pdf,docx',
            'ss_file_two' => 'required|file|mimes:xls,xlsx',
            'ss_submission_date' => 'required',
        ]);



        $ace_id = Crypt::decrypt($id);
        $oldIndicator=IndicatorOne::find($ace_id);
        $requirement = $request->ss_requirement;
        $submission_date = $request->ss_submission_date;
        $file_one=$request->ss_file_one;
        $file_two=$request->ss_file_two;
        $destinationPath = base_path() . '/public/indicator1/';
        $thefile_two = "";
        $thefile_one = "";

        $file2 = $request->file('ss_file_two');
        $file1 = $request->file('ss_file_one');

        $thefile_one = $file_one->getClientOriginalName();

        if (isset($file2)) {
            $extracted = $this->extractMembers($file2, $ace_id);
            if ($extracted) {
                $file2->move($destinationPath, $file2->getClientOriginalName());
                $thefile_two = $file_two->getClientOriginalName();
            }else{
                notify(new ToastNotification('Notice', 'An error occured extracting data- Please check the format and try again.', 'info'));
                return back();
            }
        }
        $saveIndicatorOne = IndicatorOne::updateOrCreate(
            ['ace_id' => $ace_id, 'requirement' => $request->ss_requirement],
            ['submission_date' => $request->ss_submission_date,
                'file_one' => $thefile_one,
                'file_two' => $thefile_two,]
        );

        if (isset($saveIndicatorOne)) {
            $file1->move($destinationPath, $file1->getClientOriginalName());

            notify(new ToastNotification('Successful!', 'Sectoral Board Requirement Added', 'success'));
            return back();
        } else {
            notify(new ToastNotification('Notice', 'Something might have happened. Please try again.', 'info'));
            return back();
        }
    }

    public function extractMembers($file,$ace_id){
        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range( 2, $row_limit );
            $column_range = range( 'D', $column_limit );
            $startcount = 1;
            foreach ( $row_range as $row ) {
                $data[] = [
                    'ace_id' => $ace_id,
                    'name' => $sheet->getCell( 'A' . $row )->getValue(),
                    'title' => $sheet->getCell( 'B' . $row )->getValue(),
                    'phone' => $sheet->getCell( 'C' . $row )->getValue(),
                    'email' => $sheet->getCell( 'D' . $row )->getValue(),
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                ];
                $startcount++;
            }
            // Unique data without duplicates
            $unique = array_unique($data, SORT_REGULAR);

            $batchthis = $this->insertOrUpdate('sectoral_board',$unique);

        } catch (Exception $e) {
            $error_code = $e->errorInfo[1];
            return false;
        }

        return true;
    }

    function insertOrUpdate($table,array $rows){
        $first = reset($rows);
        $columns = implode( ',',
            array_map( function( $value ) { return "$value"; } , array_keys($first) )
        );
        $values = implode( ',', array_map( function( $row ) {
                return '('.implode( ',',
                        array_map( function( $value ) { return '"'.str_replace('"', '""', $value).'"'; } , $row )
                    ).')';
            } , $rows )
        );
        $updates = implode( ',',
            array_map( function( $value ) { return "$value = VALUES($value)"; } , array_keys($first) )
        );
        $sql = "INSERT INTO {$table}({$columns}) VALUES {$values} ON DUPLICATE KEY UPDATE {$updates}";
        return \DB::statement( $sql );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function edit_view(Request $request) {

        $id = Crypt::decrypt($request->id);
        $ace = Ace::find($id);

        $currency = Currency::orderBy('name', 'ASC')->get();
        $universities = Institution::where('university', '=', 1)->orderBy('name', 'ASC')->get();
        $indicator_ones=IndicatorOne::where('ace_id', '=', $id)->get();
        $requirements=Indicator::activeIndicator()->parentIndicator(1)->pluck('title');
        $view = view('aces.edit-view', compact('ace', 'universities','currency','requirements','indicator_ones'))->render();

//		dd($view);

        return response()->json(['theView' => $view, 'ace' => $ace, 'indicator_ones'=>$indicator_ones]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update_ace(Request $request) {

        $id = $request->ace_id;

        $this->validate($request, [
            'name' => 'required|string|min:3:aces,name',
            'contact' => 'nullable|numeric|digits_between:10,17',
            'email' => 'required|string|email|min:3',
            'university' => 'required|integer|min:1',
            'field' => 'required|string',
            'active' => 'nullable|boolean',
            'grant1' => 'nullable|numeric',
            'currency1' =>'nullable|numeric',
            'grant2' => 'nullable|numeric',
            'currency2' =>'nullable|numeric',
            'acronym' => 'required|string|min:2',
            'ace_type' => 'required|string|min:2',
            'ace_state' => 'string|min:2',
            'impact_no' => 'required|numeric|min:1',
        ]);

        $this_ace = Ace::find($id);
        $update_ace = $this_ace->Update([
            'name' => $request->name,
            'acronym' => $request->acronym,
            'field' => $request->field,
            'contact' => $request->contact,
            'currency1_id' => $request->currency1,
            'currency2_id' => $request->currency2,
            'grant1' => $request->grant1,
            'grant2' => $request->grant2,
            'email' => $request->email,
            'institution_id' => $request->university,
            'active' => $request->active,
            'ace_type' => $request->ace_type,
            'ace_state' => $request->ace_state,
            'impact_no' => $request->impact_no
        ]);


        if(!$update_ace){
            notify(new ToastNotification('error!', 'There was an error updating this ACE!', 'success'));
            return back()->withInput();
        }
        notify(new ToastNotification('Successful!', 'ACE Updated!', 'success'));
        return back();
    }

    public function ace_page($aceId) {
        $id = Crypt::decrypt($aceId);

        $ace = Ace::find($id);
        $dlr_unit_costs = AceDlrIndicatorCost::where('ace_id', '=', $id)->pluck('unit_cost','ace_dlr_indicator_id');
        $dlr_currency = AceDlrIndicatorCost::where('ace_id', '=', $id)
            ->where('currency_id', '!=', null)
            ->pluck('currency_id','ace_dlr_indicator_id')->toArray();
        $dlr_max_costs = AceDlrIndicatorCost::where('ace_id', '=', $id)->pluck('max_cost','ace_dlr_indicator_id');
        $ace_dlrs = AceDlrIndicator::where('is_parent', '=', 1)->orderBy('order', 'asc')->get();
        $project_start_year = config('app.reporting_year_start');
        $project_year_length = config('app.reporting_year_length');
        for ($a = 1; $a <= $project_year_length; $a++) {
            $years[$a] = $project_start_year+$a-1;
        }
        $ace_milestones_dlrs = AceDlrIndicator::where('status', '=', 1)
            ->where('is_milestone', '=', 1)
            ->orderBy('general_order', 'asc')
            ->pluck('original_indicator_id','id')->toArray();

        //[indicator_id,total_milestone]
        $milestone_dlrs = MilestonesDlrs::where('ace_id','=',$id)
            ->select(DB::raw('count(*) as total_milestone, indicator_id'))
            ->groupBy('indicator_id')
            ->pluck('total_milestone','indicator_id')
            ->toArray();

        $target_years = $ace->target_years;
        $aceemails= $this->getContactGroup($id);

        $workplans=WorkPlan::where('ace_id',$ace->id)->get();
        $roles = Position::whereNotIn('rank',[1,2,3,4])->get();

        $currency1 =  Currency::where('id','=',$ace->currency1_id)->orderBy('name', 'ASC')->first();
        $currency2= Currency::where('id','=',$ace->currency2_id)->orderBy('name', 'ASC')->first();

        $requirements=Indicator::activeIndicator()->parentIndicator(1)->pluck('title');

        return view('aces.profile', compact('ace','workplans','roles','currency1','currency2','dlr_unit_costs', 'target_years',
            'ace_dlrs', 'aceemails', 'dlr_max_costs','requirements','dlr_currency','years','ace_milestones_dlrs','milestone_dlrs'));
    }

    public function save_ace_dlr_indicator_values($aceId,$year) {
        $id = Crypt::decrypt($aceId);

        $ace = Ace::find($id);
        $ace_dlrs = AceDlrIndicator::where('status', '=', 1)->orderBy('general_order', 'asc')->get();

        //[id,original_indicator_id]
        $ace_milestones_dlrs = AceDlrIndicator::where('status', '=', 1)
            ->where('is_milestone', '=', 1)
            ->orderBy('general_order', 'asc')
            ->pluck('original_indicator_id','id')->toArray();

        //[indicator_id,total_milestone]
        $milestone_dlrs = MilestonesDlrs::where('ace_id','=',$id)
            ->select(DB::raw('count(*) as total_milestone, indicator_id'))
            ->groupBy('indicator_id')
            ->pluck('total_milestone','indicator_id')
            ->toArray();

//        $project_start_year = config('app.reporting_year_start');
//        $project_year_length = config('app.reporting_year_length');
//        for ($a = 1; $a <= $project_year_length; $a++) {
//            $years[$a] = $project_start_year+$a-1;
//        }

        $currency1 =  Currency::where('id','=',$ace->currency1_id)->orderBy('name', 'ASC')->first();
        $currency2= Currency::where('id','=',$ace->currency2_id)->orderBy('name', 'ASC')->first();
        $total_currencies = [];
        if ($currency1) {
            $total_currencies[$currency1->id] = $currency1->code;
        }
        if ($currency2) {
            $total_currencies[$currency2->id] = $currency2->code;
        }
        //[ace_dlr_indicator_id,currency_id]
        $dlr_currencies = AceDlrIndicatorCost::where('ace_id','=', $ace->id)
            ->whereNotNull('currency_id')
            ->orderBy('ace_dlr_indicator_id','asc')
            ->pluck('currency_id','ace_dlr_indicator_id')->toArray();

        //[ace_dlr_indicator_id,max_cost]
        $dlr_max_cost = AceDlrIndicatorCost::where('ace_id','=', $ace->id)
            ->whereNotNull('currency_id')
            ->orderBy('ace_dlr_indicator_id','asc')
            ->pluck('max_cost','ace_dlr_indicator_id')->toArray();

        $dlr_values = AceDlrIndicatorValue::where('ace_id','=', $ace->id)
            ->where('reporting_year','=',$year)
            ->pluck('value','ace_dlr_indicator_id')->toArray();

        $parent_indicators = AceDlrIndicator::active()
            ->parent_indicators()
            ->orderBy('order','asc')
            ->get();

        $master_parent_total = AceDlrIndicator::where('set_max_dlr','=',0)
            ->where('master_parent_id','!=',0)
            ->select(DB::raw('count(id) as total, master_parent_id'))
            ->groupBy('master_parent_id')
            ->orderBy('master_parent_id','asc')
            ->pluck('total','master_parent_id')
            ->toArray();
//        dd($master_parent_total);

        return view('aces.dlr_costs', compact('ace','currency1','currency2',
            'ace_dlrs','parent_indicators','year','total_currencies','dlr_currencies','milestone_dlrs',
            'ace_milestones_dlrs','dlr_values','dlr_max_cost','master_parent_total'));
    }
    public function save_ace_dlr_unit_values(Request $request, $ace_id, $year)
    {
//        dd($request->all());
        $this->validate($request,[
            'dlr' => 'nullable|array|min:1',
            'dlr.*' => 'nullable|numeric|min:0',
        ]);

        foreach ($request->dlr as $indicator => $value) {
            AceDlrIndicatorValue::updateOrCreate([
                'ace_id' => Crypt::decrypt($ace_id),
                'ace_dlr_indicator_id' => $indicator,
                'reporting_year' => $year,
            ], [
                'value' => $value,
            ]);
        }
        notify(new ToastNotification('Successful', 'DLR Indicator Costs Values Saved.', 'success'));
        return back();
    }

    public function getContactGroup($ace_id){
        $the_ace = Ace::find($ace_id);
        $contacts = DB::table('contacts')->join('ace_contacts', 'ace_contacts.contact_id', '=', 'contacts.id')
            ->rightJoin('positions','positions.id','contacts.position_id')
            ->where('ace_contacts.ace_id','=',$ace_id)
            ->select('contacts.*','positions.position_title')
            ->get();

        return $contacts;
    }

    public function conversions(Request $request){
        $this->validate($request,[
            'sdr_to_usd' => 'nullable|numeric|min:0',
            'euro_to_usd' => 'nullable|numeric|min:0',
        ]);
        $the_ace = Ace::find($request->ace_id);
        $the_ace->sdr_to_usd = $request->sdr_to_usd;
        $the_ace->euro_to_usd = $request->sdr_to_usd;
        if ($the_ace->save()) {
            notify(new ToastNotification('Successful','Conversion Updated','success'));
        } else {
            notify(new ToastNotification('Sorry','Please enter accurate figures','warning'));
        }
        return back();
    }

//    Workplan
    public function saveWorkPlan(Request $request,$ace_id){
        $ace_id = $request->ace_id;
        $wp_filename=$request->wp_file;
        $destinationPath = base_path() . '/public/WorkPlan/';
        $file1 = $request->file('wp_file');
        $wp_year=$request->wp_year;

        $year_exists = WorkPlan::where('ace_id','=',$ace_id)->where('wp_year','=',$wp_year)->get();

        if($year_exists->isNotEmpty()){
            notify(new ToastNotification('error', 'You have already submitted a workplan for this year.', 'error'));
            return back()->withInput();
        }
        if (isset($file1)) {
            $file1->move($destinationPath, $file1->getClientOriginalName());
            $the_wpfile=$wp_filename->getClientOriginalName();
        }

        $saveWorkPlan = WorkPlan::updateOrCreate(
            ['ace_id' => $ace_id,
                'submission_date' => date('Y-m-d', strtotime($request->submission_date)),
                'wp_file' => $the_wpfile,
                'wp_year' => $request->wp_year
            ]
        );

        if (isset($saveWorkPlan)) {
            notify(new ToastNotification('Successful!', 'WorkPlan Added', 'success'));
            return back();
        } else {
            notify(new ToastNotification('Warning', 'Something might have happened. Please try again.', 'warning'));
            return back();
        }
    }

    public  function  destroyWorkPlan($id){
        $wp_entry = WorkPlan::find(Crypt::decrypt($id));

            $wp_entry->delete();
            notify(new ToastNotification('Successful!', 'WorkPlan Deleted!', 'success'));

        return back();

    }

    public function baselines($id) {
        $ace_id = Crypt::decrypt($id);

        $ace = Ace::find($ace_id);
        $all_aces = Ace::get();
        $project = Project::find(1);
        $values = array();
        $getBaselines = AceIndicatorsBaseline::where('ace_id', '=', $ace_id)->pluck('baseline', 'indicator_id');
        if ($getBaselines->isNotEmpty()) {
            $values = $getBaselines;
        }

        return view('aces.baselines', compact('ace', 'project', 'all_aces', 'values'));
    }

    public function target_values($id, $year_id = null) {
        $ace_id = Crypt::decrypt($id);

        $ace = Ace::find($ace_id);
        $all_aces = Ace::get();
        $project = Project::find(1);
        $indicators = Indicator::where('is_parent','=',1)
            ->where('status','=',1)
            ->where('set_target','=',1)
            ->orderBy('identifier','asc')
            ->get();
        $values = array();
        $getYear = null;
        if ($year_id != null) {
            $getYear = AceIndicatorsTargetYear::find($year_id);
            $getTargets = AceIndicatorsTarget::where('target_year_id', '=', $year_id)->pluck('target', 'indicator_id');

            if ($getTargets->isNotEmpty()) {
                $values = $getTargets;
            }
        }

        return view('aces.target_values', compact('ace', 'project', 'all_aces', 'values',
            'year_id', 'getYear', 'indicators'));
    }

    public function baselines_save(Request $request, $id) {
        $ace_id = Crypt::decrypt($id);
        $getBaselines = AceIndicatorsBaseline::where('ace_id', '=', $ace_id)->get();
        if ($getBaselines->isNotEmpty()) {
            foreach ($request->indicators as $indicator => $baseline) {
                $getBaselines = AceIndicatorsBaseline::where('ace_id', '=', $ace_id)->where('indicator_id', '=', $indicator)->update([
                    'baseline' => $baseline,
                    'user_id' => Auth::id(),
                ]);
            }
            notify(new ToastNotification('Successful', 'Baselines have been updated.', 'success'));
        } else {
            foreach ($request->indicators as $indicator => $baseline) {
                $ace_baseline = new AceIndicatorsBaseline();
                $ace_baseline->ace_id = $ace_id;
                $ace_baseline->indicator_id = $indicator;
                $ace_baseline->baseline = $baseline;
                $ace_baseline->user_id = Auth::id();
                $ace_baseline->save();
            }
            notify(new ToastNotification('Successful', 'Baselines have been saved.', 'success'));
        }

        return back();
    }

    public function targets_save(Request $request, $ace_id, $target_year_id = null) {
        $this->validate($request, [
            'reporting_year' => 'required|integer',
            'indicators' => 'required|array',
        ]);
        $aceId = Crypt::decrypt($ace_id);
        if ($target_year_id != null) {

            AceIndicatorsTargetYear::find($target_year_id)->update([
                'reporting_year' => $request->reporting_year,
                'user_id' => Auth::id(),
            ]);
            AceIndicatorsTarget::where('target_year_id', '=', (integer)$target_year_id)->delete();

            foreach ($request->indicators as $indicator => $target) {
                AceIndicatorsTarget::create([
                    'ace_id' => (integer)$aceId,
                    'target_year_id' => (integer)$target_year_id,
                    'indicator_id' => (integer)$indicator,
                    'target' => (integer)$target,
                ]);
            }
            notify(new ToastNotification('Successful', 'Indicator Targets updated.', 'success'));
        } else {
            $target_year = new AceIndicatorsTargetYear();
            $target_year->ace_id = (integer)$aceId;
            $target_year->user_id = (integer)Auth::id();
            $target_year->reporting_year = (integer)$request->reporting_year;
            $target_year->save();

            foreach ($request->indicators as $indicator => $target) {
                AceIndicatorsTarget::create([
                    'ace_id' => $aceId,
                    'target_year_id' => $target_year->id,
                    'indicator_id' => $indicator,
                    'target' => $target,
                ]);
            }

            notify(new ToastNotification('Successful', 'Indicator Targets added.', 'success'));
        }
        return back();
    }

    public function milestones($hashed_ace_id)
    {
        $ace_id = Crypt::decrypt($hashed_ace_id);
        $ace = Ace::find($ace_id);
        $dlr_milestone_indicators = Indicator::milestones()->get();

        return view('aces.milestones.index', compact('ace','dlr_milestone_indicators'));
    }

    public function add_milestone(Request $request,$hashed_ace_id)
    {
        $this->validate($request,[
           'indicator'          =>  'required|numeric',
           'milestone_no'       =>  'required|numeric|min:1',
           'description'        =>  'required|string',
        ]);
        $ace_id = Crypt::decrypt($hashed_ace_id);
        $ace = Ace::find($ace_id);
        $already_exist = MilestonesDlrs::where('ace_id','=', $ace_id)
            ->where('milestone_no','=', $request->milestone_no)
            ->where('indicator_id','=', $request->indicator)
            ->first();

        if ($already_exist) {
            notify(new ToastNotification('Sorry','This milestone already exists','error'));
            return back();
        }

        $milestone_id = DB::table('milestones_dlrs')->insertGetId(
            [
                'indicator_id' => $request->indicator,
                'milestone_no' => $request->milestone_no,
                'description' => $request->description,
                'ace_id' => $ace_id,
            ]
        );
        if ($milestone_id) {
            return redirect()->route('user-management.ace.milestone.edit',
                [$hashed_ace_id,$milestone_id]);
        }
    }

    public function edit_milestone($hashed_ace_id,$milestone_id) {
        $ace_id = Crypt::decrypt($hashed_ace_id);
        $ace = Ace::find($ace_id);
        $dlr_milestone = MilestonesDlrs::find($milestone_id);
        $indicator = Indicator::find($dlr_milestone->indicator_id);

        return view('aces.milestones.edit', compact('ace','dlr_milestone','indicator'));
    }

    public function update_milestone(Request $request,$ace_id,$milestone_id) {

        $this->validate($request,[
            'estimated_cost'                =>  'required|numeric|min:1',
            'estimated_earning'             =>  'required|numeric|min:1',
            'start_expected_timeline'       =>  'required|string',
            'end_expected_timeline'         =>  'required|string',
        ]);
        $milestone = MilestonesDlrs::find($milestone_id);
        $milestone->estimated_cost = $request->estimated_cost;
        $milestone->estimated_earning = $request->estimated_earning;
        $milestone->start_expected_timeline = $request->start_expected_timeline;
        $milestone->end_expected_timeline = $request->end_expected_timeline;

        if ($milestone->save()) {
            notify(new ToastNotification('Successful','Information has been updated','success'));
        }

        return back();
    }

    public function target_save(Request $request,$ace_id,$milestone_id) {

        $this->validate($request,[
            'target_indicator'  =>  'required|string',
        ]);

        if (isset($request->target_id)) {
            $target = MilestonesDlrsTarget::find($request->target_id);
        } else {
            $target = new MilestonesDlrsTarget();
        }
        $target->target_indicator = $request->target_indicator;
        $target->milestones_dlr_id = $request->milestone_id;
        $target->save();

        if ($target->save()) {
            notify(new ToastNotification('Successful','Information has been updated','success'));
        }

        return back();
    }

    public function remove_target($milestone_target_id) {

        if (MilestonesDlrsTarget::destroy($milestone_target_id)) {
            notify(new ToastNotification('Successful','Information has been deleted','success'));
        } else {
            notify(new ToastNotification('Sorry','No information was found','danger'));
        }
        return back();
    }
}

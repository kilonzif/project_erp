<?php

namespace App\Http\Controllers;
use App\Ace;
use App\AceCourse;
use App\AceDlrIndicator;
use App\AceDlrIndicatorCost;
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
        $addAce->save();

        if (isset($addAce->id)) {
            $currency1 =  Currency::where('id','=',$addAce->currency1_id)->orderBy('name', 'ASC')->first();
            $currency2= Currency::where('id','=',$addAce->currency2_id)->orderBy('name', 'ASC')->first();

            notify(new ToastNotification('Successful!', 'New ACE Added', 'success'));
            return redirect()->route('user-management.aces.profile', compact([Crypt::encrypt($addAce->id)],'currency1','currency2'));
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
        $this->validate($request, [
            'file_one.*' => 'nullable|file|mimes:xls,pdf,docx|max:10000',
            'file_two.*' => 'nullable|mimes:xls,docx,pdf|max:10000',
            'url' => 'sometimes|required',
            'submission_date' => 'sometimes|required',
            'comments' => 'sometimes|required',
        ]);

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
            'ss_file_one' => 'required|file|mimes:xls,xlsx',
            'ss_submission_date' => 'required',
        ]);

        $ace_id = Crypt::decrypt($id);
        $oldIndicator=IndicatorOne::find($ace_id);
        $requirement = $request->ss_requirement;
        $submission_date = $request->ss_submission_date;
        $file_one=$request->ss_file_one;
        $destinationPath = base_path() . '/public/indicator1/';
        $thefile_one = "";

        $file1 = $request->file('ss_file_one');


        if (isset($file1)) {
            $dd = $this->extractMembers($file1, $ace_id);
            if ($dd) {
                $file1->move($destinationPath, $file1->getClientOriginalName());
                $thefile_one = $file_one->getClientOriginalName();
            }else{
                notify(new ToastNotification('Notice', 'An error occured extracting data- Please check the format and try again.', 'info'));
                return back();
            }
        }
        $saveIndicatorOne = IndicatorOne::updateOrCreate(
            ['ace_id' => $ace_id, 'requirement' => $request->ss_requirement],
            ['submission_date' => $request->ss_submission_date,
                'file_one' => $thefile_one]
        );

        if (isset($saveIndicatorOne)) {

//            where you extract the members
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
            'ace_state' => $request->ace_state
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
        $dlr_max_costs = AceDlrIndicatorCost::where('ace_id', '=', $id)->pluck('max_cost','ace_dlr_indicator_id');
        $ace_dlrs = AceDlrIndicator::where('parent_id', '=', 0)->orderBy('order', 'asc')->get();

        $target_years = $ace->target_years;
        $aceemails= $this->getContactGroup($id);

        $workplans=WorkPlan::where('ace_id',$ace->id)->get();
        $roles = Position::whereNotIn('rank',[1,2,3,4])->get();


        $currency1 =  Currency::where('id','=',$ace->currency1_id)->orderBy('name', 'ASC')->first();
        $currency2= Currency::where('id','=',$ace->currency2_id)->orderBy('name', 'ASC')->first();

        $requirements=Indicator::activeIndicator()->parentIndicator(1)->pluck('title');

        return view('aces.profile', compact('ace','workplans','roles','currency1','currency2','dlr_unit_costs', 'target_years',
            'ace_dlrs', 'aceemails', 'dlr_max_costs','requirements'));
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
            'start' => 'required|date',
            'end' => 'required|date',
            'indicators' => 'required|array',
        ]);
        $aceId = Crypt::decrypt($ace_id);
        if ($target_year_id != null) {

            AceIndicatorsTargetYear::find($target_year_id)->update([
                'start_period' => $request->start,
                'end_period' => $request->end,
                'user_id' => Auth::id(),
            ]);

            foreach ($request->indicators as $indicator => $target) {
                AceIndicatorsTarget::where('ace_id', '=', $aceId)
                    ->where('target_year_id', '=', $target_year_id)
                    ->where('indicator_id', '=', $indicator)
                    ->update([
                        'target' => $target,
                    ]);
            }

            notify(new ToastNotification('Successful', 'Indicator Targets updated.', 'success'));
        } else {
            $target_year = new AceIndicatorsTargetYear();
            $target_year->ace_id = $aceId;
            $target_year->user_id = Auth::id();
            $target_year->start_period = $request->start;
            $target_year->end_period = $request->end;
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
}

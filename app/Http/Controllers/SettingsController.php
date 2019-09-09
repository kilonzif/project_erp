<?php

namespace App\Http\Controllers;

use App\Course;
use App\SubIndicator;
use App\Classes\ToastNotification;
use App\Indicator;
use App\Project;
use App\Specific;
use App\UnitMeasure;
use App\Aceemail;
use App\Ace;
use Complex\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Showing the list of Main Courses
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function courses()
    {
        $courses = Course::orderBy('name','ASC')->get();
        return view('settings.courses', compact('courses'));
    }


    public function edit_course_view(Request $request){
        $id = $request->id;
        $course = Course::find($id);
        $view = view('settings.course_edit', compact('course'))->render();
        return response()->json(['theView'=>$view]);
    }


    public function add_course(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string|min:1|unique:courses',
        ]);
        $course = new Course();
        $course->name = $request->name;
        $course->save();

        notify(new ToastNotification('Successful!', 'Course Added!', 'success'));
        return back();
    }

    public function update_course(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string|min:1|unique:courses',
        ]);
        $course = Course::find($request->id);
        $course->name = $request->name;
        $course->save();

        notify(new ToastNotification('Successful!', 'Course Updated!', 'success'));
        return back();
    }


    /**
     * Showing the list of Main Indicators
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indicators()
    {
        $indicators = Indicator::where('parent_id','=', 0)
//            ->orderBy('identifier','asc')
            ->orderBy('order_no','asc')
            ->get();
        $projects = Project::get();
        $parentIndicators=Indicator::where('isparent','=',1)->get();
        return view('settings.indicators', compact('indicators','parentIndicators','projects'));
    }

    /**
     * Returns the edit view of selected Indicator
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function activate_indicator(Request $request)
    {
        $id = Crypt::decrypt($request->id);
        $indicator = Indicator::find($id);

        if ($indicator->status == 1){
            $indicator->status = 0;
            $indicator->save();
            $indicator->where('parent_id','=',$id)->update(['status'=>0]);
            notify(new ToastNotification('Successful!', 'Indicator Deactivated', 'success'));
        }else{
            $indicator->status = 1;
            $indicator->save();
            $indicator->where('parent_id','=',$id)->update(['status'=>1]);
            notify(new ToastNotification('Successful!', 'Indicator Activated', 'success'));
        }
        return back();
    }

    /**
     * Returns the edit view of selected Indicator
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function edit_indicator(Request $request)
    {
        $indicator = Indicator::find($request->id);
        $indicators = Indicator::where('parent_id','=', 0)
//            ->orderBy('identifier','asc')
            ->orderBy('order_no','asc')
            ->get();
        $projects = Project::get();
        $view = view('settings.json-views.edit-indicator', compact('indicator','indicators','projects'))->render();
        return response()->json(['theView'=>$view]);
    }

    /**
     * Add New Main Indicator
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save_indicator(Request $request)
    {

        $this->validate($request,[
            'title' => 'required|string|min:5',
            'identifier' => 'required|string|min:1',
            'unit_of_measure' => 'required|string|min:5',
            'order_no' => 'required|numeric|min:1',
            'on_report' => 'required|numeric|min:0',
            'upload' => 'nullable|numeric|max:1'
        ]);

        Indicator::create([
            'title' => $request->title,
            'order_no' => $request->order_no,
            'identifier' => $request->identifier,
            'unit_measure' => $request->unit_of_measure,
            'parent_id' => $request->parentIndicator,
            'show_on_report' => $request->on_report,
            'upload' => $request->upload,
            'isparent'=>1
        ]);
        notify(new ToastNotification('Successful!', 'Indicator Added!', 'success'));
        return back();
    }

    /**
     * Update Main Indicator
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_indicator(Request $request)
    {

        $this->validate($request,[
            'id' => 'required|string|min:1',
            'title' => 'required|string|min:5',
            'identifier' => 'required|string|min:1',
            'unit_of_measure' => 'required|string|min:5',
            'order_no' => 'required|numeric|min:1',
            'on_report' => 'required|numeric|min:0',
            'upload' => 'nullable|numeric|max:1',
            'parentIndicator'=>'required'
        ]);

//        dd($request->all());


        Indicator::where('id','=', $request->id)->update([

            'title' => $request->title,
            'order_no' => $request->order_no,
            'identifier' => $request->identifier,
            'unit_measure' => $request->unit_of_measure,
            'parent_id' => $request->parentIndicator,
            'show_on_report' => $request->on_report,
            'upload' => $request->upload,
            'isparent'=>1
        ]);
        notify(new ToastNotification('Successful!', 'Indicator Updated!', 'success'));
        return back();
    }

    /**
     * Main Indicator Config page
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function config_indicator($id)
    {
        $indicator = Indicator::find($id);
        $sub_indicators = Indicator::where('parent_id','=',$indicator->id)->orderBy('parent_id','asc')->orderBy('order_no','asc')->get();
        $uoms = UnitMeasure::where('indicator_id','=',$indicator->id)->orderBy('order_no','asc')->get();
        return view('settings.indicator_config',compact('indicator','sub_indicators','uoms'));
    }

    /**
     * Add a new Unit of Measure
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save_unit_measure(Request $request)
    {
        $this->validate($request,[
            'title' => 'required|string|min:3',
            'order' => 'required|numeric|min:1',
            'ali_id' => 'required|numeric|min:1',
        ]);

        UnitMeasure::create([
            'title' => $request->title,
            'order_no' => $request->order,
            'indicator_id' => $request->ali_id
        ]);
        notify(new ToastNotification('Successful!', 'Unit of Measure Added!', 'success'));
        return back();
    }

    /**
     * Add Sub Indicator
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save_sub_indicator(Request $request){

        $this->validate($request,[
            'title' => 'required|string|min:3',
            'order' => 'required|numeric|min:1',
            'indicator' => 'required|numeric|min:1',
            'uom_id' => 'nullable|numeric|min:1'
        ]);
        try{
            $parent = Indicator::find($request->indicator);

            Indicator::create([
                'title' => $request->title,
                'order_no' => $request->order,
                'project_id' => $parent->project_id,
                'parent_id' => $request->indicator,
                'unit_measure_id' => $request->uom_id,
            ]);
        }catch (Exception $exception){
            notify(new ToastNotification('Unsuccessful!', 'Please check your details.!', 'warning'));
        }

        notify(new ToastNotification('Successful!', 'Sub-Indicator created.!', 'success'));
        return back();
    }

    /**
     * Returns a view response for Unit of Measure editing
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function edit_unit_measure(Request $request)
    {
        $uom = UnitMeasure::find($request->id);

        $view = view('settings.json-views.edit-unit_measure',compact('uom'))->render();
        return response()->json(['theView'=>$view]);
    }

    /**
     * Returns a view response for Sub-Indicator editing
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function edit_sub_indicator(Request $request)
    {
        $sub_indicator = Indicator::find($request->id);
        $uoms = UnitMeasure::get();

        $view = view('settings.json-views.edit-sub_indicator',compact('uoms','sub_indicator'))->render();
        return response()->json(['theView'=>$view]);
    }

    /**
     * Update Sub-Indicator details
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_sub_indicator(Request $request)
    {
//        dd($request->all());
        $this->validate($request,[
            'title' => 'required|string|min:5',
            'order' => 'required|numeric|min:1',
            'uom_id' => 'nullable|numeric|min:1',
            'id' => 'required|numeric|min:1'
        ]);

        Indicator::where('id','=', $request->id)->update([
            'title' => $request->title,
            'order_no' => $request->order,
            'unit_measure_id' => $request->uom_id
        ]);
        notify(new ToastNotification('Successful!', 'Sub-Indicator updated!', 'success'));
        return back();
    }

    /**
     * Updates Unit of Measure
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_unit_measure(Request $request)
    {
        $this->validate($request,[
            'title' => 'required|string|min:3',
            'order' => 'required|numeric|min:1',
            'id' => 'required|numeric|min:1',
        ]);

        UnitMeasure::where('id','=', $request->id)->update([
            'title' => $request->title,
            'order_no' => $request->order
        ]);
        notify(new ToastNotification('Successful!', 'Unit of Measure Updated!', 'success'));
        return back();
    }

    ///////////////////////////////////////////////////////////////////////

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save_specific(Request $request)
    {
        $this->validate($request,[
            'title' => 'required|string|min:3',
            'order' => 'required|numeric|min:1',
            'ali_id' => 'required|numeric|min:1',
            'uom_id' => 'nullable|numeric|min:1'
        ]);

        Specific::create([
            'title' => $request->title,
            'order_no' => $request->order,
            'ace_level_indicator_id' => $request->ali_id,
            'unit_measure_id' => $request->uom_id
        ]);
        notify(new ToastNotification('Successful!', 'Specific Added!', 'success'));
        return back();
    }
    public function ace_level_indicators(){
        $indicators = Indicator::orderBy('order_no','asc')->get();
        $ace_level_indicators = SubIndicator::orderBy('indicator_id','asc')->orderBy('order_no','asc')->get();
        return view('settings.ace_level_indicators',compact('indicators','ace_level_indicators'));
    }

    public function ace_level_indicators_details($id){
        $ace_level_indicator = SubIndicator::find($id);
        $uoms = UnitMeasure::where('ace_level_indicator_id','=', $id)->orderBy('order_no','asc')->get();
        $specifics = Specific::where('ace_level_indicator_id','=', $id)->orderBy('order_no','asc')->get();
        return view('settings.ali-details',compact('ace_level_indicator','uoms','specifics'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function projects(){
        $indicators = Indicator::orderBy('order_no','asc')->orderBy('identifier','asc')->get();

        $projects = Project::get();
        return view('settings.projects', compact('indicators','projects'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mailing_list(){
        $aces = Ace::all();
        $aceemails= Aceemail::all();
        return view('settings.mailinglist',compact('aces','aceemails'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save_mailing_list(Request $request){

        $this->validate($request,[
            'ace_id' => 'required|string|min:1',

            'email' => 'required|string|min:1',

        ]);

        Aceemail::create([
            'ace_id' => $request->ace_id,
            'email' => $request->email

        ]);
        notify(new ToastNotification('Successful!', 'Email Added!', 'success'));
        return back();
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit_mailinglist($id){
        $aceemail_id = Crypt::decrypt($id);
        $aceemails = Aceemail::find($aceemail_id);
        $aces = Ace::all();
        return view('settings.mailinglist.edit', compact('aceemails','aces'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_mailinglist(Request $request,$id){


        $this->validate($request,[

            'ace_id' => 'required|numeric|min:1',
            'email' => 'required|string|email|min:3'

        ]);

        $aceemails = Aceemail::find($id);

        $aceemails->ace_id =$request->ace_id;
        $aceemails->email=$request->email;

        $aceemails->save();

        notify(new ToastNotification('Successful!', ' Email Edited!', 'success'));
        return redirect()->route('settings.mailinglist');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function   destroy_mailinglist($id){

        $aceemail_id = Crypt::decrypt($id);
//        $aceemails = Aceemail::destroy($aceemail_id);
        notify(new ToastNotification('Successful!', '  deleted!', 'success'));
        return redirect()->back();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view_project(){
        $indicators = Indicator::get();
        $projects = Project::get();
        return view('settings.projects.project_view', compact('projects','indicators'));

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save_project(Request $request){
        $this->validate($request,[
            'title' => 'required|string|min:3',
            'project_coordinator' => 'required|string|min:2',
            'grant_id' => 'required|numeric|min:1',
            'total_grant' => 'required|numeric|min:1',
            'start_date'=>'required|date',
            'end_date'=>'required|date'
        ]);

        Project::create([
            'title' => $request->title,
            'project_coordinator' => $request->project_coordinator,
            'grant_id' => $request->grant_id,
            'total_grant' => $request->total_grant,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);
        notify(new ToastNotification('Successful!', 'Project Added!', 'success'));
        return back();
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit_project($id){

        $project_id = Crypt::decrypt($id);

        $projects = Project::find($project_id);

        return view('settings.projects.project_edit', compact('projects'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_project(Request $request,$id){

        $this->validate($request,[
            'title' => 'required|string|min:3',
            'project_coordinator' => 'required|string|min:2',
            'grant_id' => 'required|numeric|min:1',
            'total_grant' => 'required|numeric|min:1',
            'start_date'=>'required|date',
            'end_date'=>'required|date'
        ]);

        $project = Project::find($id);

        $project->title= $request->title;
        $project->project_coordinator= $request->project_coordinator;
        $project->grant_id= $request->grant_id;
        $project->total_grant= $request->total_grant;
        $project->start_date= $request->start_date;
        $project->end_date= $request->end_date;
        $project->save();

        notify(new ToastNotification('Successful!', 'Project Edited!', 'success'));
        return redirect()->route('settings.projects');
    }

}

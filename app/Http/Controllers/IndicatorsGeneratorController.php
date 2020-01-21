<?php

namespace App\Http\Controllers;

use App\Classes\ToastNotification;
use App\Indicator;
use App\IndicatorForm;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndicatorsGeneratorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $fields = DB::connection('mongodb')->collection('indicator_form')->get();
        return view('settings.indicator-form.index',compact('fields'));
    }

    public function create()
    {
        $indicators = Indicator::where('is_parent','=', 1)->where('status','=', 1)->where('upload','=', 1)->get();
        $projects = Project::where('status','=', 1)->get();
        return view('settings.indicator-form.form-creator', compact('indicators','projects'));
    }

    public function save(Request $request)
    {
        $theFields = $request->fields;
        foreach ($theFields as $key => $field){
            $theFields[$key]['slug'] = str_slug($field['label']);
        }

        $lang= array();
        $lang['Text'] =$request->language;

        $request->fields = $theFields;
        $values['indicator'] = (integer)$request->indicator;
        $values['project'] = (integer)$request->project;
        $values['start_row'] = (integer)$request->start_row;
        $values['language'] = $lang;
        $values['fields'] = $request->fields;


        $row = DB::connection('mongodb')->collection('indicator_form')->insert($values);

        if($row){
            notify(new ToastNotification('Successful!', 'The Indicator Form has been added!', 'success'));
            return back();
        }else{
            notify(new ToastNotification('Sorry!', 'Something went wrong. Could not submit!', 'danger'));
            return back();
        }
    }

    public function edit($id)
    {
        $indicators = Indicator::where('is_parent','=', 1)->where('status','=', 1)->where('upload','=', 1)->get();
        $projects = Project::where('status','=', 1)->get();
        $form = IndicatorForm::find($id);



        return view('settings.indicator-form.form-edit', compact('indicators','projects','form'));
    }

    public function update(Request $request,$id)
    {
        $theFields = $request->fields;
        foreach ($theFields as $key => $field){
            $theFields[$key]['slug'] = str_slug($field['label']);
        }

        $lang= array();
        $lang['Text'] =$request->language;



        $request->fields = $theFields;
        $values['indicator'] = (integer)$request->indicator;
        $values['project'] = (integer)$request->project;
        $values['start_row'] = (integer)$request->start_row;
        $values['language'] = $lang;
        $values['fields'] = $request->fields;

//        dd($values['language']);

        $row = DB::connection('mongodb')->collection('indicator_form')->where('_id', $id)->update($values);

        if($row){
            notify(new ToastNotification('Successful!', 'The Indicator Form has been updated!', 'success'));
            return redirect()->route('settings.indicator.generated_forms');
        }else{
            notify(new ToastNotification('Sorry!', 'Update Failed!', 'danger'));
            return back();
        }
    }
}

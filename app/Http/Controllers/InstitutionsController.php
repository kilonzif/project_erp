<?php

namespace App\Http\Controllers;

use App\Classes\ToastNotification;
use App\Country;
use App\Institution;
use Illuminate\Http\Request;

class InstitutionsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $institutions = Institution::all();
        $countries = Country::all();
        return view('institutions.index', compact('institutions','countries'));
    }

    public function create(Request $request){

        $this->validate($request,[
            'name' => 'required|string|min:3|unique:institutions,name',
            'contact' => 'nullable|numeric|digits_between:10,17|unique:institutions,name',
            'email' => 'nullable|string|min:3',
            'country' => 'required|integer|min:1',
            'is_uni' => 'nullable|boolean',
            'contact_name' => 'nullable|string|min:3',
            'contact_email' => 'nullable|string|email|min:3',
            'contact_person_phone' => 'nullable|numeric|digits_between:10,20',
            'position' => 'nullable|string|min:3',
        ]);

        $addInstitution = new Institution();
        $addInstitution->name = $request->name;
        $addInstitution->contact = $request->contact;
        $addInstitution->email = $request->email;
        $addInstitution->country_id = $request->country;
        $addInstitution->university = $request->is_uni;
        $addInstitution->contact_person = $request->contact_name;
        $addInstitution->person_email = $request->contact_email;
        $addInstitution->person_number = $request->contact_person_phone;
        $addInstitution->position = $request->position;
        $addInstitution->save();
        notify(new ToastNotification('Successful!', 'New Institution Added', 'success'));
        return back();
    }

    public function edit(Request $request){
        $institution = Institution::find($request->id);
        $countries = Country::all();
        $view = view('institutions.edit', compact('institution','countries'))->render();
        return response()->json(['theView'=>$view]);
    }

    public function update(Request $request){

        $this->validate($request,[
            'name' => 'required|string|min:3|unique:institutions,name,'.$request->id,
            'contact' => 'nullable|numeric|digits_between:10,17',
            'email' => 'nullable|string|min:3',
            'country' => 'required|integer|min:1',
            'is_uni' => 'nullable|boolean',
            'contact_name' => 'nullable|string|min:3',
            'contact_email' => 'nullable|string|email|min:3',
            'contact_person_phone' => 'nullable|numeric|digits_between:10,20',
            'position' => 'nullable|string|min:3',
        ]);
//        return $request->all();

        $addInstitution = Institution::find($request->id);
        $addInstitution->name = $request->name;
        $addInstitution->contact = $request->contact;
        $addInstitution->email = $request->email;
        $addInstitution->country_id = $request->country;
        $addInstitution->university = $request->is_uni;
        $addInstitution->contact_person = $request->contact_name;
        $addInstitution->person_email = $request->contact_email;
        $addInstitution->person_number = $request->contact_person_phone;
        $addInstitution->position = $request->position;
        $addInstitution->save();
        notify(new ToastNotification('Successful!', 'Institution Updated', 'success'));
        return back();
    }
}

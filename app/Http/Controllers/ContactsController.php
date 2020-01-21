<?php

namespace App\Http\Controllers;

use App\Ace;
use App\Classes\ToastNotification;
use App\Contacts;
use App\Country;
use App\Institution;
use App\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ContactsController extends Controller
{
    public function index(){
        $all_contacts = Contacts::where('country', '!=',NULL)->orwhere('institution','!=',NULL)->orwhere('thematic_field','!=',NULL)->get();
        $countries = Country::all();
        $aces = Ace::all();
        $institutions = Institution::all();



        return view('contacts.index',compact('all_contacts','countries','institutions','aces'));
    }


//    central contacts
    public function save_contact(Request $request)
    {
        $this->validate($request,[
            'role' => 'required|string|min:1',
            'institution' => 'nullable|integer|min:1',
            'thematic_field' => 'nullable|string|min:1',
            'country' => 'nullable|integer|min:1',
            'mailing_name' => 'required|string|min:1',
            'mailing_phone' => 'string|min:10',
            'mailing_email' => 'required|string|min:1',

        ]);

        $contact_saved = Contacts::create([
            'ace_id' => $request->ace_id,
            'contact_name' => $request->mailing_name,
            'contact_title' => $request->role,
            'contact_phone' => $request->mailing_phone,
            'email' => $request->mailing_email,
            'institution' =>$request->institution,
            'country' =>$request->country,
            'thematic_field' =>$request->thematic_field
        ]);
        if($contact_saved) {
            notify(new ToastNotification('Successful!', 'Contact Person Added!', 'success'));

        }else{
            notify(new ToastNotification('error!', 'Error occurred while adding contact', 'ERROR'));
        }
        return back();

    }


    public function edit_view(Request $request){
        $id = Crypt::decrypt($request->id);
        $contacts = Contacts::find($id);
        $all_contacts = Contacts::where('edit_status', '=',false)->get();
        $countries = Country::all();
        $aces = Ace::all();
        $institutions = Institution::all();
        $view = view('contacts.edit_view', compact('contacts','all_contacts','institutions','countries'))->render();

        return response()->json(['theView' => $view]);
    }


    public function update_contact(Request $request,$id)
    {

        $this->validate($request, [
            'role' => 'required|string|min:1',
            'institution' => 'nullable|integer|min:1',
            'thematic_field' => 'nullable|string|min:1',
            'country' => 'nullable|integer|min:1',
            'mailing_name' => 'required|string|min:1',
            'mailing_phone' => 'string|min:10',
            'mailing_email' => 'required|string|min:1',

        ]);

        $this_contact = Contacts::find($id);

        $contact_update = $this_contact->Update([
            'ace_id' => $request->ace_id,
            'contact_name' => $request->mailing_name,
            'contact_title' => $request->role,
            'contact_phone' => $request->mailing_phone,
            'email' => $request->mailing_email,
            'institution' => $request->institution,
            'country' => $request->country,
            'thematic_field' => $request->thematic_field
        ]);
        if ($contact_update) {
            notify(new ToastNotification('Successful!', 'Contact Person Updated!', 'success'));

        } else {
            notify(new ToastNotification('error!', 'Error occurred while updating contact', 'ERROR'));
        }
        return back();

    }




/**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save_mailing_list(Request $request){

        $this->validate($request,[
            'ace_id' => 'required|string|min:1',
            'mailing_name' => 'required|string|min:1',
            'mailing_title' => 'required|string|min:1',
            'mailing_phone' => 'string|min:10',
            'mailing_email' => 'required|string|min:1',
        ]);

        Contacts::create([
            'ace_id' => $request->ace_id,
            'contact_name' => $request->mailing_name,
            'contact_title' => $request->mailing_title,
            'contact_phone' => $request->mailing_phone,
            'edit_status' =>'1',
            'email' => $request->mailing_email
        ]);
        notify(new ToastNotification('Successful!', 'Contact Person Added!', 'success'));
        return back();
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function  edit_mailinglist(Request $request)
    {
        $id = Crypt::decrypt($request->id);
        $contacts = Contacts::find($id);
        $ace = Ace::find($contacts->ace_id);
        $view = view('contacts.edit', compact('contacts','ace'))->render();

        return response()->json(['theView' => $view]);
    }





    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_mailinglist(Request $request,$id){

        $contacts = Contacts::find($id);

        $contacts->ace_id =$contacts->ace_id;
        $contacts->contact_name =$request->mailing_name;
        $contacts->contact_title =$request->mailing_title;
        $contacts->contact_phone = $request->mailing_phone;
        $contacts->email=$request->mailing_email;
        $contacts->edit_status=true;

        $contacts->save();

        notify(new ToastNotification('Successful!', ' Contact Updated!', 'success'));
        return redirect()->route('user-management.aces.profile',[\Illuminate\Support\Facades\Crypt::encrypt($contacts->ace_id)]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function   destroy_mailinglist($id){

        $aceemail_id = Crypt::decrypt($id);
        if (Contacts::destroy($aceemail_id)){
            notify(new ToastNotification('Successful!', '  deleted!', 'success'));
        } else {
            notify(new ToastNotification('Sorry!', 'Something went wrong. Please try again', 'warning'));
        }

        return redirect()->back();
    }




}
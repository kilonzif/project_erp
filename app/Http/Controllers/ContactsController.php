<?php

namespace App\Http\Controllers;

use App\Ace;
use App\AceContact;
use App\Classes\ToastNotification;
use App\Contacts;
use App\Country;
use App\Institution;
use App\Position;
use App\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ContactsController extends Controller
{
    public function index(){
        $all_contacts = DB::table('contacts')->join('ace_contacts', 'ace_contacts.contact_id', '=', 'contacts.id')
                        ->distinct('ace_contacts.id')
                        ->select('contacts.*')
                       ->get();

        $countries = Country::all();
        $aces = Ace::all();
        $roles = Position::whereIn('rank',[1,2,3,4])->get();
        $institutions = Institution::all();
        return view('contacts.index',compact('all_contacts','roles','countries','institutions','aces'));
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
        $institution = $request->institution;
        $country = $request->country;
        $thematic_field = $request->thematic_field;

        if(isset($institution)){
            $aces = Ace::where('institution_id','=',$institution)->get();
        }
        if(isset($country)){
            $aces = DB::table('aces')->join('institutions', 'aces.institution_id', '=', 'institutions.id')
                ->join('countries', 'institutions.country_id', '=', 'countries.id')
                ->where('institutions.country_id', '=', $country)
                ->distinct('countries.id')
                ->select('aces.*')
                ->get();
        }
        if(isset($thematic_field)){
            $aces = Ace::where('field','=',$thematic_field)->get();
        }

        $contact_saved = Contacts::create([
            'contact_name' => $request->mailing_name,
            'position_id' => $request->role,
            'contact_phone' => $request->mailing_phone,
            'email' => $request->mailing_email,
            'contact_status'=>1,
        ]);
        if($contact_saved) {
            foreach ($aces as $ace){
                $data=array('ace_id'=>$ace->id,"contact_id"=>$contact_saved->id);
                DB::table('ace_contacts')->insert($data);
            }
            notify(new ToastNotification('Successful!', 'Contact Person Added!', 'success'));

        }else{
            notify(new ToastNotification('error!', 'Error occurred while adding contact', 'ERROR'));
        }
        return back();

    }


    public function get_role(Request $request){
        $id = $request->id;
        $role = Position::find($id);

        $title = $role->position_title;
        return $title;
    }





    public function edit_view(Request $request){
        $id = Crypt::decrypt($request->id);
        $contacts = Contacts::find($id);
        $all_contacts = Contacts::where('edit_status', '=',false)->get();
        $countries = Country::all();
        $aces = Ace::all();
        $roles = Position::whereIn('rank',[1,2,3,4])->get();
        $institutions = Institution::all();
        $view = view('contacts.edit_view', compact('contacts','all_contacts','roles','institutions','countries'))->render();

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
            'contact_status'=>'required'

        ]);
        $institution = $request->institution;
        $country = $request->country;
        $thematic_field = $request->thematic_field;

        if(isset($institution)){
            $aces = Ace::where('institution_id','=',$institution)->get();
        }
        if(isset($country)){
            $aces = DB::table('aces')->join('institutions', 'aces.institution_id', '=', 'institutions.id')
                ->join('countries', 'institutions.country_id', '=', 'countries.id')
                ->where('institutions.country_id', '=', $country)
                ->distinct('countries.id')
                ->select('aces.*')
                ->get();
        }
        if(isset($thematic_field)){
            $aces = Ace::where('field','=',$thematic_field)->get();
        }

        $this_contact = Contacts::find($id);

        $contact_update = $this_contact->Update([
            'contact_name' => $request->mailing_name,
            'position_id' => $request->role,
            'contact_phone' => $request->mailing_phone,
            'email' => $request->mailing_email,
            'contact_status'=>$request->contact_status,

        ]);


        if ($contact_update) {
//            foreach ($aces as $ace){
//                $data=array('ace_id'=>$ace->id,"contact_id"=>$request->contact_id);
//                DB::table('ace_contacts')->update($data);
//            }
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

        $saved= Contacts::create([
            'contact_name' => $request->mailing_name,
            'position_id' => $request->mailing_title,
            'contact_phone' => $request->mailing_phone,
            'edit_status' =>'1',
            'email' => $request->mailing_email
        ]);

        if($saved){

            $data=array('ace_id'=>$request->ace_id,"contact_id"=>$saved->id);
            DB::table('ace_contacts')->insert($data);
            notify(new ToastNotification('Successful!', 'Contact Person Added!', 'success'));
        }else{
            notify(new ToastNotification('error!', 'Error occurred while adding contact', 'ERROR'));
        }

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

        $ace = AceContact::where('contact_id','=',$contacts->id)->first();
        $roles = Position::whereNotIn('rank',[1,2,3,4])->get();
        $view = view('contacts.edit', compact('contacts','ace','roles'))->render();

        return response()->json(['theView' => $view]);
    }





    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_mailinglist(Request $request,$id){

        $contacts = Contacts::find($id);


        $this->validate($request,[
            'ace_id' => 'required|string|min:1',
            'mailing_name' => 'required|string|min:1',
            'mailing_title' => 'required|string|min:1',
            'mailing_phone' => 'string|min:10',
            'mailing_email' => 'required|string|min:1',
        ]);

        $updated= $contacts->Update([
            'contact_name' => $request->mailing_name,
            'position_id' => $request->mailing_title,
            'contact_phone' => $request->mailing_phone,
            'edit_status' =>true,
            'email' => $request->mailing_email
        ]);

        if($updated){
//            $data=array('ace_id'=>$request->ace_id,"contact_id"=>$contacts->id);
//
//            DB::table('ace_contacts')->update($data);
            notify(new ToastNotification('Successful!', 'Contact Person Updated!', 'success'));
        }else{
            notify(new ToastNotification('error!', 'Error occurred while updated contact', 'ERROR'));
        }

        return back();
  }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function   destroy_mailinglist($id){

        $aceemail_id = Crypt::decrypt($id);
        $ace_contacts = AceContact::where('contact_id','=',$aceemail_id)->get();
        if (Contacts::destroy($aceemail_id)){
            foreach ($ace_contacts as $i){
                AceContact::destroy($i->id);
            }
            notify(new ToastNotification('Successful!', '  deleted!', 'success'));
        } else {
            notify(new ToastNotification('Sorry!', 'Something went wrong. Please try again', 'warning'));
        }

        return redirect()->back();
    }




}
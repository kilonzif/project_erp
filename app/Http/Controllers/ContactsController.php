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
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use phpDocumentor\Reflection\Types\Null_;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use vendor\project\StatusTest;
//use Illuminate\Support\Facades\Storage;
//use Illuminate\Support\Facades\File;



class ContactsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(){
        $all_contacts =DB::table('contacts')
            ->join('positions','positions.id','=','contacts.position_id')
            ->select ('contacts.id as contact_id')
            ->select('positions.*','contacts.*')
            ->orderBy('positions.rank','ASC')
            ->get();

        $ace_roles = Position::where('position_type','=','ACE level')->get();

        $countries = Country::all();
        $aces = Ace::all();
        $roles = Position::all();
        $institutions = Institution::all();
        return view('contacts.index',compact('all_contacts','ace_roles','roles','countries','institutions','aces'));
    }


//    central contacts
    public function save_contact(Request $request)
    {
        $this->validate($request,[
            'role' => 'required|string|min:1',
            'second_role' => 'nullable|integer|min:1',
            'institution' => 'nullable|integer|min:1',
            'thematic_field' => 'nullable|string|min:1',
            'country' => 'nullable|integer|min:1',
            'ace' => 'nullable|integer|min:1',
            'mailing_name' => 'required|string|min:1',
            'gender' => 'required|string|min:1',
            'mailing_phone' => 'string|min:10',
            'mailing_email' => 'required|string|min:1',
            'new_contact' => 'required|min:1'

        ]);
        $institution = $request->institution;
        $country = $request->country;
        $thematic_field = $request->thematic_field;
        $ace = $request->ace;

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
        if(isset($ace)){
            $aces = Ace::find($ace)->get();
        }

        $new_contact = new Contacts();
        $new_contact->position_id =$request->role;
        $new_contact->second_role_id =$request->second_role;
        $new_contact->person_title =$request->person_title;
        $new_contact->mailing_name = $request->mailing_name;
        $new_contact->gender = $request->gender;
        $new_contact->mailing_phone = $request->mailing_phone;
        $new_contact->mailing_email = $request->mailing_email;
        $new_contact->institution=$request->institution;
        $new_contact->ace=$request->ace;
        $new_contact->country=$request->country;
        $new_contact->thematic_field=$request->thematic_field;
        $new_contact->new_contact=$request->new_contact;

        $contact_saved =  $new_contact->save();

        if($contact_saved) {
            foreach ($aces as $ace){
                DB::table('ace_contacts')->insert(
                    ['ace_id'=>$ace->id,"contact_id"=>$new_contact->id]
                );
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

        $type = $role->position_type;
        return $type;
    }


    public static function getCountryName($id){
        $country = DB::table('countries')->where('id',$id)->first();
        return $country->country;
    }
    public static function getInstitutionName($id){
        $institution = DB::table('institutions')->where('id',$id)->first();
        return $institution->name;
    }
    public static function getAceName($id){
        $ace = DB::table('aces')->where('id',$id)->first();
        return $ace->name;
    }

    public function edit_view(Request $request){

        $id = Crypt::decrypt($request->id);

        $contacts =  Contacts::find($id);


        $all_contacts =DB::table('contacts')
            ->join('positions','positions.id','=','contacts.position_id')
            ->select ('contacts.id as contact_id')
            ->select('positions.*','contacts.*')
            ->orderBy('positions.rank','ASC')
            ->get();
        $countries = Country::all();
        $aces = Ace::all();
        $roles = Position::all();
        $ace_roles = Position::where('position_type','=','ACE level')->get();
        $institutions = Institution::all();
        $view = view('contacts.edit_view', compact('contacts','all_contacts','ace_roles','roles','aces','institutions','countries'))->render();

        return response()->json(['theView' => $view]);
    }


    public function update_contact(Request $request,$id)
    {

        $this->validate($request,[
            'role' => 'required|string|min:1',
            'second_role' => 'nullable|integer|min:1',
            'institution' => 'nullable|integer|min:1',
            'thematic_field' => 'nullable|string|min:1',
            'country' => 'nullable|integer|min:1',
            'ace' => 'nullable|integer|min:1',
            'mailing_name' => 'required|string|min:1',
            'gender' => 'required|string|min:1',
            'mailing_phone' => 'string|min:10',
            'mailing_email' => 'required|string|min:1',
            'new_contact' => 'required|min:1'

        ]);
        $institution = $request->institution;
        $country = $request->country;
        $thematic_field = $request->thematic_field;
        $ace = $request->ace;

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
        if(isset($ace)){
            $aces = Ace::find($ace)->get();
        }
        $this_contact = Contacts::find($id);
        $contact_update = $this_contact->Update([
            'position_id' =>$request->role,
            'person_title' =>$request->person_title,
            'second_role_id'=>$request->second_role,
            'mailing_name' =>$request->mailing_name,
            'gender' => $request->gender,
            'mailing_phone' => $request->mailing_phone,
            'mailing_email' => $request->mailing_email,
            'institution'=>$request->institution,
            'ace'=>$request->ace,
            'country'=>$request->country,
            'thematic_field'=>$request->thematic_field,
            'new_contact'=>$request->new_contact,

        ]);
        if ($contact_update) {
            $acecontacts = AceContact::where('contact_id','=',$this_contact->id)->get();
            foreach ($acecontacts as $ac) {
                foreach ($aces as $ace){
                    $ac->Update([
                        'ace_id'=>$ace->id,
                        "contact_id"=>$request->contact_id,
                    ]);
                }
            }

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



        $new_contact = new Contacts();
        $new_contact->contact_name = $request->mailing_name;
        $new_contact->position_id =$request->mailing_title;
        $new_contact->contact_phone = $request->mailing_phone;
        $new_contact->email = $request->mailing_email;
        $new_contact->contact_status=1;

        $saved =  $new_contact->save();

        if($saved){

            $data=array('ace_id'=>$request->ace_id,"contact_id"=>$new_contact->id);
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

        $this_contact = Contacts::find($id);

        $this->validate($request,[
            'ace_id' => 'required|string|min:1',
            'mailing_name' => 'required|string|min:1',
            'mailing_title' => 'required|string|min:1',
            'mailing_phone' => 'string|min:10',
            'mailing_email' => 'required|string|min:1',
        ]);

        $updated= $this_contact->Update([
            'contact_name' => $request->mailing_name,
            'position_id' => $request->mailing_title,
            'contact_phone' => $request->mailing_phone,
            'edit_status' =>true,
            'email' => $request->mailing_email
        ]);

        if($updated){
            $acecontacts = AceContact::where('contact_id','=',$this_contact->id)->get();
            foreach ($acecontacts as $ac) {
                $ac->Update([
                    'ace_id'=>$request->ace_id,
                    "contact_id"=>$request->contact_id,
                ]);
            }
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



//    bulk uploads using excel form
    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save_bulk_contacts(Request $request)
    {

        $this->validate($request, [
            'upload_file' => 'required|file|mimes:xls,xlsx',
        ]);


        $file_one=$request->upload_file;
        $destinationPath = base_path() . '/public/Contacts/';
        $thefile_one = "";

        $file1 = $request->file('upload_file');

//        dd($file1);

        if (isset($file1)) {
            $extracted = $this->extractMembers($file1);
            if ($extracted) {
                $file1->move($destinationPath, $file1->getClientOriginalName());
                $thefile_one = $file_one->getClientOriginalName();
                notify(new ToastNotification('Successful!', 'Contacts File Added Successfully', 'success'));
                return back();
            }else{
                notify(new ToastNotification('Notice', 'An error occured extracting data- Please check the format and try again.', 'info'));
                return back();
            }
        }
    }

    public function downloadTemplate(){
        $destinationPath = base_path() . '/public/Contacts/Template/contacts_templates.xlsx';
        $downloaded = Response::download($destinationPath);

        if(!$downloaded){
            notify(new ToastNotification('Sorry!', 'File does not exist!', 'error'));
        }else {
            return $downloaded;
            notify(new ToastNotification('Successful!', 'Download successful!', 'success'));
        }

    }

    public function extractMembers($file){
        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range( 2, $row_limit );
            $column_range = range( 'J', $column_limit );
            $startcount = 2;


            foreach ( $row_range as $row ) {
                $institution=null;
                $country = null;
                $thematic_field = null;
                $ace = null;
                $aces_list = null;
                $ace_sheet = $sheet->getCell( 'I' . $row )->getValue();
                $institution_sheet = $sheet->getCell( 'H' . $row )->getValue();
                $country_sheet = $sheet->getCell( 'J' . $row )->getValue();
                $thematic_sheet = $sheet->getCell( 'K' . $row )->getValue();

                $new_contact = 1;
                $new =$sheet->getCell( 'L' . $row )->getValue();


                if($new =='no' || $new == 'No' || $new == 'NO'){
                    $new_contact = 0;
                }



                if($ace_sheet != null){
                    $the_ace=DB::table('aces')
                        ->where('name','=',$ace_sheet)
                        ->orWhere('acronym','=',$ace_sheet)
                        ->orWhere('name','like','%$ace_sheet')
                        ->first();
                    if ($the_ace) {
                        $ace= $the_ace->id;
                        $aces_list = Ace::find($ace)->get();
                    }

                }

                if($institution_sheet != null){
                    $the_institution=DB::table('institutions')
                        ->where('name','=',$institution_sheet)
                        ->orWhere('name','like','%$institution_sheet%')
                        ->orWhere('name','like','%$institution_sheet')
                        ->first();
                    if ($the_institution) {
                        $institution = $the_institution->id;
                        $aces_list= Ace::where('institution_id','=',$institution)->get();
                    }
                }

                if($country_sheet !=Null){
                    $the_country=DB::table('countries')
                        ->where('country','=',$country_sheet)
                        ->orWhere('country','like','%$country_sheet%')
                        ->orWhere('country','like','%$country_sheet')
                        ->first();
                    if ($the_country) {
                        $country = $the_country->id;
                        $aces_list= DB::table('aces')->join('institutions', 'aces.institution_id', '=', 'institutions.id')
                            ->join('countries', 'institutions.country_id', '=', 'countries.id')
                            ->where('institutions.country_id', '=', $country)
                            ->distinct('countries.id')
                            ->select('aces.*')
                            ->get();
                    }

                }

                if($thematic_sheet !=Null){
                    $aces_list = Ace::where('field','=',$thematic_sheet)->get();
                    $thematic_field=$thematic_sheet;
                }
                $position_name = $sheet->getCell( 'A' . $row )->getValue();

                $position = DB::table('positions')->where('position_title','=',$position_name)
                    ->orWhere('position_title','like','%$position_name')
                    ->orWhere('position_title','like','%$position_name%')
                    ->orWhere('position_title','like','$position_name%')
                    ->first();

                if ($position) {
                    $position_id = $position->id;
                } else {
                    continue;
                }

                $data[] = [
                    'position_id' =>$position_id,
                    'second_role_id' => $sheet->getCell( 'B' . $row )->getValue(),
                    'person_title' => $sheet->getCell( 'C' . $row )->getValue(),
                    'mailing_name' => $sheet->getCell( 'D' . $row )->getValue(),
                    'gender' => $sheet->getCell( 'E' . $row )->getValue(),
                    'mailing_phone' =>$sheet->getCell( 'F' . $row )->getValue(),
                    'mailing_email' => $sheet->getCell( 'G' . $row )->getValue(),
                    'institution' => $institution,
                    'ace' => $ace,
                    'country' => $country,
                    'thematic_field' => $thematic_field,
                    'new_contact' => $new_contact
                ];
                $startcount++;
            }
            if ($startcount <= 2){
                notify(new ToastNotification('Sorry', 'No data has been uploaded. Please check your data',
                    'warning'));
            }
            // Unique data without duplicates
            $unique = array_unique($data, SORT_REGULAR);


            DB::table('contacts')->insert($unique);


        } catch (Exception $e) {
            $error_code = $e->errorInfo[1];
            return false;
        }

        return true;
    }




}
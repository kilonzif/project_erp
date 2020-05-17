<?php

namespace App\Http\Controllers;

use App\Ace;
use App\Classes\ToastNotification;
use App\Contacts;
use App\Country;
use App\FileUploads;
use App\Institution;
use App\Report;
use Faker\Provider\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FileUploadsController extends Controller
{
    public function __construct() {
        $this->middleware( 'auth' );

    }
    public function index(){
        $aces = Ace::all();
        $is_ace = false;
        $uploads = FileUploads::where('user_id','=',Auth::id())->get();
        if (Auth::user()->hasRole(['webmaster', 'admin', 'super-admin'])){
            $uploads = FileUploads::all();
        } elseif(Auth::user()->hasRole(['ace-officer'])) {
            $uploads = FileUploads::where('ace_id','=',Auth::user()->ace)->get();
            $is_ace = true;
        }
        $ace = Ace::find(Auth::user()->ace);

        return view('fileuploads.index',compact('aces','uploads','is_ace','ace'));
    }

    public function getAceName($id){
        $ace_name = Ace::where('id','=',$id)->pluck('acronym');
        return $ace_name;
    }

    public function saveUploads(Request $request){

        if (Auth::user()->ace || isset($request->ace)) {
            $ace_id = Auth::user()->ace;
            $acronym = strtoupper(Auth::user()->ace_->acronym);
        }
        else {
            $acronym = Auth::id();
            $ace_id = $request->ace_id;
        }

        $directory = "public/additional-files/$acronym";
        $comments = $request->comments;
        $category=$request->file_category;
        $file1_name = $file2_name = $file_one_path = $file_two_path = null;
        $files_array =[];

        if ($request->file('file_one')) {
            $file_one= $request->file_one;
            $files_array['file_one'] =  $request->file('file_one');
            $file1_name = $file_one->getClientOriginalName();
            $file_one_path = "$directory/$file1_name";
        }

        if ($request->file('file_two')) {
            $satisfactory_survey_file= $request->file_two;
            $files_array['file_two'] =  $request->file('file_two');
            $file2_name = $satisfactory_survey_file->getClientOriginalName();
            $file_two_path = "$directory/$file2_name";
        }

        $saveUpload = FileUploads::create(
            [   'ace_id' => $ace_id,
                'user_id' => Auth::id(),
                'file_one'=>$file1_name,
                'file_two'=>$file2_name,
                'comments' =>$comments,
                'file_one_path' =>$file_one_path,
                'file_two_path' =>$file_two_path,
                'status'=>1,
                'file_category' => $category
            ]
        );


        if (isset($saveUpload)) {
            foreach ($files_array as $key=>$value){
                Storage::putFileAs("$directory", $value, $value->getClientOriginalName());
            }
            notify(new ToastNotification('Successful!', 'Files Added', 'success'));
            return back();
        } else {
            notify(new ToastNotification('Notice', 'Something might have happened. Please try again.', 'info'));
            return back();
        }

    }


    public function destroyUpload(Request $request,$id){
        $upload = FileUploads::find(Crypt::decrypt($id));

        $upload->delete();
        notify(new ToastNotification('Successful!', 'Uploaded Files Deleted!', 'success'));

        return back();
    }




}
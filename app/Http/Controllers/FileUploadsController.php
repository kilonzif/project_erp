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
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class FileUploadsController extends Controller
{
    public function __construct() {
        $this->middleware( 'auth' );

    }
    public function index(){
        $aces = Ace::all();

        $uploads = FileUploads::all();

        return view('fileuploads.index',compact('aces','uploads'));
    }

    public function getAceName($id){
        $ace_name = Ace::where('id','=',$id)->pluck('acronym');
        return $ace_name;
    }

    public function saveUploads(Request $request){

        $ace_id = $request->ace_id;
        $file1=$request->file_one;
        $file2=$request->file_two;
        $destinationPath = base_path() . '/public/AdditionalFiles/';
        $the_file1 = $request->file('file_one');
        $the_file2 = $request->file('file_two');
        $comments = $request->comments;
        $category=$request->file_category;
        $file2_name=null;


        if (isset($the_file1)) {
            $file1->move($destinationPath, $file1->getClientOriginalName());
            $file1_name= $file1->getClientOriginalName();
        }
        if (isset($the_file2)) {
            $file2->move($destinationPath, $file2->getClientOriginalName());
            $file2_name=$file2->getClientOriginalName();
        }

//        dd($request->comments);

        $saveUpload = FileUploads::updateOrCreate(
            ['ace_id' => $ace_id,
                'file_one'=>$file1_name,
                'file_two'=>$file2_name,
                'comments' =>$request->comments,
                'status'=>1,
                'file_category' => $category
            ]
        );
//        $saveUpload->comments = $request->comments;


        if (isset($saveUpload)) {
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
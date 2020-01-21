<?php

namespace App\Http\Controllers;
use App\Classes\ToastNotification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Indicator;
use App\ExcelUpload;
use ZipArchive;

class ExcelUploadController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }
    public function index(){
        $indicators = Indicator::where('is_parent','=', 1)
            ->where('status','=', 1)
            ->where('upload','=', 1)
            ->where('parent_id','=',0)
            ->orderBy('identifier','asc')->get();
        $exceluploads=ExcelUpload::orderBy('indicator_id','asc')->get();
        return view('settings.exceluploads',compact('indicators','exceluploads'));
    }


    public function save(Request $request)
    {
        $this->validate($request, [
            'upload_file' => 'required|file|mimes:xls,xlsx',
            'indicator_id' => 'required'
        ]);

        $upload_file = $request->upload_file;
        $upload_file_new_name = time().$upload_file ->getClientOriginalName();
        $upload_file->move('uploads/docs', $upload_file_new_name);

        $file = ExcelUpload::where('indicator_id','=',$request->indicator_id)->first();
        if ($file){
            notify(new ToastNotification('Notice',
                'This indicator already has an uploaded template. Please delete the existing upload and re-upload.',
                'warning'));
            return redirect()->back();
        }

        ExcelUpload::create([
            'upload_file' => 'uploads/docs/'.$upload_file_new_name,
            'indicator_id' => $request->indicator_id
        ]);
        $success = "File Uploaded.";
        notify(new ToastNotification('Successful', $success, 'success'));
        return redirect()->back();
    }






    public function   delete($id)
    {
        $excelupload_id = Crypt::decrypt($id);
        ExcelUpload::destroy($excelupload_id);

        notify(new ToastNotification('Successful!', '  upload deleted!', 'success'));
        return redirect()->back();
    }




    public function download($id)
    {
        $excelupload_id= Crypt::decrypt($id);

        $xlsx=ExcelUpload::find($excelupload_id);

        if($xlsx){
            $file = response()->download($xlsx->upload_file);
            if(!$file){
                notify(new ToastNotification('Sorry!', 'File does not exist!', 'error'));
            }
            return $file;
            notify(new ToastNotification('Successful!', 'Download successful!', 'success'));
        }
        else{
            notify(new ToastNotification('Sorry!', 'File does not exist!', 'error'));
        }
        return redirect()->back();
    }


    public function downloadAll(){
        $indicators = Indicator::where('is_parent','=', 1)
            ->where('status','=', 1)
            ->where('upload','=', 1)
            ->orderBy('identifier','asc')->get();
        $indicator_array = array();
        foreach($indicators as $indicator) {
            if ($indicator->IsUploadable($indicator->id)) {
                $excel_upload = \App\ExcelUpload::where('indicator_id', '=', (integer)$indicator->id)->get();
                foreach ($excel_upload as $var) {
                    $indicator_array [] = $var->id;
                }

            }
        }
        $zip = new ZipArchive;

        $zipped_file = 'indicatorTemplates.zip';
        foreach ($indicator_array as $item){
            $xlsx=ExcelUpload::find($item);
            if ($zip->open(public_path($zipped_file), ZipArchive::CREATE) === TRUE) {
                $zip->addFile($xlsx->upload_file);
            }

        }
        $zip->close();
        $file = response()->download(public_path($zipped_file));
        if($file){
            notify(new ToastNotification('Successful!', 'Download successful!', 'success'));
            return $file;
        }else{
            notify(new ToastNotification('Sorry!', 'File does not exist!', 'error'));
        }

        return redirect()->back();

    }








}

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
            'indicator_id' => 'required',
            'language' =>'required'
        ]);

        $upload_file = $request->upload_file;
        $upload_file_new_name = time().$upload_file ->getClientOriginalName();
        $upload_file->move('uploads/docs', $upload_file_new_name);
        $file = ExcelUpload::where('indicator_id','=',$request->indicator_id)->where('language','=',$request->language)->first();
        if ($file){
            notify(new ToastNotification('Notice',
                'This indicator already has an uploaded template. Please delete the existing upload and re-upload.',
                'warning'));
            return redirect()->back();
        }

        ExcelUpload::create([
            'upload_file' => 'uploads/docs/'.$upload_file_new_name,
            'language' => $request->language,
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
    public function downloadEn($id)
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
    public function downloadFr($id)
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


    public function downloadAllEnglish(Request $request){
        $indicators = Indicator::where('is_parent','=', 1)
            ->where('status','=', 1)
            ->where('upload','=', 1)
            ->orderBy('identifier','asc')->get();
        $eng_indicator_array = array();
        foreach($indicators as $indicator) {
            if ($indicator->IsUploadable($indicator->id)) {
                $eng_excel_upload = \App\ExcelUpload::where('indicator_id', '=', (integer)$indicator->id)->where('language','=','english')->get();
                foreach ($eng_excel_upload as $var) {
                    $eng_indicator_array [] = $var->id;
                }

            }
        }
        $zip = new ZipArchive;

        $zipped_file = 'English_indicatorTemplates.zip';
        foreach ($eng_indicator_array as $item){
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
    public function downloadAllFrench(Request $request){
        $indicators = Indicator::where('is_parent','=', 1)
            ->where('status','=', 1)
            ->where('upload','=', 1)
            ->orderBy('identifier','asc')->get();
        $fr_indicator_array = array();
        foreach($indicators as $indicator) {
            if ($indicator->IsUploadable($indicator->id)) {
                $fr_excel_upload = \App\ExcelUpload::where('indicator_id', '=', (integer)$indicator->id)->where('language','=','french')->get();
                foreach ($fr_excel_upload as $var) {
                    $fr_indicator_array [] = $var->id;
                }

            }
        }
        $zip = new ZipArchive;

        $zipped_file = 'French_indicatorTemplates.zip';
        foreach ($fr_indicator_array as $item){
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

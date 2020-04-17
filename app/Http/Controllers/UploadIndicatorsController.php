<?php

namespace App\Http\Controllers;

use App\Classes\ToastNotification;
use App\ExcelUpload;
use App\Indicator;
use App\IndicatorDetails;
use App\IndicatorForm;
use App\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Collection;
use phpDocumentor\Reflection\Types\Integer;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UploadIndicatorsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($report_id)
    {
        $d_report_id = Crypt::decrypt($report_id);
        $indicator_details = IndicatorDetails::where('report_id','=',$d_report_id)->get();
        $report = Report::find($d_report_id);
        
        if ($report->editable <= 0 && Auth::user()->hasRole('ace-officer')){
            notify(new ToastNotification('Sorry!', 'This report is unavailable for editing!', 'warning'));
            return back();
        }
        $ace = $report->ace;

        $indicator_type= Indicator::where('id','=',$report->indicator_id)->where('upload','=',1)->first();


        if(empty($indicator_type)){
            $indicators = Indicator::where('is_parent','=', 1)
            ->where('id','=',$report->indicator_id)
            ->where('status','=', 1)
            ->orderBy('identifier','asc')->first();
            $table_name = Str::snake("indicator_".$indicators->identifier);

            $data = DB::connection('mongodb')
                ->collection("$table_name")
                ->where('report_id','=', (integer)$d_report_id)->get();
            if($report->language=="french" && $indicators->identifier =='4.1' ){
                return view('report-form.dlr41fr-webform', compact('indicators','indicator_type','data','d_report_id','report_id','indicator_details','report','ace'));
            }

            return view('report-form.dlr-webform', compact('indicators','indicator_type','data','d_report_id','report_id','indicator_details','report','ace'));
        }
        $indicators = Indicator::where('is_parent','=', 1)
            ->where('id','=',$report->indicator_id)
            ->where('status','=', 1)
            ->where('upload','=', 1)
            ->orderBy('identifier','asc')->get();
        return view('report-form.uploads', compact('indicators','d_report_id','report_id','indicator_details','report','ace'));
    }

    public function downloadIndicators()
    {
        $indicators = Indicator::where('is_parent','=', 1)
            ->where('status','=', 1)
            ->where('upload','=', 1)
            ->orderBy('identifier','asc')->get();

        return view('report-form.download_indicators', compact('indicators'));
    }

    public function read($detail_id)
    {
        $indicator_details = IndicatorDetails::find($detail_id);
        $getHeaders = DB::connection('mongodb')->collection('indicator_form')->where('indicator','=',$indicator_details->indicator_id)->pluck('fields');

        $headers = array();

        for ($a = 0; $a < sizeof($getHeaders[0]); $a++){
            $headers[] = $getHeaders[0][$a]['label'];
        }

        for ($a = 0; $a < sizeof($getHeaders[0]); $a++){
            $slugs[] = $getHeaders[0][$a]['slug'];
        }
        $indicator = Indicator::find($indicator_details->indicator_id);
        return view('report-form.read-details', compact('indicator','indicator_details','headers','slugs'));
    }

    public function getFields(Request $request)
    {

        $getHeaders = IndicatorForm::query()
            ->where('indicator','=',(integer)$request->id)
            ->orderBy('order','asc')->get();


        $maindata = collect($getHeaders)->filter(function ($query) use($request){
            return in_array($request->language,collect($query)->get('language'));
        })->pluck('fields')->toArray();

        $data=$maindata;


        $excel_upload = ExcelUpload::where('indicator_id','=',(integer)$request->id)->where('language','=',$request->language)->first();
        $theView = view('report-form.field-list', compact('data','excel_upload'))->render();

        return response()->json(['theView'=>$theView]);
    }

    public function excelUpload(Request $request)
    {
        $this->validate($request,[
            'report_id'=>'required|string|min:100',
            'indicator'=>'required|numeric|min:1',
            'language'=>'required|string|min:1',
            'upload_file'=>'required|file|mimes:xls,xlsx',
        ]);

        $indicator_info = Indicator::find($request->indicator);

        $getHeaders = IndicatorForm::query()
            ->where('indicator','=',(integer)$request->indicator)
            ->orderBy('order','asc')->get();

//        dd($getHeaders);
        //Get the start row of data inputs for the upload
        $data_start = collect($getHeaders)->filter(function ($query) use($request){
            return in_array($request->language,collect($query)->get('language'));
        })
            ->pluck('start_row')->first();


        //Assign 3 which is row 3 if no data is found
        if ($data_start == null){
            $data_start = 3;//The row number the data input starts
        }
        $upload_values = array(); //An array to holds the upload cells values
        $upload_values['report_id'] = (integer)Crypt::decrypt($request->report_id);
        $upload_values['indicator_id'] = (integer)$request->indicator;
        $upload_values['language'] = $request->language;
        $upload_values['created_at'] = date('Y-m-d H:i:s');
        $upload_values['updated_at'] = date('Y-m-d H:i:s');

        $indicator_details = array(); //An array to holds the indicator details
        $report_id = (integer)Crypt::decrypt($request->report_id);
        $indicator_details['report_id'] = (integer)$report_id;

        //row headers
        $headers = array();
        $error = $success = "";

        $getIndicator = IndicatorForm::query()
            ->where('indicator','=',(integer)$request->indicator)
            ->orderBy('order','asc')->get();

        $getHeaders = collect($getIndicator)->filter(function ($query) use($request){
            return in_array($request->language,collect($query)->get('language'));
        })->pluck('fields')->toArray();

        if (sizeof($getHeaders) < 1){
            notify(new ToastNotification('Sorry!','This indicator is not available for uploads yet.','info'));
            return back();
        }

        for ($a = 0; $a < sizeof($getHeaders[0]); $a++){
            $headers[] = $getHeaders[0][$a]['slug'];
        }

        if ($request->file('upload_file')->isValid()) {


            $extension = \File::extension($request->upload_file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls") {
                $path = $request->file('upload_file')->getRealPath();

                $reader = IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($path);
                $worksheet = $spreadsheet->getActiveSheet();

                // Get the highest row and column numbers referenced in the worksheet
                $highestRow = $worksheet->getHighestRow(); // e.g. 10
                $highestRow = (integer)$highestRow; // e.g. 10

                $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'

                $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); // e.g. 5
                $highestColumnIndex = (integer)$highestColumnIndex; // e.g. 5

                //Checks if the total columns equals the total columns required
                if ($highestColumnIndex < sizeof($headers)){
                    $error = "There is a mismatch in the fields required for this indicator or the total number of fields 
                    for this indicator is not equal to that of the uploaded file.";

                }

                $table_name = Str::snake("indicator_".$indicator_info->identifier);

//                dd($table_name);

                //Loops through the excel sheet to get the values;
                DB::connection('mongodb')->collection("$table_name")->where('report_id',$report_id)->delete();






                for ($row = $data_start; $row <= $highestRow; $row++) {



                    echo PHP_EOL;

                    for ($col = 1; $col < $highestColumnIndex; $col++) {
                        $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();

                        $line = $row - $data_start;
                        $upload_values['data'][$line][$headers[$col-1]] = $value;
                        $indicator_details[$headers[$col-1]] = $value;
                        echo  PHP_EOL;
                    }

                    DB::connection('mongodb')->collection("$table_name")->insert($indicator_details);

                    echo PHP_EOL;
                }//end of loop

                echo PHP_EOL;
                $row = DB::connection('mongodb')
                    ->collection('indicator_form_details')
                    ->where('report_id','=', $upload_values['report_id'])->where('indicator_id','=', $upload_values['indicator_id'])->first();
                $item = (object)$row;

                if ($row){
                    DB::connection('mongodb')
                        ->collection('indicator_form_details')
                        ->where('_id', $item->_id)
                        ->update($upload_values);
                    $success = "The upload was successful.";
                }
                else{
                    $insert = DB::connection('mongodb')
                        ->collection('indicator_form_details')
                        ->insert($upload_values);

                    if ($insert){
                        $success = "The upload was successful.";
                    }else{
                        $error = "The upload failed.";
                    }
                }
            }else{
                $error = "The upload supports only xlsx or xls files!";
            }
        }


        $d_report_id = Crypt::decrypt($request->report_id);
        $indicator_details = IndicatorDetails::where('report_id','=',$d_report_id)->get();



            return view ('report-form.uploaded-dlrs',compact('indicator_details'));
//        return back()->withInput(['error'=>$error,'success'=>$success]);
    }



    public function saveWebForm(Request $request,$dlr_id){

        $this->validate($request, [
            'programmetitle' => 'required|string|unique:mongodb.indicator_form_details,programmetitle',
//            'level' => 'required|string',
//            'typeofaccreditation' => 'nullable|string',
//            "accreditationreference" => "required|string|min:1",
//            "accreditationagency" => "required|string",
//            'agencyname' => 'required|string|min:100',
//            'agencyemail' => 'required|email',
//            'agencycontact' => 'nullable|string|date',
//            "dateofaccreditation" => "required|date|min:1",
//            "exp_accreditationdate" => "required|date",
        ]);


        $this_dlr = Indicator::where('id','=',$request->indicator_id)->first();
        $table_name = Str::snake("indicator_".$this_dlr->identifier);
        $report = Report::where('id','=',$request->report_id)->first();


        $upload_values = array(); //An array to holds the upload cells values
        $upload_values['report_id'] = (integer)($request->report_id);
        $upload_values['indicator_id'] = (integer)$request->indicator_id;
        $upload_values['language'] = $report->language;
        $upload_values['created_at'] = date('Y-m-d H:i:s');
        $upload_values['updated_at'] = date('Y-m-d H:i:s');


        $row = DB::connection('mongodb')
            ->collection('indicator_form_details')
            ->where('report_id','=', $upload_values['report_id'])->where('indicator_id','=', $upload_values['indicator_id'])->first();
        $item = (object)$row;


        if ($row) {
            DB::connection('mongodb')
                ->collection('indicator_form_details')
                ->where('_id', $item->_id)
                ->update($upload_values);
        }else {

            $insert = DB::connection('mongodb')
                ->collection('indicator_form_details')
                ->insert($upload_values);
        }

        $indicator_details = array(); //An array to holds the indicator details
        $report_id = (integer)($request->report_id);
        $indicator_details['report_id'] = (integer)$report_id;
        $indicator_details['indicator_id'] = $request->indicator_id;
        $indicator_details['programmetitle'] = $request->programmetitle;
        $indicator_details['level'] = $request->level;
        $indicator_details['typeofaccreditation'] = $request->typeofaccreditation;
        $indicator_details['accreditationreference'] = $request->accreditationreference;
        $indicator_details['accreditationagency'] = $request->accreditationagency;
        $indicator_details['agencyname'] = $request->agencyname;
        $indicator_details['agencyemail'] = $request->agencyemail;
        $indicator_details['agencycontact'] = $request->agencycontact;
        $indicator_details['dateofaccreditation'] = $request->dateofaccreditation;
        $indicator_details['exp_accreditationdate'] = $request->exp_accreditationdate;


        $saved= DB::connection('mongodb')->collection("$table_name")->insert($indicator_details);

        if(!$saved){
            $error_msg = "There was an error uploading the data";
            notify(new ToastNotification('error', $error_msg, 'warning'));
            return back()->withInput();
        }else if($saved) {
            DB::connection('mongodb')
                ->collection('indicator_form_details')
                ->where('report_id', $report_id)
                ->update($indicator_details);
            $success = "The upload was successful.";
            notify(new ToastNotification('Successful', $success, 'success'));
            return back();


        }

    }


//    public function insertIntoDb(){
//        $table_name = Str::snake("indicator_".$indicator_info->identifier);
////                dd($table_name);
//
//        //Loops through the excel sheet to get the values;
//        DB::connection('mongodb')->collection("$table_name")->where('report_id',$report_id)->delete();
//        for ($row = $data_start; $row <= $highestRow; $row++) {
//
//            echo PHP_EOL;
//
//            for ($col = 1; $col < $highestColumnIndex; $col++) {
//                $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
//                $line = $row - $data_start;
////                        dd($headers);
//                $upload_values['data'][$line][$headers[$col-1]] = $value;
//
//                $indicator_details[$headers[$col-1]] = $value;
//                echo  PHP_EOL;
//            }
//            DB::connection('mongodb')->collection("$table_name")->insert($indicator_details);
//
//            echo PHP_EOL;
//        }//end of loop
//
//        echo PHP_EOL;
//        $row = DB::connection('mongodb')
//            ->collection('indicator_form_details')
//            ->where('report_id','=', $upload_values['report_id'])->where('indicator_id','=', $upload_values['indicator_id'])->first();
//        $item = (object)$row;
//
//        if ($row){
//            DB::connection('mongodb')
//                ->collection('indicator_form_details')
//                ->where('_id', $item->_id)
//                ->update($upload_values);
//
//            $success = "The upload was successful.";
//            notify(new ToastNotification('Successful', $success, 'success'));
//            return back();
//        }
//        else{
//            $insert = DB::connection('mongodb')
//                ->collection('indicator_form_details')
//                ->insert($upload_values);
//
//            if ($insert){
//                $success = "The upload was successful.";
//                notify(new ToastNotification('Successful', $success, 'success'));
//                return back();
//            }else{
//                $error = "The upload failed.";
//                notify(new ToastNotification('Upload Error!', $error, 'warning'));
//                return back();
//            }
//        }
//    }



}





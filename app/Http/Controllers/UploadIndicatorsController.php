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
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UploadIndicatorsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($report_id,$indicatorID=null)
    {
        $d_report_id = Crypt::decrypt($report_id);
        $indicator_details = IndicatorDetails::where('report_id','=',$d_report_id)->get();
        $report = Report::find($d_report_id);
        if ($report->editable <= 0 && Auth::user()->hasRole('ace-officer')){
            notify(new ToastNotification('Sorry!', 'This report is unavailable for editing!', 'warning'));
            return back();
        }
        $ace = $report->ace;
        $indicators = Indicator::where('parent_id','=', 0)->get();
        return view('report-form.uploads', compact('indicators','indicator_id','d_report_id','report_id','indicator_details','indicatorID','ace'));
    }

    public function downloadIndicators()
    {
        $indicators = Indicator::where('parent_id','=', 0)->get();
        return view('report-form.download_indicators', compact('indicators'));
    }

    public function read($detail_id)
    {
//        $d_report_id = Crypt::decrypt($report_id);
//        $indicator_details = DB::connection('mongodb')->collection('indicator_form_details')->find($detail_id);
        $indicator_details = IndicatorDetails::find($detail_id);
//        dd($indicator_details->data);
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
        //row headers
//        $headers = array();
        $getHeaders = DB::connection('mongodb')->collection('indicator_form')
            ->where('indicator','=',$request->id)->pluck('fields');

//        if ($getHeaders){
//            for ($a = 0; $a < sizeof($getHeaders[0]); $a++){
//                $headers[] = $getHeaders[0][$a]['label'];
//            }
//            $message = "Successful";
//        }else{
//            $headers = [];
//        }

        $excel_upload = ExcelUpload::where('indicator_id','=',$request->id)->first();
        $theView = view('report-form.field-list', compact('getHeaders','excel_upload'))->render();
        return response()->json(['theView'=>$theView]);
    }

    public function excelUpload(Request $request)
    {
        $this->validate($request,[
            'report_id'=>'required|string|min:100',
            'indicator'=>'required|numeric|min:1',
            'upload_file'=>'required|file|mimes:xls,xlsx',
        ]);

        //Get the start row of data inputs for the upload
        $data_start = DB::connection('mongodb')
            ->collection('indicator_form')
            ->where('indicator','=',$request->indicator)
            ->pluck('start_row')->first();

        //Assign 3 which is row 3 if no data is found
        if ($data_start == null){
            $data_start = 3;//The row number the data input starts
        }
        $upload_values = array(); //An array to holds the upload cells values
        $upload_values['report_id'] = Crypt::decrypt($request->report_id);
        $upload_values['indicator_id'] = $request->indicator;
        $upload_values['created_at'] = date('Y-m-d H:i:s');
        $upload_values['updated_at'] = date('Y-m-d H:i:s');

        //row headers
        $headers = array();
        $error = $success = "";
        $getHeaders = DB::connection('mongodb')
            ->collection('indicator_form')
            ->where('indicator','=',$request->indicator)
            ->pluck('fields');
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
                $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
                $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); // e.g. 5

                //Checks if the total columns equals the total columns required
                if ($highestColumnIndex <> sizeof($headers)){
                    $error = "There is a mismatch in the fields required for this indicator or the total number of fields for this indicator is not equal to that of the uploaded file.";
                    notify(new ToastNotification('Upload Error!', $error, 'warning'));
                    return back();
                }

                //Loops through the excel sheet to get the values;
                for ($row = $data_start; $row <= $highestRow; ++$row) {

                    echo PHP_EOL;

                    for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                        $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
//                        if ($value == null or $value == " "){
//                            break;
//                        }
                        $line = $row - $data_start;
                        $upload_values['data'][$line][$headers[$col-1]] = $value;
                        echo  PHP_EOL;
                    }

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
                    notify(new ToastNotification('Successful', $success, 'success'));
                    return back();
                }
                else{
                    $insert = DB::connection('mongodb')
                        ->collection('indicator_form_details')
                        ->insert($upload_values);
                    if ($insert){
                        $success = "The upload was successful.";
                        notify(new ToastNotification('Successful', $success, 'success'));
                        return back();
                    }else{
                        $error = "The upload failed.";
                        notify(new ToastNotification('Upload Error!', $error, 'warning'));
                        return back();
                    }
                }
            }else{
                $error = "The upload supports only xlsx or xls files!";
                notify(new ToastNotification('Upload Error!', $error, 'warning'));
            }
        }
        return back()->withInput(['error'=>$error,'success'=>$success]);
    }
}





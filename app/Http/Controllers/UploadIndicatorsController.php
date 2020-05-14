<?php

namespace App\Http\Controllers;

use App\Classes\CommonFunctions;
use App\Classes\ToastNotification;
use App\ExcelUpload;
use App\Indicator;
use App\IndicatorDetails;
use App\IndicatorForm;
use App\Report;
use App\ReportUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Array_;
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
        $select_language = new CommonFunctions();
        $lang = $select_language->webFormLang($report->language);

        $currency_list = DB::table('currency_list')->get();
        $ace = $report->ace;
        $ace_programmes = explode(';',$ace->programmes);

        /**
         * Gather the file name and directory path to retrieve saved documents
         * **/
        $acronym = strtoupper($report->ace->acronym);
        $reporting_year = $report->reporting_period->first()->reporting_year;
        $identifier = "dlr_".str_replace('.','_',$report->indicator->identifier);
        $directory = "public/reports/$acronym/$reporting_year/$identifier";


        /**
         * Checks if the DLR requires an upload.
         */
        $indicator_type = Indicator::where('id','=',$report->indicator_id)->where('upload','=',1)->first();
        $indicator_info = Indicator::find($report->indicator_id);

        /**
         * If does not require an upload then it's a web-form
         */
        if(empty($indicator_type)){
            $indicators = Indicator::where('is_parent','=', 1)
                ->where('id','=',$report->indicator_id)
                ->where('status','=', 1)
                ->orderBy('identifier','asc')->first();
            $table_name = Str::snake("indicator_".$indicators->identifier);
            $the_record = null;

            $data = DB::connection('mongodb')
                ->collection("$table_name")
                ->where('report_id','=', (integer)$d_report_id)->get();

            if (!$indicator_info->upload) {
                $view_name = '';
                if (isset($indicator_info->webForm)) {
                    $view_name = $indicator_info->webForm->view_name;
                    $table_name = $indicator_info->webForm->table_name;
                    $data = DB::table("$table_name")
                        ->where('report_id','=', (integer)$d_report_id)->get();
                }

                if($report->language=="french" && $indicators->identifier =='4.1' ){
                    return view('report-form.webforms.dlr41fr-webform', compact('indicators',
                        'indicator_type','data','d_report_id','report_id','indicator_details','report','ace'
                    ,'indicator_info','ace_programmes'));
                }
//                else if($report->language=="french" && $indicators->identifier =='5.1'){
//                    return view('report-form.webforms.dlr51fr-webform', compact('indicators',
//                        'indicator_type','data','d_report_id','report_id','indicator_details','report','ace'
//                        ,'indicator_info'));
//
//                }
                else if($report->language=="english" && $indicators->identifier =='4.1'){
                    return view('report-form.webforms.dlr41en-webform', compact('indicators',
                        'indicator_type','data','d_report_id','report_id','indicator_details','report','ace'
                        ,'indicator_info','ace_programmes'));

                }
//                else if($report->language=="english" && $indicators->identifier =='5.1'){
//                    return view('report-form.webforms.dlr51en-webform', compact('indicators',
//                        'indicator_type','data','d_report_id','report_id','indicator_details','report','ace'
//                        ,'indicator_info'));
//                }
                else if($report->language=="english" && $indicators->identifier =='7.3'){
                    return view('report-form.webforms.dlr73en-webform', compact('indicators',
                        'indicator_type','data','d_report_id','report_id','indicator_details','report','ace'
                        ,'indicator_info'));
                }
                else if($report->language=="french" && $indicators->identifier =='7.3'){
                    return view('report-form.webforms.dlr73fr-webform', compact('indicators',
                        'indicator_type','data','d_report_id','report_id','indicator_details','report','ace'
                        ,'indicator_info'));
                }
                else {
                    return view("report-form.webforms.$view_name", compact('indicator_type','currency_list','lang',
                        'data','d_report_id','report_id','indicator_details','report','ace','indicator_info','the_record','directory'));
                }
            }
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
        $getHeaders = DB::connection('mongodb')->collection('indicator_form')
            ->where('indicator','=',$indicator_details->indicator_id)
            ->where('language.Text','=',$indicator_details->language)
            ->pluck('fields');

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

        $start_row = collect($getHeaders)->filter(function ($query) use($request){
            return in_array($request->language,collect($query)->get('language'));
        })
            ->pluck('start_row')->first();

        $data=$maindata;


        $excel_upload = ExcelUpload::where('indicator_id','=',(integer)$request->id)->where('language','=',$request->language)->first();
        $theView = view('report-form.field-list', compact('data','excel_upload','start_row'))->render();

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
        $report = Report::find($report_id);
//        dd($report->reporting_period);

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

                //Gather the file name and directory path
                $acronym = strtoupper($report->ace->acronym);
                $reporting_year = $report->reporting_period->first()->reporting_year;
                $identifier = "dlr_".str_replace('.','_',$report->indicator->identifier);
                $directory = "public/reports/$acronym/$reporting_year/$identifier";
                $month = date('M',strtotime($report->reporting_period->first()->period_end));
                $file_name = "$acronym-$reporting_year-$identifier-$month.$extension";

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

                    $path = Storage::putFileAs("$directory", $request->file('upload_file'), $file_name);

                    ReportUpload::updateOrCreate([
                        'report_id' =>  $report_id
                    ],[
                        'file_name' =>  $file_name,
                        'file_path' =>  $path,
                    ]);
                    $success = "The upload was successful.";
                }
                else{
                    $insert = DB::connection('mongodb')
                        ->collection('indicator_form_details')
                        ->insert($upload_values);

                    if ($insert){

                        $path = Storage::putFileAs("$directory", $request->file('upload_file'), $file_name);

                        ReportUpload::updateOrCreate([
                            'report_id' =>  $report_id
                        ],[
                            'file_name' =>  $file_name,
                            'file_path' =>  $path,
                        ]);

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
    }

    public function saveWebForm(Request $request,$dlr_id){

        $this_dlr = Indicator::find($request->indicator_id);
        $indicator_details = array(); //An array to holds the indicator details
        $report_id = (integer)($request->report_id);

        $files_array = array();
        $this_report = Report::find($report_id)->first();
        $ace_id = $this_report->ace_id;

        //Gather the file name and directory path
        $report = Report::find($report_id);
        $acronym = strtoupper($report->ace->acronym);
        $reporting_year = $report->reporting_period->first()->reporting_year;
        $identifier = "dlr_".str_replace('.','_',$report->indicator->identifier);
        $directory = "public/reports/$acronym/$reporting_year/$identifier";

        switch ($this_dlr->identifier) {
            case "4.1":
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
                $indicator_details['newly_accredited_programme'] = $request->newly_accredited_programme;
                break;
            case "5.1":
                $indicator_details['report_id'] = (integer)$report_id;
//                $indicator_details['indicator_id'] = $request->indicator_id;
                $indicator_details['amountindollars'] = $request->amountindollars;
                $indicator_details['originalamount'] = $request->originalamount;
                $indicator_details['currency'] = $request->currency;
                $indicator_details['source'] = $request->source;
                $indicator_details['datereceived'] = date('Y-m-d',strtotime($request->datereceived));
                $indicator_details['bankdetails'] = $request->bankdetails;
                $indicator_details['region'] = $request->region;
                $indicator_details['fundingreason'] = $request->fundingreason;
                break;
            case "6.1":
                if ($request->file('file_name_1')) {
                    $file1_name= $request->file_name_1;
                    $files_array['file_one'] =  $request->file('file_name_1');
                    $indicator_details['file_name_1'] = $file1_name->getClientOriginalName();
                }
                if ($request->file('file_name_2')) {
                    $file2_name = $request->file_name_2;
                    $files_array['file_two'] =  $request->file('file_name_2');
                    $indicator_details['file_name_2'] = $file2_name->getClientOriginalName();
                };
                $indicator_details['report_id'] = (integer)$report_id;
                $indicator_details['ifr_period'] = $request->ifr_period;
                $indicator_details['file_name_1_submission'] = $request->file_name_1_submission;
                $indicator_details['efa_period'] = $request->efa_period;
                $indicator_details['file_name_2_submission'] = $request->file_name_2_submission;
                break;
            case "6.2":
                $indicator_details['report_id'] = (integer)$report_id;
                $indicator_details['ace_id'] = (integer)$ace_id;
                if ($request->file('guideline_file')) {
                    $guideline_file= $request->guideline_file;
                    $files_array['guideline_file'] =  $request->file('guideline_file');
                    $indicator_details['guideline_file'] = $guideline_file->getClientOriginalName();
                }
                if ($request->file('members_file')) {
                    $members_file = $request->members_file;
                    $files_array['members_file'] =  $request->file('members_file');
                    $indicator_details['members_file'] = $members_file->getClientOriginalName();
                };
                if ($request->file('report_file')) {
                    $report_file= $request->report_file;
                    $files_array['report_file'] =  $request->file('report_file');
                    $indicator_details['report_file'] = $report_file->getClientOriginalName();
                }
                if ($request->file('audited_account_file')) {
                    $audited_account_file = $request->audited_account_file;
                    $files_array['audited_account_file'] =  $request->file('audited_account_file');
                    $indicator_details['audited_account_file'] = $audited_account_file->getClientOriginalName();
                };
                break;
            case "6.3":
                dd($request->all());
                break;
            case "6.4":
                $indicator_details['report_id'] = (integer)$report_id;
                $indicator_details['ace_id'] = $report->ace->id;

                if ($request->file('approved_procurement_file')) {
                    $file1_name= $request->approved_procurement_file;
                    $files_array['file_one'] =  $request->file('approved_procurement_file');
                    $indicator_details['approved_procurement_file'] = $file1_name->getClientOriginalName();
                }
                if ($request->file('officer_file')) {
                    $file2_name = $request->officer_file;
                    $files_array['file_two'] =  $request->file('officer_file');
                    $indicator_details['officer_file'] = $file2_name->getClientOriginalName();
                }

                if ($request->file('procurement_progress_report_file')) {
                    $file1_name= $request->procurement_progress_report_file;
                    $files_array['file_three'] =  $request->file('procurement_progress_report_file');
                    $indicator_details['procurement_progress_report_file'] = $file1_name->getClientOriginalName();
                }
                if ($request->file('contracts_signed_file')) {
                    $file2_name = $request->contracts_signed_file;
                    $files_array['file_four'] =  $request->file('contracts_signed_file');
                    $indicator_details['contracts_signed_file'] = $file2_name->getClientOriginalName();
                }
                break;
            case "7.1":
                $indicator_details['report_id'] = (integer)$report_id;
                if ($request->file('upload_1')) {
                    $file1_name= $request->upload_1;
                    $files_array['file_one'] =  $request->file('upload_1');
                    $indicator_details['upload_1'] = $file1_name->getClientOriginalName();
                }
                if ($request->file('upload_2')) {
                    $file1_name= $request->upload_2;
                    $files_array['file_two'] =  $request->file('upload_2');
                    $indicator_details['upload_2'] = $file1_name->getClientOriginalName();
                }
                if ($request->file('upload_3')) {
                    $file1_name= $request->upload_3;
                    $files_array['file_three'] =  $request->file('upload_3');
                    $indicator_details['upload_3'] = $file1_name->getClientOriginalName();
                }
                $indicator_details['upload_1_description'] = $request->upload_1_description;
                $indicator_details['upload_2_description'] = $request->upload_2_description;
                $indicator_details['upload_3_description'] = $request->upload_3_description;
                break;
            case "7.2":
                dd($request->all());
                break;
            case "7.3":
                $indicator_details['report_id'] = (integer)$report_id;
                $indicator_details['indicator_id'] = $request->indicator_id;
                $indicator_details['institutionname'] = $request->institutionname;
                $indicator_details['typeofaccreditation'] = $request->typeofaccreditation;
                $indicator_details['accreditationagency'] = $request->accreditationagency;
                $indicator_details['accreditationreference'] = $request->accreditationreference;
                $indicator_details['contactname'] = $request->contactname;
                $indicator_details['contactemail'] = $request->contactemail;
                $indicator_details['contactphone'] = $request->contactphone;
                $indicator_details['dateofaccreditation'] = $request->dateofaccreditation;
                $indicator_details['exp_accreditationdate'] = $request->exp_accreditationdate;
                break;
            case "7.4":
                dd($request->all());
                break;
            case "7.6":
                dd($request->all());
                break;
            default:
                "Nothing";
        }

        if (isset($this_dlr->web_form_id)) {
            $table_name = $this_dlr->webForm->table_name;
            $saved= DB::table("$table_name")->insert($indicator_details);
        } else {
            $table_name = Str::snake("indicator_".$this_dlr->identifier);
            $saved= DB::connection('mongodb')->collection("$table_name")->insert($indicator_details);
        }

        if(!$saved){
            $error_msg = "Data hasn't saved. Please try again.";
            notify(new ToastNotification('Sorry', $error_msg, 'warning'));
            return back()->withInput();
        }else{
            foreach ($files_array as $key=>$value){
                Storage::putFileAs("$directory", $value, $value->getClientOriginalName());
            }
            $success = "The data has been saved.";
            notify(new ToastNotification('Successful', $success, 'success'));
            return back();
        }

    }

    public function uploadWebForm(Request $request,$dlr_id){


        $this->validate($request, [
            'upload_file' => 'required|file|mimes:xls,xlsx',
        ]);

        $this_dlr = Indicator::where('id','=',$dlr_id)->first();
        $table_name = Str::snake("indicator_".$this_dlr->identifier);
        $report = Report::where('id','=',$request->report_id)->first();

        $file_one=$request->upload_file;
        $destinationPath = base_path() . '/public/DLRs/';
        $file1 = $request->file('upload_file');


        if (isset($file1)) {

            try {
                $spreadsheet = IOFactory::load($file1->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
                $row_limit = $sheet->getHighestDataRow();
                $row_range = range(2, $row_limit);
                $startcount = 2;
                $indicator_details = array(); //An array to holds the indicator details
                foreach ($row_range as $row) {

                    if($this_dlr->identifier =='5.1' ){
                        $indicator_details[] =[ 'report_id' => (integer)$request->report_id,
                        'indicator_id' => $dlr_id,
                        'amountindollars' => $sheet->getCell('A' . $row)->getValue(),
                        'originalamount' => $sheet->getCell('B' . $row)->getValue(),
                        'currency'=> $sheet->getCell('C' . $row)->getValue(),
                        'source' => $sheet->getCell('D' . $row)->getValue(),
                        'datereceived' => $sheet->getCell('E' . $row)->getValue(),
                        'bankdetails' => $sheet->getCell('F' . $row)->getValue(),
                        'region' => $sheet->getCell('G' . $row)->getValue(),
                        'fundingreason' => $sheet->getCell('H' . $row)->getValue()
                        ];

                    }
                    $startcount++;
                }
                if (isset($this_dlr->web_form_id)) {
                    $saved = DB::table("$table_name")->insert($indicator_details);
                }
                else{
                    $saved = DB::connection('mongodb')->collection("$table_name")->insert($indicator_details);
                }
                if ($saved) {
                    $file1->move($destinationPath, $file1->getClientOriginalName());
                    $thefile_one = $file_one->getClientOriginalName();
                    notify(new ToastNotification('Successful!', 'DLR data Added', 'success'));
                    return back();
                }else{
                    notify(new ToastNotification('Notice', 'An error occured extracting data- Please check the format and try again.', 'info'));
                    return back();
                }
            }catch (Exception $e) {
                $error_code = $e->errorInfo[1];

            }
        }
        notify(new ToastNotification('Notice', 'Could not upload the file', 'info'));
        return back();
    }

    public function removeRecord($indicator_id,$record_id)
    {
        $this_dlr = Indicator::find(Crypt::decrypt($indicator_id));
        if (isset($this_dlr->web_form_id)) {
            $table_name = $this_dlr->webForm->table_name;
        }
        else {
            $table_name = Str::snake("indicator_" . $this_dlr->identifier);
        }

        if (DB::connection('mongodb')->collection("$table_name")->delete($record_id)) {
            notify(new ToastNotification('Successful!', 'Record Deleted!', 'success'));
        } elseif (DB::table("$table_name")->delete($record_id)){
            notify(new ToastNotification('Successful!', 'Record Deleted!', 'success'));
        } else {
            notify(new ToastNotification('Sorry!', 'Something went wrong!', 'warning'));
        }
        return back();
    }

    public function editRecord(Request $request){
        $this_indicator = Indicator::find($request->indicator_id);
        $record_id = $request->record_id;
        if (isset($this_indicator->web_form_id)) {
            $table_name = $this_indicator->webForm->table_name;
            $the_record = DB::table("$table_name")
                ->where('id','=',$record_id)
                ->first();
        }
        else {
            $table_name = Str::snake("indicator_".$this_indicator->identifier);
            $the_record = DB::connection('mongodb')->collection("$table_name")
                ->where('_id','=',$record_id)
                ->first();
        }

        $report = Report::find($the_record->report_id);
        $currency_list = DB::table('currency_list')->get();
        $ace = $report->ace;
        $ace_programmes = explode(';',$ace->programmes);
        $select_language = new CommonFunctions();
        $lang = $select_language->webFormLang($report->language);

        /**
         * Gather the file name and directory path to retrieve saved documents
         * **/
        $acronym = strtoupper($report->ace->acronym);
        $reporting_year = $report->reporting_period->first()->reporting_year;
        $identifier = "dlr_".str_replace('.','_',$report->indicator->identifier);
        $directory = "public/reports/$acronym/$reporting_year/$identifier";

        if($report->language=="english" && $this_indicator->identifier =='4.1' ){
            $view = view ('report-form.webforms.edit_dlr41en',compact('the_record','record_id',
                'this_indicator','ace_programmes'))->render();
        }
        elseif ($report->language=="french" && $this_indicator->identifier =='4.1' ){
            $view = view ('report-form.webforms.edit_dlr41fr',compact('the_record','record_id',
                'this_indicator','ace_programmes'))->render();
        }
        elseif($report->language=="english" && $this_indicator->identifier =='7.3' ){
            $view = view ('report-form.webforms.edit_dlr73en',compact('the_record','record_id',
                'this_indicator'))->render();
        }
        elseif($report->language=="french" && $this_indicator->identifier =='7.3' ){
            $view = view ('report-form.webforms.edit_dlr73fr',compact('the_record','record_id',
                'this_indicator'))->render();
        }
        elseif (isset($this_indicator->web_form_id)) {
            $indicator_info = $this_indicator;
            $view_name = $this_indicator->webForm->view_name;
            $form_view = substr_replace($view_name,'form',strrpos($view_name,'page'));
            $view = view ("report-form.webforms.$form_view",compact('the_record','currency_list','record_id',
                'indicator_info','lang','report','directory'))->render();
        }
        return response()->json(['theView' => $view]);
    }

    public function updateRecord(Request $request,$indicator_id,$record_id){

        $this_dlr = Indicator::find($request->indicator_id);
        if (isset($this_dlr->web_form_id)) {
            $table_name = $this_dlr->webForm->table_name;
        }
        else {
            $table_name = Str::snake("indicator_".$this_dlr->identifier);
        }

        $indicator_details = array(); //An array to holds the indicator details
        $report_id = (integer)($request->report_id);
        $files_array =array();
        $report = Report::find($report_id);
        $acronym = strtoupper($report->ace->acronym);
        $reporting_year = $report->reporting_period->first()->reporting_year;
        $identifier = "dlr_".str_replace('.','_',$report->indicator->identifier);
        $directory = "public/reports/$acronym/$reporting_year/$identifier";

        $this_report = Report::find($report_id)->first();
        $ace_id = $this_report->ace_id;

        switch ($this_dlr->identifier) {
            case "4.1":
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
                $indicator_details['newly_accredited_programme'] = $request->newly_accredited_programme;
                break;
            case "5.1":
                $indicator_details['report_id'] = (integer)$report_id;
//                $indicator_details['indicator_id'] = $request->indicator_id;
                $indicator_details['amountindollars'] = $request->amountindollars;
                $indicator_details['originalamount'] = $request->originalamount;
                $indicator_details['currency'] = $request->currency;
                $indicator_details['source'] = $request->source;
                $indicator_details['datereceived'] = $request->datereceived;
                $indicator_details['bankdetails'] = $request->bankdetails;
                $indicator_details['region'] = $request->region;
                $indicator_details['fundingreason'] = $request->fundingreason;
                break;
            case "6.1":
                $indicator_details['report_id'] = (integer)$report_id;
                $indicator_details['ifr_period'] = $request->ifr_period;

                if ($request->file('file_name_1')) {
                    $file1_name= $request->file_name_1;
                    $files_array['file_one'] =  $request->file('file_name_1');
                    $indicator_details['file_name_1'] = $file1_name->getClientOriginalName();
                }
                if ($request->file('file_name_2')) {
                    $file2_name = $request->file_name_2;
                    $files_array['file_two'] =  $request->file('file_name_2');
                    $indicator_details['file_name_2'] = $file2_name->getClientOriginalName();
                }

                $indicator_details['file_name_1_submission'] = $request->file_name_1_submission;
                $indicator_details['efa_period'] = $request->efa_period;
                $indicator_details['file_name_2_submission'] = $request->file_name_2_submission;
                break;
            case "6.2":
                $indicator_details['report_id'] = (integer)$report_id;
                $indicator_details['ace_id'] = (integer)$ace_id;
                if ($request->file('guideline_file')) {
                    $guideline_file= $request->guideline_file;
                    $files_array['guideline_file'] =  $request->file('guideline_file');
                    $indicator_details['guideline_file'] = $guideline_file->getClientOriginalName();
                }
                if ($request->file('members_file')) {
                    $members_file = $request->members_file;
                    $files_array['members_file'] =  $request->file('members_file');
                    $indicator_details['members_file'] = $members_file->getClientOriginalName();
                };
                if ($request->file('report_file')) {
                    $report_file= $request->report_file;
                    $files_array['report_file'] =  $request->file('report_file');
                    $indicator_details['report_file'] = $report_file->getClientOriginalName();
                }
                if ($request->file('audited_account_file')) {
                    $audited_account_file = $request->audited_account_file;
                    $files_array['audited_account_file'] =  $request->file('audited_account_file');
                    $indicator_details['audited_account_file'] = $audited_account_file->getClientOriginalName();
                };
                break;
            case "6.3":
                dd($request->all());
                break;
            case "6.4":
                $indicator_details['report_id'] = (integer)$report_id;
                $indicator_details['ace_id'] = $report->ace->id;

                if ($request->file('approved_procurement_file')) {
                    $file1_name= $request->approved_procurement_file;
                    $files_array['file_one'] =  $request->file('approved_procurement_file');
                    $indicator_details['approved_procurement_file'] = $file1_name->getClientOriginalName();
                }
                if ($request->file('officer_file')) {
                    $file2_name = $request->officer_file;
                    $files_array['file_two'] =  $request->file('officer_file');
                    $indicator_details['officer_file'] = $file2_name->getClientOriginalName();
                }

                if ($request->file('procurement_progress_report_file')) {
                    $file1_name= $request->procurement_progress_report_file;
                    $files_array['file_three'] =  $request->file('procurement_progress_report_file');
                    $indicator_details['procurement_progress_report_file'] = $file1_name->getClientOriginalName();
                }
                if ($request->file('contracts_signed_file')) {
                    $file2_name = $request->contracts_signed_file;
                    $files_array['file_four'] =  $request->file('contracts_signed_file');
                    $indicator_details['contracts_signed_file'] = $file2_name->getClientOriginalName();
                }
                break;
            case "7.1":
                $indicator_details['report_id'] = (integer)$report_id;
                if ($request->file('upload_1')) {
                    $file1_name= $request->upload_1;
                    $files_array['file_one'] =  $request->file('upload_1');
                    $indicator_details['upload_1'] = $file1_name->getClientOriginalName();
                }
                if ($request->file('upload_2')) {
                    $file1_name= $request->upload_2;
                    $files_array['file_two'] =  $request->file('upload_2');
                    $indicator_details['upload_2'] = $file1_name->getClientOriginalName();
                }
                if ($request->file('upload_3')) {
                    $file1_name= $request->upload_3;
                    $files_array['file_three'] =  $request->file('upload_3');
                    $indicator_details['upload_3'] = $file1_name->getClientOriginalName();
                }
                $indicator_details['upload_1_description'] = $request->upload_1_description;
                $indicator_details['upload_2_description'] = $request->upload_2_description;
                $indicator_details['upload_3_description'] = $request->upload_3_description;
                break;
            case "7.2":
                dd($request->all());
                break;
            case "7.3":
                $indicator_details['report_id'] = (integer)$report_id;
                $indicator_details['indicator_id'] = $request->indicator_id;
                $indicator_details['institutionname'] = $request->institutionname;
                $indicator_details['typeofaccreditation'] = $request->typeofaccreditation;
                $indicator_details['accreditationreference'] = $request->accreditationreference;
                $indicator_details['accreditationagency'] = $request->accreditationagency;
                $indicator_details['contactname'] = $request->contactname;
                $indicator_details['contactemail'] = $request->contactemail;
                $indicator_details['contactphone'] = $request->contactphone;
                $indicator_details['dateofaccreditation'] = $request->dateofaccreditation;
                $indicator_details['exp_accreditationdate'] = $request->exp_accreditationdate;
                break;
            case "7.4":
                dd($request->all());
                break;
            case "7.6":
                dd($request->all());
                break;
            default:
                "Nothing";
        }

        if (isset($this_dlr->web_form_id)) {
            $updated = DB::table("$table_name")
                ->where('id','=',$record_id)
                ->update($indicator_details);
        } else {
            $updated = DB::connection('mongodb')->collection("$table_name")
                ->where('_id','=',$record_id)
                ->update($indicator_details);
        }

        if(!$updated){
            $error_msg = "No data available to be saved. Please try again.";
            notify(new ToastNotification('error', $error_msg, 'info'));
            return back()->withInput();
        }else if($updated) {
            foreach ($files_array as $key=>$value){
                Storage::putFileAs("$directory", $value, $value->getClientOriginalName());
            }
            $success = "The Update was successful.";
            notify(new ToastNotification('Successful', $success, 'success'));
        }
        return back();
    }
}





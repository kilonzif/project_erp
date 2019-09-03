<?php
namespace App\Http\Controllers;
use App\Ace;
use App\Classes\ToastNotification;
use App\Course;
use App\Currency;
use App\Http\Controllers\Controller;
use App\Indicator;
use App\Institution;
use App\Project;
use App\Report;
use App\VerificationLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class VerificationLetterController extends Controller {

	public function __construct() {
		$this->middleware('auth');
	}

	public function report_verify_letter_logs($report) {
		$letters = VerificationLetter::where('report_id', '=', $report)->get();
		$currencies = Currency::get();

		return view('generate-report.verificationletterreport.report-letters', compact(['letters', 'currencies', 'report']));
	}

	public function report_verify_letter_logs_save(Request $request) {
//        return $request->all();
		$letter = new VerificationLetter();
		$letter->report_id = $request->report;
		$letter->amount_due = $request->amount_due;
		$letter->letter_dated = $request->letter_dated;
		$letter->date_dispatched = $request->date_dispatched;
		$letter->payment = $request->payment;
		$letter->save();

		notify(new ToastNotification('Successful', 'Verification Log saved', 'success'));
		return back();
	}

	public function list() {
		$aces = Ace::with('verificationLetters')->get();
		return view('generate-report.verificationletterreport.list'
			, compact('aces'));
	}

	public function verificationpage() {

		$verificationletters = VerificationLetter::get();
		$aces = Ace::get();
		return view('generate-report.verificationletterreport.verificationpage', compact('aces', 'verificationletters'));

	}

	public function verificationpagereport(Request $request) {
		//	$process = new CommonFunctions();
		//return $request->all();

		$this->validate($request, [
			'start' => 'required|date',
			'end' => 'required|date',
		]);

		//return $request->all();

		// $reports = Report::where('start_date', '>=', $request->start)
		// 	->where('end_date', '<=', $request->end)
		// 	->whereIn('ace_id', $request->aces)
		// 	->with(array('ace', 'verificationLetters'))
		// 	->get();

		$verificationletters = DB::table('verification_letters')
			->select('countries.country', 'aces.name', 'verification_letters.letter_dated', 'verification_letters.date_dispatched', 'verification_letters.payment', 'verification_letters.amount_due')
			->join('reports', 'verification_letters.report_id', '=', 'reports.id')
			->join('aces', 'reports.ace_id', '=', 'aces.id')
			->join('institutions', 'aces.institution_id', '=', 'institutions.id')
			->join('countries', 'institutions.country_id', '=', 'countries.id')
			->where('reports.end_date', '<=', $request->end)
			->whereIn('reports.ace_id', $request->aces)
			->where('start_date', '>=', $request->start)
			->get();

		//dd($verificationletters);
		//dd($request->aces);
		//dd($verificationletters->groupBy('country'));
		// dd(DB::getQueryLog());
		//join('aces', 'verification_letters.ace_id', '=', 'aces.id')->
		//	dd($request->aces);
		// $aces = Ace::where('status', '=', 1)
		// 	->join('reports', 'reports.ace_id', '=', 'aces.id')
		// 	->where('start_date', '>=', $request->start)
		// 	->where('end_date', '<=', $request->end)
		// 	->whereIn('ace_id', $request->aces)
		// 	->get();

		//$aces = Ace::with('verificationLetters')->get();

		//dd($aces);

		// $acesAll = $request->aces;
		// dd($acesAll);
		// if (isset($acesAll)) {
		// 	$aces = $verificationletter->whereIn('ace_id', $acesAll);
		// }

		// dd($aces);

		$start = $request->start;
		$end = $request->end;

		//dd($reports);

		if ($request->query->get('export')) {
			//dd("hello");
			return $this->spreadsheet($verificationletters);
		}

		return view('generate-report.verificationletterreport.verificationpagereport', compact('start', 'end', 'verificationletters'));

		// return back();
		//   $process = new CommonFunctions();
		//  $this->validate($request,[
		//      'start' => 'required|string',
		//      'end' => 'required|string'
		//  ]);
		//  $project = Project::find(1);
		// return $request->all();

		//  $reports = Report::where('status','=',1)
		//      ->join('aces', 'reports.ace_id', '=', 'aces.id')
		//      ->where('start_date','>=',$request->start)
		//      ->where('end_date','<=',$request->end)
		//      ->get();

		//  $acesAll = $request->aces;
		//  if(isset($acesAll)){
		//      $reports = $reports->whereIn('ace_id',$acesAll);
		//  }

		//  if ($request->filter == "revenue"){
		//      $steps = $process->getRevenueProcess();
		//  }
		//  elseif ($request->filter == "quality"){
		//      $steps = $process->getQualityProcess();
		//  }
		//  else{
		//      $steps = $process->getStudentProcess();
		//  }

		//  $type_indicator = $process->getDLR_indicator($request->filter);
		//  $start = $request->start;
		//  $end = $request->end;
		//  return view('generate-report.verification-indicator-result',
		//      compact('project','start','end','reports','steps','process','type_indicator'));

	}

	private function spreadsheet($verificationletters) {
		$spreadsheet = new Spreadsheet();
		$styleArray = [
			'font' => [
				'bold' => true,
			],
		];
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->getDefaultColumnDimension()->setWidth(20);

// 		$columns = range('A', 'Z');
		// 		$counter = 0;

// 		foreach ($steps as $step) {

// 			$sheet->setCellValue($columns[$counter + 1] . '1', $process->getStatusLabelRep($step))->getStyle($columns[$counter + 1] . '1')->applyFromArray($styleArray);

// 			$counter++;
		// }

		$sheet->setCellValue('A1', 'Country')->getStyle('A1')->applyFromArray($styleArray);
		$sheet->setCellValue('B1', 'Ace')->getStyle('B1')->applyFromArray($styleArray);
		$sheet->setCellValue('C1', 'Letter Dated')->getStyle('C1')->applyFromArray($styleArray);
		$sheet->setCellValue('D1', 'Date Dispatched')->getStyle('D1')->applyFromArray($styleArray);
		$sheet->setCellValue('E1', 'Payment')->getStyle('E1')->applyFromArray($styleArray);
		$sheet->setCellValue('F1', 'Amount Due')->getStyle('F1')->applyFromArray($styleArray);
		$sheet->setCellValue('G1', 'Total')->getStyle('G1')->applyFromArray($styleArray);

		$row = 2;

		foreach ($verificationletters->groupBy('country') as $country => $letters) {

			$sheet->mergeCells('A' . $row . ':A' . ($letters->count() + $row - 1));
			//dd($letters->count());

			$sheet->getStyle('A' . $row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
			$sheet->setCellValue('A' . $row, $country)->getStyle('A' . $row)->applyFromArray($styleArray);

			foreach ($letters->groupBy('name') as $ace => $ace_letters) {

				$sheet->mergeCells('B' . $row . ':B' . ($ace_letters->count() + $row - 1));
				$sheet->getStyle('B' . $row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
				$sheet->setCellValue('B' . $row, $ace)->getStyle('B' . $row)->applyFromArray($styleArray);

				$sheet->mergeCells('G' . $row . ':G' . ($ace_letters->count() + $row - 1));
				$sheet->getStyle('G' . $row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
				$sheet->setCellValue('G' . $row, $ace_letters->sum('amount_due'));

				foreach ($ace_letters as $index => $letter) {

					$sheet->setCellValue('C' . $row, $letter->letter_dated);
					$sheet->setCellValue('D' . $row, $letter->date_dispatched);
					$sheet->setCellValue('E' . $row, $letter->payment);
					$sheet->setCellValue('F' . $row, $letter->amount_due);

					$row++;

				}

			}

		}

		// We'll be outputting an excel file

// Write file to the browser

		$writer = new Xls($spreadsheet);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Verification Log Report.xls"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');

	}

	// private function export($start, $end, $reports) {

	// 	return Excel::download(new ReportExport($start, $end, $reports), 'report.xlsx');

	// }

	public function gen() {
		$VerificationLetter = VerificationLetter::get();

		return view('generate-report.verificationletterreport.generate'
			, compact('VerificationLetter'));
	}

	public function create() {
		$aces = Ace::all();
		$courses = Course::get();
		$universities = Institution::where('university', '=', 1)->get();

		$indicators = Indicator::where('parent_id', '=', 0)->where('status', '=', 1)->get();
		$projects = Project::where('status', '=', 1)->get();
		return view('settings.verificationletter', compact('indicators', 'projects', 'aces', 'universities', 'courses'));
	}

	public function save(Request $request) {
		$theFields = $request->fields;
		foreach ($theFields as $field) {
			VerificationLetter::create($field);
		}
		notify(new ToastNotification('Successful!', 'The data has been added!', 'success'));
		return back();
	}

	public function edit($id) {
		$verification_id = Crypt::decrypt($id);
		$verifications = VerificationLetter::find($verification_id);
		return view('generate-report.verificationletterreport.edit', compact('verifications'));
	}

	public function update(Request $request, $id) {
		$this->validate($request, [
			'amount_due' => 'required|numeric|min:1',
			'payment' => 'required|string|min:1',
			'letter_dated' => 'required|date',
			'date_dispatched' => 'required|date',
		]);

		$verification = VerificationLetter::find($id);
		$verification->amount_due = $request->amount_due;
		$verification->payment = $request->payment;
		$verification->letter_dated = $request->letter_dated;
		$verification->date_dispatched = $request->date_dispatched;
		$verification->save();

		notify(new ToastNotification('Successful!', 'Verification Log updated!', 'success'));
		return redirect()->route('report_generation.verificationletter.report', [$verification->report_id]);

	}

	public function delete($id) {
		$verification_id = Crypt::decrypt($id);
		VerificationLetter::destroy($verification_id);

		notify(new ToastNotification('Successful!', ' Email deleted!', 'success'));
		return redirect()->back();
	}
}

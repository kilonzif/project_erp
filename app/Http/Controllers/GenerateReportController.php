<?php
namespace App\Http\Controllers;

use App\Ace;
use App\Classes\CommonFunctions;
use App\Classes\ToastNotification;
use App\Country;
use App\Exports\ReportsExport;
use App\Indicator;
use App\Milestone;
use App\Project;
use App\Report;
use App\SystemOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class GenerateReportController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}

	public function general_report_page() {
		$indicators = Indicator::mainIndicators()->get();
		$aces = Ace::orderBy('aces.name', 'asc')->get();
		$countries = DB::table('aces')->join('institutions', 'aces.institution_id', '=', 'institutions.id')
			->join('countries', 'institutions.country_id', '=', 'countries.id')
			->distinct('countries.id')
			->select('countries.*')
			->get();
		$fields = ['Agriculture', 'Health', 'STEM'];
		return view('generate-report.general-report', compact('indicators', 'aces', 'countries', 'fields'));
	}

	public function milestones_report_page() {
		$indicators = Indicator::mainIndicators()->get();
		$aces = Ace::orderBy('aces.name', 'asc')->get();
		$countries = DB::table('aces')->join('institutions', 'aces.institution_id', '=', 'institutions.id')
			->join('countries', 'institutions.country_id', '=', 'countries.id')
			->distinct('countries.id')
			->select('countries.*')
			->get();
		$fields = ['Agriculture', 'Health', 'STEM'];
		return view('generate-report.milestones.generate-page', compact('indicators', 'aces', 'countries', 'fields'));
	}

	public function general_report(Request $request) {
		$this->validate($request, [
			'start' => 'required|string',
			'end' => 'required|string',
		]);
		return $request->all();
//        $indicators = $request->indicators;
		$project = Project::find(1);

		$report_values = DB::table('reports')
			->join('report_values', 'reports.id', '=', 'report_values.report_id')
			->select('report_values.indicator_id', DB::raw('SUM(report_values.value) as ind_values'))
			->where('reports.start_date', '>=', $request->start)
			->where('reports.end_date', '<=', $request->end)
//            ->whereIn('report_values.indicator_id',$indicators)
			->groupBy('report_values.indicator_id')
			->get();
//            return $report_values;
		$start = $request->start;
		$end = $request->end;
//        $value = $report_values->where('indicator_id','=',3)->pluck('ind_values');
		//        return $value[0];
		return view('generate-report.general-report-result',
			compact('project', 'report_values', 'start', 'end'));
	}

	public function general_report_table(Request $request) {
		$urii = new CommonFunctions();
		$process = new CommonFunctions();

		$Excelquery = $urii->currentUrl();
		//$export = route('report_generation.general_report_excel') . '?' . $Excelquery;

		$this->validate($request, [
			'start' => 'required|string',
			'end' => 'required|string',
		]);
//        return $request->all();
		$project = Project::find(1);
        $status = SystemOption::where('option_name', '=', 'generation_status')->pluck('option_value')->first();
        if (!isset($status)){
            $status = 101;
        }

		$reports = DB::table('reports')
//            ->join('report_values', 'reports.id', '=', 'report_values.report_id')
			->join('aces', 'reports.ace_id', '=', 'aces.id')
			->join('institutions', 'aces.institution_id', '=', 'institutions.id')
			->join('countries', 'institutions.country_id', '=', 'countries.id')
			->distinct('reports.id')
			->select(DB::raw('reports.*,aces.id as aceID, aces.*,countries.id as countryID, countries.*,reports.id'))
			->where('reports.status', '=', $status)
			->where('reports.start_date', '>=', $request->start)
			->where('reports.end_date', '<=', $request->end)
			->get();

		//Filter by ACE

		if ($request->filter == "aces") {
			$reports = $reports->whereIn('ace_id', $request->aces);
//			$steps = $process->getTitleProcess();
		}

		//Filter by field or Country
		if ($request->filter == "field_country") {

			//Filter by Country
			if (isset($request->country)) {
				if (sizeof($request->country) > 0) {
					$reports = $reports->whereIn('countryID', $request->country);
				}
			}
			//Filter by Field
			if (isset($request->field)) {
				if (sizeof($request->field) > 0) {
					$reports = $reports->whereIn('field', $request->field);
				}
			}

		}

		//Get aces target values by indicators
		$target_values = DB::table('ace_indicators_targets')
			->join('ace_indicators_target_years', 'ace_indicators_targets.target_year_id', '=', 'ace_indicators_target_years.id')
			->select('indicator_id', DB::raw('SUM(ace_indicators_targets.target) as targets'))
			->whereIn('ace_indicators_target_years.ace_id', $reports->pluck('ace_id'))
			->where('start_period', '>=', $request->start)
			->where('end_period', '<=', $request->end)
			->groupBy('indicator_id')
			->pluck('targets', 'indicator_id');

		//Get aces baselines by indicators
		$baseline_values = DB::table('ace_indicators_baselines')
			->select('indicator_id', DB::raw('SUM(baseline) as baselines'))
			->whereIn('ace_id', $reports->pluck('ace_id'))
			->groupBy('indicator_id')
			->pluck('baselines', 'indicator_id');

		$report_values = DB::table('report_values')
			->select('indicator_id', DB::raw('SUM(value) as ind_values'))
			->whereIn('report_id', $reports->pluck('id'))
			->groupBy('indicator_id')
			->get();

		$start = $request->start;
		$end = $request->end;

		if ($request->query->get('export')) {
			// dd("hello");
			return $this->generalspreadsheet($report_values, $baseline_values, $target_values, $reports, $project);
		}

		return view('generate-report.general-f-table',
			compact('project', 'report_values', 'start', 'end', 'baseline_values', 'target_values', 'export', 'reports'));
	}

	public function indicator_verification() {
		$indicators = Indicator::mainIndicators()->get();
		$aces = Ace::get();
		$countries = DB::table('aces')->join('institutions', 'aces.institution_id', '=', 'institutions.id')
			->join('countries', 'institutions.country_id', '=', 'countries.id')
			->distinct('countries.id')
			->select('countries.*')
			->get();
		$fields = ['Agriculture', 'Health', 'STEM'];
		return view('generate-report.verification-indicator-status', compact('indicators', 'aces', 'countries', 'fields'));
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */

	public function indicator_verification_report(Request $request) {

		$process = new CommonFunctions();
		$this->validate($request, [
			'start' => 'required|string',
			'end' => 'required|string',
		]);
		$project = Project::find(1);
        $status = SystemOption::where('option_name', '=', 'generation_status')->pluck('option_value')->first();
        if (!isset($status)){
            $status = 101;
        }

		$reports = Report::join('aces', 'reports.ace_id', '=', 'aces.id')
			->where('status', '=', $status)
			->where('start_date', '>=', $request->start)
			->where('end_date', '<=', $request->end)
			->get();

		$acesAll = $request->aces;
		if (isset($acesAll)) {
			$reports = $reports->whereIn('ace_id', $acesAll);
		}

		if ($request->filter == "revenue") {
			$steps = $process->getRevenueProcess();
		} elseif ($request->filter == "quality") {
			$steps = $process->getQualityProcess();
		} else {
			$steps = $process->getStudentProcess();
		}

		$type_indicator = $process->getDLR_indicator($request->filter);
		$start = $request->start;
		$end = $request->end;

		if ($request->query->get('export')) {
			return $this->indicatorspreadsheet($reports, $steps, $process, $type_indicator);
		}

		return view('generate-report.verification-indicator-result',
			compact('start', 'end', 'reports', 'steps', 'process', 'type_indicator'));

	}

	//Milestones
	public function milestones($report_id) {
		$report = Report::find($report_id);
		$status_history = Milestone::where('report_id', '=', $report_id)->get();
//        return $status_history;
		$get_status = new CommonFunctions();
		$milestone_statuses = $get_status->getInfrastructureProcess();
		$all_status = $get_status->getStatusLabel();
		$all_label = $get_status->getStatusLabelRep();
		return view('generate-report.milestones.index',
			compact('report', 'all_status', 'status_history', 'milestone_statuses', 'all_label'));
	}

	public function milestones_save(Request $request, $report) {
		$this->validate($request, [
			'number' => 'required|numeric|min:1',
			'status_label' => 'required|numeric|min:1',
			'sub_date' => 'required|date',
		]);

		Milestone::updateOrCreate(
			['number' => $request->number, 'report_id' => $report, 'status' => $request->status_label],
			['responsibility' => $request->status_label, 'status_date' => $request->sub_date]
		);

		notify(new ToastNotification('Successful', 'Milestone Status Updated.', 'success'));
		return back();
	}

	public function generate_milestones_report(Request $request) {
		$urii = new CommonFunctions();
		$Excelquery = $urii->currentUrl();
		// $export = route('report_generation.general_report_excel') . '?' . $Excelquery;

		$this->validate($request, [
			'start' => 'required|string',
			'end' => 'required|string',
		]);
		$project = Project::find(1);
        $status = SystemOption::where('option_name', '=', 'generation_status')->pluck('option_value')->first();
        if (!isset($status)){
            $status = 101;
        }
		$reports = DB::table('reports')
			->join('aces', 'reports.ace_id', '=', 'aces.id')
			->join('institutions', 'aces.institution_id', '=', 'institutions.id')
			->join('countries', 'institutions.country_id', '=', 'countries.id')
			->distinct('reports.id')
			->select(DB::raw('reports.*,aces.id as aceID, aces.*,countries.id as countryID,institutions.name as university, countries.*,reports.id'))
			->where('reports.status', '=', $status)
			->where('reports.start_date', '>=', $request->start)
			->where('reports.end_date', '<=', $request->end)
			->orderBy('countries.country', 'asc')
			->get();

		//Filter by ACE
		if ($request->filter == "aces") {
			$reports = $reports->whereIn('ace_id', $request->aces);
		}

		//Filter by field or Country
		if ($request->filter == "field_country") {

			//Filter by Country
			if (isset($request->country)) {
				if (sizeof($request->country) > 0) {
					$reports = $reports->whereIn('countryID', $request->country);
				}
			}
			//Filter by Field
			if (isset($request->field)) {
				if (sizeof($request->field) > 0) {
					$reports = $reports->whereIn('field', $request->field);
				}
			}
		}

		$milestones = Milestone::whereIn('report_id', $reports->pluck('id'))->get();
		$start = $request->start;
		$end = $request->end;
		$process = new CommonFunctions();
		$steps = $process->getInfrastructureProcess();

		if ($request->query->get('export')) {
			// dd("hello");
			return $this->dlrspreadsheet($reports, $steps, $milestones, $process);
		}

		return view('generate-report.milestones.generated-result',
			compact('project', 'reports', 'start', 'end', 'milestones', 'export', 'process', 'steps'));

	}

	public function general_summary_excel(Request $request) {
		return \Maatwebsite\Excel\Facades\Excel::download(new ReportsExport, 'general_summary.xlsx');
	}

	private function dlrspreadsheet($reports, $steps, $milestones, $process) {

		$spreadsheet = new Spreadsheet();
		$styleArray = [
			'font' => [
				'bold' => true,
			],
		];

		$sheet = $spreadsheet->getActiveSheet();
		$sheet->getDefaultColumnDimension()->setWidth(20);

		$sheet->setCellValue('A1', '')->getStyle('A1')->applyFromArray($styleArray);
		$sheet->setCellValue('B1', '')->getStyle('B1')->applyFromArray($styleArray);
		$sheet->setCellValue('C1', 'Action')->getStyle('C1')->applyFromArray($styleArray);
		// $sheet->setCellValue('D1', '')->getStyle('D1')->applyFromArray($styleArray);

		$sheet->setCellValue('A2', '')->getStyle('A2')->applyFromArray($styleArray);
		$sheet->setCellValue('B2', '')->getStyle('B2')->applyFromArray($styleArray);
		$sheet->setCellValue('C2', 'Responsibilty')->getStyle('C2')->applyFromArray($styleArray);

		$sheet->setCellValue('A3', 'Country')->getStyle('A3')->applyFromArray($styleArray);
		$sheet->setCellValue('B3', 'ACE Host University')->getStyle('B3')->applyFromArray($styleArray);
		$sheet->setCellValue('C3', 'ACE	')->getStyle('C3')->applyFromArray($styleArray);
		$sheet->setCellValue('D3', 'Milestones')->getStyle('D3')->applyFromArray($styleArray);

		$columns = range('D', 'Z');
		$counter = 0;

		foreach ($steps as $step) {

			$sheet->setCellValue($columns[$counter + 1] . '1', $process->getStatusLabel($step))->getStyle($columns[$counter + 1] . '1')->applyFromArray($styleArray);

			$sheet->setCellValue($columns[$counter + 1] . '2', $process->getStatusLabelRep($step))->getStyle($columns[$counter + 1] . '2')->applyFromArray($styleArray);

			$sheet->setCellValue($columns[$counter + 1] . '3', $process->getStatusLabel($step))->getStyle($columns[$counter + 1] . '3')->applyFromArray($styleArray);

			$counter++;

		}

		// $sheet->setCellValue('E1', 'Requested verification')->getStyle('E1')->applyFromArray($styleArray);
		// $sheet->setCellValue('F1', 'Verification visit')->getStyle('F1')->applyFromArray($styleArray);
		// $sheet->setCellValue('G1', 'Visit report')->getStyle('G1')->applyFromArray($styleArray);
		// $sheet->setCellValue('H1', 'Shortcomings rectified')->getStyle('H1')->applyFromArray($styleArray);
		// $sheet->setCellValue('I1', 'Final verification letter')->getStyle('I1')->applyFromArray($styleArray);
		// $sheet->setCellValue('J1', 'Disbursement authorization')->getStyle('J1')->applyFromArray($styleArray);
		// $sheet->setCellValue('K1', 'Withdrawal Application Submitted')->getStyle('K1')->applyFromArray($styleArray);
		// $sheet->setCellValue('L1', 'Funds disbursed')->getStyle('L1')->applyFromArray($styleArray);

		// $sheet->setCellValue('D2', '')->getStyle('D2')->applyFromArray($styleArray);
		// $sheet->setCellValue('E2', 'ACE')->getStyle('E2')->applyFromArray($styleArray);
		// $sheet->setCellValue('F2', 'AAU')->getStyle('F2')->applyFromArray($styleArray);
		// $sheet->setCellValue('G2', 'AAU')->getStyle('G2')->applyFromArray($styleArray);
		// $sheet->setCellValue('H2', 'ACE')->getStyle('H2')->applyFromArray($styleArray);
		// $sheet->setCellValue('I2', 'AAU')->getStyle('I2')->applyFromArray($styleArray);
		// $sheet->setCellValue('J2', 'World Bank')->getStyle('J2')->applyFromArray($styleArray);
		// $sheet->setCellValue('K2', 'AAU/Gov')->getStyle('K2')->applyFromArray($styleArray);
		// $sheet->setCellValue('L2', 'World Bank')->getStyle('L2')->applyFromArray($styleArray);

		$row = 4;

		foreach ($reports as $report) {

			$sheet->mergeCells('A' . $row . ':A' . ($report->milestone_no + $row - 1));

			$sheet->getStyle('A' . $row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

			$sheet->setCellValue('A' . $row, $report->country);

			$sheet->mergeCells('B' . $row . ':B' . ($report->milestone_no + $row - 1));

			$sheet->getStyle('B' . $row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

			$sheet->setCellValue('B' . $row, $report->university);

			$sheet->mergeCells('C' . $row . ':C' . ($report->milestone_no + $row - 1));

			$sheet->getStyle('C' . $row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

			$sheet->setCellValue('C' . $row, $report->acronym);

			for ($a = 1; $a <= $report->milestone_no; $a++) {
				$sheet->setCellValue('D' . $row, $a);

				$columns = range('A', 'Z');
				$counter = 0;

				foreach ($steps as $key => $step) {
					$milestone = $milestones->where('status', '=', $step)
						->where('number', '=', $a)
						->where('report_id', '=', $report->id)
						->pluck('status_date')
						->first();

					if (!is_null($milestone)) {

						$sheet->setCellValue($columns[$counter + 4] . $row, date('d M, Y', strtotime($milestone)));

						$counter++;

					} else {

						$sheet->setCellValue($columns[$counter + 4] . $row, '-');

						$counter++;

					}
				}
				if ($a == $report->milestone_no) {

				}
				$row++;

			}

		}

		$writer = new Xls($spreadsheet);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename=" DLR2.8 Log Report.xls"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');

	}

// '$start', '$end', '$reports', '$steps', '$process', '$type_indicator'
	//, $process, $steps
	private function indicatorspreadsheet($reports, $steps, $process, $type_indicator) {

		$spreadsheet = new Spreadsheet();
		$styleArray = [
			'font' => [
				'bold' => true,
			],
		];

		$sheet = $spreadsheet->getActiveSheet();
		$sheet->getDefaultColumnDimension()->setWidth(20);

		$columns = range('A', 'Z');
		$counter = 0;

		foreach ($steps as $step) {

			$sheet->setCellValue($columns[$counter + 2] . '1', $process->getStatusLabelRep($step))->getStyle($columns[$counter + 2] . '1')->applyFromArray($styleArray);

			$sheet->setCellValue($columns[$counter + 2] . '2', $process->getStatusLabel($step))->getStyle($columns[$counter + 2] . '2')->applyFromArray($styleArray);

			$counter++;
			$sheet->setCellValue('A1', '')->getStyle('A1')->applyFromArray($styleArray);
			$sheet->setCellValue('B1', 'Responsibilty')->getStyle('B1')->applyFromArray($styleArray);

		}

		$sheet->setCellValue('A2', '')->getStyle('A2')->applyFromArray($styleArray);

		$sheet->setCellValue('B2', 'Actions')->getStyle('B2')->applyFromArray($styleArray);
		$sheet->setCellValue('A3', 'Country')->getStyle('A3')->applyFromArray($styleArray);
		$sheet->setCellValue('B3', 'Ace')->getStyle('B3')->applyFromArray($styleArray);

		$row = 4;

		foreach ($reports as $report) {

			$getIndicators = $report->report_indicators_status->where('indicator_id', '=', $type_indicator);

			$sheet->setCellValue('A' . $row, $report->ace->university->country->country);
			$sheet->setCellValue('B' . $row, $report->ace->acronym);

			$columns = range('A', 'Z');
			$counter = 0;

			foreach ($steps as $key => $step) {

				$getIndicator = $getIndicators->where('status', '=', $step)->pluck('status_date')->first();

				if (!is_null($getIndicator)) {

					$sheet->setCellValue($columns[$counter + 2] . $row, date('d M, Y', strtotime($getIndicator)));

					$counter++;
				} else {

					$sheet->setCellValue($columns[$counter + 2] . $row, '-');

					$counter++;
				}

			}

			$row++;

		}

		$writer = new Xls($spreadsheet);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename=" Indicator Log Report.xls"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');

	}

	private function generalspreadsheet($report_values, $baseline_values, $target_values, $reports, $project) {
		$spreadsheet = new Spreadsheet();

		$styleArray = [
			'font' => [
				'bold' => true,
			],
		];

		$sheet = $spreadsheet->getActiveSheet();
		$sheet->getDefaultColumnDimension()->setWidth(20);

		$sheet->setCellValue('A1', 'ACE Level Results Indicators')->getStyle('A1')->applyFromArray($styleArray);
		$sheet->setCellValue('B1', 'Core')->getStyle('B1')->applyFromArray($styleArray);
		$sheet->setCellValue('C1', 'Unit of Measure')->getStyle('C1')->applyFromArray($styleArray);
		$sheet->setCellValue('D1', 'Specifics')->getStyle('D1')->applyFromArray($styleArray);
		$sheet->setCellValue('E1', 'Baseline')->getStyle('E1')->applyFromArray($styleArray);
		$sheet->setCellValue('F1', 'CTV')->getStyle('F1')->applyFromArray($styleArray);
		$sheet->setCellValue('G1', 'Results as of October 2018')->getStyle('G1')->applyFromArray($styleArray);

		$row = 2;

		$indicators = $project->indicators->where('parent_id', '=', 0)->where('status', '=', 1)->where('show_on_report', '=', 1);

		foreach ($indicators as $indicator) {

			$spreadsheet->getActiveSheet()->getStyle('A' . $row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

			$sheet->getStyle('A' . $row)->getAlignment()->setWrapText(true);
			$sheet->setCellValue('A' . $row, "Indicator " . $indicator->identifier . " " . $indicator->title);

			$spreadsheet->getActiveSheet()->getStyle('C' . $row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

			$sheet->getStyle('C' . $row)->getAlignment()->setWrapText(true);
			$sheet->setCellValue('C' . $row, $indicator->unit_measure);

			$sub_indicators = $indicator->indicators->where('status', '=', 1)->where('show_on_report', '=', 1);
			$count = 0;

			$counter = $sub_indicators->count();

			$span = $counter + $row - 1;

			if ($span < $row) {
				$span = $row;
			}

			$sheet->mergeCells('A' . $row . ':A' . ($span));
			$sheet->mergeCells('B' . $row . ':B' . ($span));
			$sheet->mergeCells('C' . $row . ':C' . ($span));

			if ($sub_indicators->count() > 1) {
				foreach ($sub_indicators as $sub_indicator) {

					$count += 1;
					try {

						$value = $report_values->where('indicator_id', '=', $sub_indicator->id)->pluck('ind_values');
					} catch (Exception $exception) {
						$value[0] = "N/A";
					}

					$sheet->setCellValue('D' . $row, $sub_indicator->title);

					if (sizeof($baseline_values) > 0) {
						$sheet->setCellValue('E' . $row, $baseline_values[$sub_indicator->id]);
					} else {
						$sheet->setCellValue('E' . $row, '0');
					}

					if (sizeof($target_values) > 0) {
						$sheet->setCellValue('F' . $row, $target_values[$sub_indicator->id]);
					} else {
						$sheet->setCellValue('F' . $row, ' ');
					}

					$sheet->setCellValue('G' . $row, $value[0]);

					if ($count != $counter) {
						$row++;
					}

				}
			} else {

				try {
					$value = $report_values->where('indicator_id', '=', $indicator->id)->pluck('ind_values');
				} catch (Exception $exception) {
					$value[0] = "N/A";

				}
				if (sizeof($baseline_values) > 0) {
					$sheet->setCellValue('E' . $row, $baseline_values[$indicator->id]);
				} else {
					$sheet->setCellValue('E' . $row, '0');

				}

				if (sizeof($target_values) > 0) {

					$sheet->setCellValue('F' . $row, $target_values[$indicator->id]);
				} else {
					$sheet->setCellValue('F' . $row, '0');
				}

				$sheet->setCellValue('G' . $row, $value[0]);
				// $row++;
			}

			$row++;

		}

		$writer = new Xls($spreadsheet);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename=" General Log Report.xls"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');

	}

}
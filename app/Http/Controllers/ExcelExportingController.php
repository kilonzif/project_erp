<?php

namespace App\Http\Controllers;
use App\Exports\ReportExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExportController extends Controller {
	public function export() {
		// return Excel::download(new YourExport);
		return Excel::download(new ReportExport, 'report.xlsx');

	}
}

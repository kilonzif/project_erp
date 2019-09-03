<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SpreadSheetController extends Controller {
	//

	public function spreadsheet() {

		$spreadsheet = new Spreadsheet();

		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Country');
		$sheet->setCellValue('B1', 'Ace');
		$sheet->setCellValue('C1', 'Letter Dated');
		$sheet->setCellValue('D1', 'Date Dispatched');
		$sheet->setCellValue('E1', 'Payment');
		$sheet->setCellValue('F1', 'Amount Due');
		$sheet->setCellValue('G1', 'Total');

		$writer = new Xlsx($spreadsheet);
		$writer->save('hello worldfive.xlsx');

	}

}

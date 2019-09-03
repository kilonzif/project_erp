<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExcelImportController extends Controller
{
    //
    public function read()
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load("excel/downloads/hello world.xlsx");
        var_dump($spreadsheet);
    }
}

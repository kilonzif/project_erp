<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class ValidateDistricts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validate:locations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validating the districts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');
        $file_name = 'districts.xlsx';
        try {
            $spreadsheet = IOFactory::load(Storage::path($file_name));
            $worksheet = $spreadsheet->getActiveSheet();
            $bar = $this->output->createProgressBar($worksheet->getHighestDataRow());
            $bar->display();
            for ($row = 1; $row <= $worksheet->getHighestDataRow(); $row++) {
                $id = $worksheet->getCell("A$row")->getValue();
                $location_id = $worksheet->getCell("B$row")->getValue();
                $name = $worksheet->getCell("D$row")->getValue();
                $additions = $worksheet->getCell("E$row")->getValue();

                if (empty($location_id) || $location_id == "NULL") {
                    $bar->advance();
                    continue;
                }

                DB::transaction(function () use ($id, $location_id, $name,$additions) {
                    if (!empty($id) || $id == "NULL") {
                        $this->updateRecord('directory_platform_locations',$id,
                            [
                                'location_id'=>$location_id,
                                'name'=>$name,
                                'status'=>true
                            ]);
                    } else {
                        $this->createRecord('directory_platform_locations',
                            [
                                'location_id'=>$location_id,
                                'name'=>$name,
                                'status'=>true
                            ]);
                    }
                    if (!empty($additions) || $additions != "") {
                        $getIds = explode(';',trim($additions));
//                        dd($getIds);
                        DB::table('directory_platform_listings')
                            ->whereIn('district_id',$getIds)
                            ->update(['district_id'=>$id,'location_id'=>$location_id]);
                        DB::table('directory_platform_locations')
                            ->whereIn('id',$getIds)
                            ->update(['status'=>false]);
                    }
                });
                $bar->advance();
            }
            $bar->finish();
        } catch (Exception $e) {
//            dd($e);
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
//            dd($e);
        }
    }

    /**
     * @param $table
     * @param $id
     * @param array $params
     */
    private function updateRecord($table, $id,array $params)
    {
        DB::table($table)
            ->where('id','=',$id)
            ->update($params);
    }

    /**
     * @param $table
     * @param $values
     * @return int
     */
    public function createRecord($table, $values)
    {
        return DB::table($table)->insertGetId($values);
    }
}

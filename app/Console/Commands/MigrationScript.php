<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use function Symfony\Component\VarDumper\Dumper\esc;

class MigrationScript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        Config::set('database.connections.mysql.database', 'service_mapping');
        DB::purge('mysql');

        $file_name = 'Mapping_and_Directory_of_Services_and_District_Based_Referral_System_results.xlsx';

        try {
            $spreadsheet = IOFactory::load(Storage::path($file_name));
            $worksheet = $spreadsheet->getActiveSheet();
            $bar = $this->output->createProgressBar($worksheet->getHighestDataRow());
            $bar->display();

            for ($row = 2; $row <= $worksheet->getHighestDataRow(); $row++) {
                $serviceProviderName = $worksheet->getCell("D$row")->getValue();
                $region = $worksheet->getCell("L$row")->getValue();
                $district = $worksheet->getCell("M$row")->getValue();
                $latitude = $worksheet->getCell("H$row")->getValue();
                $longitude = $worksheet->getCell("I$row")->getValue();
                $address = $worksheet->getCell("X$row")->getValue();
                $telephone = $worksheet->getCell("Y$row")->getValue();
                $email = $worksheet->getCell("AA$row")->getValue();
                $website = $worksheet->getCell("Z$row")->getValue();
                $opening_hours_time = $worksheet->getCell("AC$row")->getValue();
                $opening_hours_days = $worksheet->getCell("AD$row")->getValue();
                $type = $worksheet->getCell("AG$row")->getValue();
                $town = $worksheet->getCell("N$row")->getValue();

                $categories = $worksheet->getCell("Q$row")->getValue();
                $serviceProviderName = trim($serviceProviderName);


                if (empty($serviceProviderName)) {
                    $bar->advance();
                    continue;
                }


                $region = trim($region);
                $district = trim(str_replace('_', ' ', $district));

                DB::transaction(function () use ($categories, $town, $opening_hours_time, $opening_hours_days, $longitude,
                    $latitude, $email, $website, $telephone, $address, $region, $district,$serviceProviderName, $type) {
                    if (!empty($region)) {
                        if ($record = $this->getRecord("directory_platform_locations", ["slug" => $region])) {
                            $region_id = $record->id;
                        }
//                        else $region_id = $this->insertRecord("directory_platform_locations",
//                            [
//                                "name" => str_replace('_', ' ', $region),
//                                "slug" => $region
//                            ]);
                    }

                    if (!empty($district)) {
                        if ($record = $this->getRecord("directory_platform_locations", ["slug" => $district])) {
                            $district_id = $record->id;
                        }
//                        else $district_id = $this->insertRecord("directory_platform_locations",
//                            [
//                                "name" => str_replace('_', ' ', $district),
//                                "slug" => $district,
//                                "location_id" => $region_id ?? null
//                            ]);
                    }

//                    continue;
                    $listing_id = $this->insertRecord("directory_platform_listings",
                        [
                            "location_id" => $region_id ?? $district_id ?? null,
                            "type" => trim(str_replace('_', ' ', $type)),
                            "name" => $serviceProviderName,
                            "address" => $address,
                            "slug" => str_slug($serviceProviderName.'_'.str_random(4)),
                            "telephone" => $telephone,
                            "website" => $website,
                            "email" => $email,
                            "latitude" => $latitude,
                            "longitude" => $longitude,
                            "opening_hours" => $opening_hours_days . ' ' . $opening_hours_time,
                        ]);


                    $categories = explode(" ", trim($categories));

                    foreach ($categories as $category) {
                        if ($record = $this->getRecord("directory_platform_categories", ["slug" => trim($category)])) {
                            $this->insertRecord("directory_platform_listing_category", [
                                "listing_id" => $listing_id,
                                "category_id" => $record->id,
                            ]);
                        } else $this->insertRecord("directory_platform_categories", [
                            "name" => str_replace('_', ' ', $category),
                            "slug" => $category,
                        ]);


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
     * @param $table string
     * @param $params array
     * @return Model|Builder|object|null
     */
    private function getRecord($table, $params)
    {
        $query = DB::table($table);

        foreach ($params as $column => $param) {
            $query->where($column, $param);
        }

        return $query->first();
    }

    /**
     * @param $table
     * @param $values
     * @return int
     */
    public function insertRecord($table, $values)
    {
        return DB::table($table)->insertGetId($values);
    }
}
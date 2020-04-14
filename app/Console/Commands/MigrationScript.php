<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
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
        $file_name = 'listings.xlsx';
        try {
            $spreadsheet = IOFactory::load(Storage::path($file_name));
            $worksheet = $spreadsheet->getActiveSheet();
            $bar = $this->output->createProgressBar($worksheet->getHighestDataRow());
            $bar->display();
            for ($row = 2; $row <= $worksheet->getHighestDataRow(); $row++) {
                $abbrev = $worksheet->getCell("C$row")->getValue();
                $serviceProviderName = $worksheet->getCell("D$row")->getValue();
                $region = $worksheet->getCell("L$row")->getValue();
                $district = $worksheet->getCell("M$row")->getValue();
                $latitude = $worksheet->getCell("H$row")->getValue();
                $longitude = $worksheet->getCell("I$row")->getValue();
                $address = $worksheet->getCell("X$row")->getValue();
                $telephone = str_replace(",","/",$worksheet->getCell("Y$row")->getValue());
                $email = $worksheet->getCell("AA$row")->getValue();
                $website = $worksheet->getCell("Z$row")->getValue();
                $opening_hours_time = $worksheet->getCell("AC$row")->getValue();
                $opening_hours_days = $worksheet->getCell("AD$row")->getValue();
                $type = $worksheet->getCell("AH$row")->getValue();
                $town = $worksheet->getCell("N$row")->getValue();
                $cp_name = $worksheet->getCell("F$row")->getValue();
                $cp_contact = $worksheet->getCell("G$row")->getValue();
                $cp_position = $worksheet->getCell("E$row")->getValue();
                $type_of_organization = $worksheet->getCell("Q$row")->getValue();
                $sex_group = $worksheet->getCell("P$row")->getValue();
                $age_group = $worksheet->getCell("O$row")->getValue();
                $categories = $worksheet->getCell("AG$row")->getValue();
                $serviceProviderName = trim($serviceProviderName);
                if (empty($serviceProviderName)) {
                    $bar->advance();
                    continue;
                }
                $region = trim($region);
                $district = trim(str_replace('_', ' ', $district));
                DB::transaction(function () use (
                    $cp_contact,
                    $abbrev,
                    $cp_name,
                    $type_of_organization,
                    $sex_group,
                    $cp_position,
                    $age_group,
                    $categories, $town, $opening_hours_time, $opening_hours_days, $longitude,
                    $latitude, $email, $website, $telephone, $address, $region, $district, $serviceProviderName, $type
                ) {
                    if (!empty($region)) {
                        if ($record = $this->getRecord("directory_platform_locations", ["slug" => $region])) {
                            $region_id = $record->id;
                        } else $region_id = $this->insertRecord("directory_platform_locations",
                            [
                                "name" => Str::title(str_replace('_', ' ', $region)),
                                "slug" => $region
                            ]);
                    }
                    if (!empty($district)) {
                        if ($record = $this->getRecord("directory_platform_locations", ["slug" => $district])) {
                            $district_id = $record->id;
                        } else $district_id = $this->insertRecord("directory_platform_locations",
                            [
                                "name" => Str::title(str_replace('_', ' ', $district)),
                                "slug" => $district,
                                "location_id" => $region_id ?? null
                            ]);
                    }
                    $listing_id = $this->insertRecord("directory_platform_listings",
                        [
                            "location_id" => $region_id ?? null,
                            "district_id" => $district_id ?? null,
                            "description" => ucwords(trim(str_replace('_', ' ', str_replace(' ', ' | ', $type)))),
                            "name" => ucwords($serviceProviderName),
                            "abbrev" => ucwords($abbrev),
                            "address" => $address,
                            "slug" => Str::slug($serviceProviderName . '_' . Str::random(4)),
                            "telephone" => $this->formatTel(str_replace(" ", "",$telephone)),
                            "website" => $website,
                            "email" => strtolower($email),
                            "latitude" => $latitude,
                            "longitude" => $longitude,
                            "age_group" => ucwords(str_replace("_", " ", $age_group)),
                            "sex_group" => ucwords(str_replace("_", " ", $sex_group)),
                            "type_of_organization" => ucwords(trim(str_replace('_', ' ', str_replace(' ', ' | ', $type_of_organization)))),
                            "cp_name" => ucfirst(str_replace("_", " ", $cp_name)),
                            "cp_contact" => str_replace(" ", "",$cp_contact),
                            "cp_position" => $cp_position,
                            "town_of_operation" => $town,
                            "opening_hours" => $opening_hours_days . ' ' . $opening_hours_time,
                            "is_published" => true,
                            "type" => (!empty($latitude) and !empty($longitude)) ? 'MAP' : 'NONE',
                        ]);
                    $categories = explode(" ", trim($categories));
                    foreach ($categories as $category) {
                        if (empty(trim($category))) continue;
                        if ($record = $this->getRecord("directory_platform_categories", ["slug" => trim($category)])) {
                            $this->insertRecord("directory_platform_listing_category", [
                                "listing_id" => $listing_id,
                                "category_id" => $record->id,
                            ]);
                        } else $this->insertRecord("directory_platform_categories", [
                            "name" => ucwords(str_replace('_', ' ', $category)),
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
    /**
     * @param $contact
     * @return string
     */
    public function formatTel($contact)
    {
        $format_contact = $contact;
        if (substr($contact,0,1) != 0){
            $format_contact = "0".$contact;
        }
        return $format_contact;
    }
}
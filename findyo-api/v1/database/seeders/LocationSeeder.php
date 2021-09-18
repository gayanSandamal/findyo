<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('locations')->delete();
        $Locationjson = File::get("database/data/locations.json");
        $data = json_decode($Locationjson);
        $locationId = 0;
        foreach ($data->Provinces as $province) {
            $provinceId = ++$locationId;
            $this->insertLocation($locationId, $province, 1, NULL);
            foreach ($province->Districts as $district) {
                $districtId = ++$locationId;
                $this->insertLocation($locationId, $district, 2, $provinceId);
                foreach ($district->Cities as $city) {
                    $cityId = ++$locationId;
                    $this->insertLocation($locationId, $city, 3, $districtId);
                }
            }
        }
    }
    private function insertLocation($id, $location, $level, $parentId)
    {

        Location::create(array(
            'id' => $id,
            'name' => $location->Name,
            'parent' => $parentId ? $parentId : NULL,
            'location_level_id' => $level
        ));
    }
}

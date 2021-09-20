<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationlevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('location_levels')->insert([
            ['id' => 1, 'name' => 'Province'],
            ['id' => 2, 'name' => 'District'],
            ['id' => 3, 'name' => 'City']
        ]);
    }
}

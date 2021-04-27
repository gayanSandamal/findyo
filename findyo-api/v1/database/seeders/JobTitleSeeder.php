<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('job_titles')->insert(
            ['name' => 'Quality Assuarence Engineer 1'],
            ['name' => 'Quality Assuarence Engineer 2'],
            ['name' => 'Quality Assuarence Engineer 3'],
            ['name' => 'Quality Assuarence Engineer 4'],
            ['name' => 'Quality Assuarence Engineer 5'],
            ['name' => 'Quality Assuarence Engineer 6'],
            ['name' => 'Quality Assuarence Engineer 7'],
            ['name' => 'Quality Assuarence Engineer 8'],
            ['name' => 'Quality Assuarence Engineer 9'],
            ['name' => 'Quality Assuarence Engineer 10'],
            ['name' => 'Quality Assuarence Engineer 11'],
            ['name' => 'Quality Assuarence Engineer 12'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],
            ['name' => 'Quality Assuarence Engineer'],

        );
    }
    private function generateDummyData()
    {
        $a1 = array();
        $x = 0;
        while ($x <= 100000) {
            $a1 = array_merge($a1, ["name" => "SE " . $x++]);
        }

        return $a1;
    }
}

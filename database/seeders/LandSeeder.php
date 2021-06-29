<?php

namespace Database\Seeders;

use Flynsarmy\CsvSeeder\CsvSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = file_get_contents(database_path() . '/seeders/sql/lands.sql');
        DB::unprepared($sql);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Land;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Flynsarmy\CsvSeeder\CsvSeeder;
use Illuminate\Support\Facades\DB;

class LandSeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->table = 'lands';
        $this->filename = database_path().'/seeders/csvs/land.csv';
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::disableQueryLog();

        DB::table($this->table);

        parent::run();
    }
}

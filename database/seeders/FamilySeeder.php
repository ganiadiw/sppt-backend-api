<?php

namespace Database\Seeders;

use Flynsarmy\CsvSeeder\CsvSeeder;
use Illuminate\Support\Facades\DB;

class FamilySeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->table = 'families';
        $this->filename = database_path().'/seeders/csvs/family.csv';
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

<?php

namespace Database\Seeders;

use App\Models\Owner;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Flynsarmy\CsvSeeder\CsvSeeder;
use Illuminate\Support\Facades\DB;

class OwnerSeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->table = 'owners';
        $this->filename = database_path().'/seeders/csvs/owner.csv';
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
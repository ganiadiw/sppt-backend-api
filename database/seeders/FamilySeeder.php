<?php

namespace Database\Seeders;

use App\Models\Family;
use Illuminate\Database\Seeder;
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

        Family::create([
            'name' => 'Akbaruddin Nurullah',
        ]);
        Family::create([
            'name' => 'Sobari',
        ]);
        Family::create([
            'name' => 'Gani Adi Wiranata',
        ]);
        Family::create([
            'name' => 'Jadid',
        ]);
        Family::create([
            'name' => 'Badris Sholeh Rahmatullah',
        ]);
        Family::create([
            'name' => 'Muhammad Iqbal Naufal Ilmi',
        ]);
        
    }
}

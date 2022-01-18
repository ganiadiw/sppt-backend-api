<?php

namespace Database\Seeders;

use App\Models\Land;
use Faker\Factory as Faker;
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
        $faker = Faker::create('ID_id');
        // $owner = DB::table('owners')->count();
        $guardian = DB::table('guardians')->count();
        
        for ($i=1; $i < 100; $i++) { 
            $blockNumber = $faker->randomNumber(3, true);

            Land::create([
                'nop' => 3503010111 . $blockNumber . $faker->randomNumber(4, true) . 7,
                'owner_id' => $i,
                'guardian_id' => $faker->numberBetween(1, $guardian),
                'rt' => $faker->randomElement(['001', '002', '003', '004', '005', '006', '007', '008', '009', '010']),
                'rw' => $faker->randomElement(['001', '002', '003', '004', '005', '006', '007', '008', '009', '010']),
                'village' => 'Sanankulon',
                'road' => $faker->streetName(),
                'block_number' => $blockNumber,
                'land_area' => $faker->randomNumber(3, true),
                'land_area_unit' => 'ha',
                'building_area' => $faker->randomNumber(3, true),
                'building_area_unit' => 'm2',
            ]);
        }

    }
}

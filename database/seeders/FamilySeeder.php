<?php

namespace Database\Seeders;

use App\Models\Family;
use Faker\Factory as Faker;
use Flynsarmy\CsvSeeder\CsvSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        for ($i=1; $i < 50; $i++) { 
            Family::create([
                'name' => $faker->firstName() . ' ' . $faker->lastName(),
                'rt' => $faker->randomElement(['001', '002', '003', '004', '005', '006', '007', '008', '009', '010']),
                'rw' => $faker->randomElement(['001', '002', '003', '004', '005', '006', '007', '008', '009', '010']),
                'village' => 'Sanankulon',
                'road' => $faker->streetName(),
            ]);
        }
    }
}

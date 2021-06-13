<?php

namespace Database\Seeders;

use App\Models\Land;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
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
        Land::create([
            'nop' => 350518000600100010,
            'owner_id' => 1,
            'name' => 'Jadid',
            'guardian_id' => 1,
            'rt' => '001',
            'rw' => '001',
            'village' => 'Desa Sumberjo',
            'road' => 'Desa Sumberjo',
            'determination' => 1000000,
            'block_number' => 'A1',
            'land_area' => 50,
            'land_area_unit' => 'm2',
            'building_area' => 25,
            'building_area_unit' => 'm2',
        ]);
        Land::create([
            'nop' => 350518000600110010,
            'owner_id' => 2,
            'name' => 'Gani',
            'guardian_id' => 1,
            'rt' => '001',
            'rw' => '001',
            'village' => 'Desa Sumberjo',
            'road' => 'Desa Sumberjo',
            'determination' => 1000000,
            'block_number' => 'A1',
            'land_area' => 50,
            'land_area_unit' => 'm2',
            'building_area' => 25,
            'building_area_unit' => 'm2',
        ]);

        //15
        $faker = Faker::create('id_ID');
        for ($i = 3; $i <= 50; $i++) {
            $landArea = $faker->numberBetween(200, 500);
            Land::create([
                'nop' => 3505180006 . $faker->numberBetween(0000000, 9999999) . 0,
                'owner_id' => $i,
                'name' => $faker->firstName() . ' ' . $faker->lastName(),
                'guardian_id' => $faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8]),
                'rt' => $faker->randomElement(['001', '002', '003', '004', '005', '006', '008', '009', '010']),
                'rw' => $faker->randomElement(['001', '002', '003', '004']),
                'village' => 'Desa Sumberjo',
                'road' => 'Desa Sumberjo',
                'determination' => $faker->numberBetween(50000, 150000),
                'block_number' =>  $faker->randomElement(['A1', 'A2', 'A3', 'A4', 'A5', 'B1', 'B2', 'B3', 'B4', 'B5', 'C1', 'C2']),
                'land_area' => $landArea,
                'land_area_unit' => 'm2',
                'building_area' => $landArea - 150,
                'building_area_unit' => 'm2'
            ]);
        }
    }
}

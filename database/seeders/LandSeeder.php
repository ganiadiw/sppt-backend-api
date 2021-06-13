<?php

namespace Database\Seeders;

use App\Models\Land;
use Illuminate\Database\Seeder;

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
            'nop' => 350501001000100010,
            'owner_id' => 1,
            'name' => 'Jadid',
            'guardian_id' => 1,
            'rt' => '001',
            'rw' => '001',
            'village' => 'Desa Sumberjo',
            'road' => 'Desa Sumberjo',
            'determination' => 1000000,
            'land_area' => 50,
            'land_area_unit' => 'm2',
            'building_area' => 25,
            'building_area_unit' => 'm2',
        ]);
        Land::create([
            'nop' => 350501001000110010,
            'owner_id' => 2,
            'name' => 'Gani',
            'guardian_id' => 1,
            'rt' => '001',
            'rw' => '001',
            'village' => 'Desa Sumberjo',
            'road' => 'Desa Sumberjo',
            'determination' => 1000000,
            'land_area' => 50,
            'land_area_unit' => 'm2',
            'building_area' => 25,
            'building_area_unit' => 'm2',
        ]);
    }
}

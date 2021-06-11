<?php

namespace Database\Seeders;

use App\Models\Owner;
use Illuminate\Database\Seeder;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Owner::create([
            'family_id' => 1,
            'name' => 'Jadid',
            'rt' => '001',
            'rw' => '001',
            'village' => 'Desa Sumberjo',
            'road' => 'Desa Sumberjo',
        ]);
        Owner::create([
            'family_id' => 1,
            'name' => 'Gani',
            'rt' => '002',
            'rw' => '003',
            'village' => 'Desa Sumberjo',
            'road' => 'Desa Sumberjo',
        ]);
        Owner::create([
            'family_id' => 2,
            'name' => 'Adi',
            'rt' => '001',
            'rw' => '001',
            'village' => 'Desa Sumberjo',
            'road' => 'Desa Sumberjo',
        ]);
        Owner::create([
            'family_id' => 2,
            'name' => 'Gani',
            'rt' => '002',
            'rw' => '003',
            'village' => 'Desa Sumberjo',
            'road' => 'Desa Sumberjo',
        ]);
    }
}

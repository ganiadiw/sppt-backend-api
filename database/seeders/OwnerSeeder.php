<?php

namespace Database\Seeders;

use App\Models\Owner;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

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
            'family_id' => 4,
            'name' => 'Jadid',
            'rt' => '002',
            'rw' => '003',
            'village' => 'Desa Sumberjo',
            'road' => 'Desa Sumberjo',
        ]);
        Owner::create([
            'family_id' => 5,
            'name' => 'Akbar',
            'rt' => '003',
            'rw' => '005',
            'village' => 'Desa Sumberjo',
            'road' => 'Desa Sumberjo',
        ]);
        Owner::create([
            'family_id' => 6,
            'name' => 'Nurullah',
            'rt' => '003',
            'rw' => '005',
            'village' => 'Desa Sumberjo',
            'road' => 'Desa Sumberjo',
        ]);
        Owner::create([
            'family_id' => 1,
            'name' => 'Badris',
            'rt' => '003',
            'rw' => '005',
            'village' => 'Desa Sumberjo',
            'road' => 'Desa Sumberjo',
        ]);
        Owner::create([
            'family_id' => 2,
            'name' => 'Sholeh',
            'rt' => '003',
            'rw' => '005',
            'village' => 'Desa Sumberjo',
            'road' => 'Desa Sumberjo',
        ]);
        Owner::create([
            'family_id' => 5,
            'name' => 'Iqbal',
            'rt' => '003',
            'rw' => '005',
            'village' => 'Desa Sumberjo',
            'road' => 'Desa Sumberjo',
        ]);
        Owner::create([
            'family_id' => 2,
            'name' => 'Naufal',
            'rt' => '003',
            'rw' => '005',
            'village' => 'Desa Sumberjo',
            'road' => 'Desa Sumberjo',
        ]);

        $faker = Faker::create('id_ID');
        $family = DB::table('families')->count();

        for ($i = 1; $i <= 43; $i++) {
            Owner::create([
                'family_id' => $faker->numberBetween(1, $family),
                'name' => $faker->firstName() . ' ' . $faker->lastName(),
                'rt' => $faker->randomElement(['001', '002', '003', '004', '005', '006', '008', '009', '010']),
                'rw' => $faker->randomElement(['001', '002', '003', '004']),
                'village' => 'Desa Sumberjo',
                'road' => 'Desa Sumberjo'
            ]);
        }
    }
}
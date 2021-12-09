<?php

namespace Database\Seeders;

use App\Models\Owner;
use Faker\Factory as Faker;
use Flynsarmy\CsvSeeder\CsvSeeder;
use Illuminate\Database\Seeder;
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
        // $sql = file_get_contents(database_path() . '/seeders/sql/owners.sql');
        // DB::unprepared($sql);

        $faker = Faker::create('id_ID');
        $family = DB::table('families')->count();

        for ($i=1; $i < 200; $i++) { 
            Owner::create([
                'family_id' => $faker->numberBetween(1, $family),
                'name' => $faker->firstName() . ' ' . $faker->lastName(),
                'rt' => $faker->randomElement(['001', '002', '003', '004', '005', '006', '007', '008', '009', '010']),
                'rw' => $faker->randomElement(['001', '002', '003', '004', '005', '006', '007', '008', '009', '010']),
                'village' => 'Sanankulon',
                'road' => $faker->streetName(),
            ]);
        }
    }
}
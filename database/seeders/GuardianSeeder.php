<?php

namespace Database\Seeders;

use App\Models\Guardian;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class GuardianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        Guardian::create([
            'id' => '1',
            'name' => $faker->firstName() . ' ' . $faker->lastName(),
        ]);
        Guardian::create([
            'id' => '2',
            'name' => $faker->firstName() . ' ' . $faker->lastName(),
        ]);
        Guardian::create([
            'id' => '3',
            'name' => $faker->firstName() . ' ' . $faker->lastName(),
        ]);
        Guardian::create([
            'id' => '4',
            'name' => $faker->firstName() . ' ' . $faker->lastName(),
        ]);
        Guardian::create([
            'id' => '5',
            'name' => $faker->firstName() . ' ' . $faker->lastName(),
        ]);
        Guardian::create([
            'id' => '6',
            'name' => $faker->firstName() . ' ' . $faker->lastName(),
        ]);
        Guardian::create([
            'id' => '7',
            'name' => $faker->firstName() . ' ' . $faker->lastName(),
        ]);
        Guardian::create([
            'id' => '8',
            'name' => $faker->firstName() . ' ' . $faker->lastName(),
        ]);
    }
}

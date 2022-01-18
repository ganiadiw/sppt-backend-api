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
        collect([
            [
                'id' => '1',
                'name' => 'Tezar Yohanang Wiranata',
            ],
            [
                'id' => '2',
                'name' => 'Moh. Muslih',
            ],
            [
                'id' => '3',
                'name' => 'M. Chandra Adi Pratama',
            ],
            [
                'id' => '4',
                'name' => 'Marbani',
            ],
            [
                'id' => '5',
                'name' => 'Eko Suharianto',
            ],
            [
                'id' => '6',
                'name' => 'Prawoto',
            ],
            [
                'id' => '7',
                'name' => 'Judi Budi Santosa',
            ],
            [
                'id' => '8',
                'name' => 'Sujito',
            ]
        ])->each(function ($guardian) {
            Guardian::create($guardian);
        });
    }
}

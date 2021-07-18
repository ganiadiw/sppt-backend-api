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
        Guardian::create([
            'id' => '1',
            'name' => 'Tezar Yohanang Wiranata',
        ]);
        Guardian::create([
            'id' => '2',
            'name' => 'Moh. Muslih',
        ]);
        Guardian::create([
            'id' => '3',
            'name' => 'M. Chandra Adi Pratama',
        ]);
        Guardian::create([
            'id' => '4',
            'name' => 'Marbani',
        ]);
        Guardian::create([
            'id' => '5',
            'name' => 'Eko Suharianto',
        ]);
        Guardian::create([
            'id' => '6',
            'name' => 'Prawoto',
        ]);
        Guardian::create([
            'id' => '7',
            'name' => 'Judi Budi Santosa',
        ]);
        Guardian::create([
            'id' => '8',
            'name' => 'Sujito',
        ]);
    }
}

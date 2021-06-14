<?php

namespace Database\Seeders;

use App\Models\Family;
use Illuminate\Database\Seeder;

class FamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Family::create([
            'name' => 'Akbaruddin Nurullah',
        ]);
        Family::create([
            'name' => 'Sobari',
        ]);
        Family::create([
            'name' => 'Gani Adi Wiranata',
        ]);
        Family::create([
            'name' => 'Jadid',
        ]);
        Family::create([
            'name' => 'Badris Sholeh Rahmatullah',
        ]);
        Family::create([
            'name' => 'Muhammad Iqbal Naufal Ilmi',
        ]);
        
    }
}
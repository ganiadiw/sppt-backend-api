<?php

namespace Database\Seeders;

use App\Models\TaxHistory;
use Illuminate\Database\Seeder;

class TaxHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaxHistory::create([
            'land_id' => '1',
            'year' => 2020,
            'amount' => 153000,
            'payment_status' => 'lunas'
        ]);
        TaxHistory::create([
            'land_id' => '1',
            'year' => 2021,
            'amount' => 155000,
            'payment_status' => 'belum bayar'
        ]);
    }
}

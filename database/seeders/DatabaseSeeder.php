<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(GuardianSeeder::class);
        $this->call(FamilySeeder::class);
        $this->call(OwnerSeeder::class);
        $this->call(LandSeeder::class);
        $this->call(TaxHistorySeeder::class);
    }

}

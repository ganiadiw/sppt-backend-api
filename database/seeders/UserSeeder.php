<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $superAdmin = User::create([
            'name' => 'Jadid',
            'username' => 'jadid99',
            'email' => 'jadid@example.com',
            'password' => bcrypt('12345678'),
            'occupation' => 'Sekretaris Desa',
            'role' => 'Super Admin'
        ]);

        $superAdmin->assignRole('Super Admin');

        $admin = User::create([
            'name' => 'Akbar',
            'username' => 'akbar99',
            'email' => 'akbar@example.com',
            'password' => bcrypt('87654321'),
            'occupation' => 'Kepala Bidang Pemerintahan',
            'role' => 'Admin'
        ]);

        $admin->assignRole('Admin');
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

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
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('superadmin'),
            'occupation' => 'Sekretaris Desa',
            'role' => 'super admin'
        ]);

        $superAdmin->assignRole('super admin');

        $admin = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin'),
            'occupation' => 'Pamong 1',
            'role' => 'admin'
        ]);

        $admin->assignRole('admin');
    }
}

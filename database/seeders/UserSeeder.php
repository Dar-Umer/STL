<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@soillab.com',
            'password' => 'password',
            'phone' => '9999999999',
            'is_active' => true,
        ]);
        $admin->assignRole('Admin');

        $manager = User::create([
            'name' => 'Lab Manager',
            'email' => 'manager@soillab.com',
            'password' => 'password',
            'phone' => '9999999998',
            'is_active' => true,
            'lab_id' => 1,
        ]);
        $manager->assignRole('Lab Manager');

        $tech = User::create([
            'name' => 'Lab Technician',
            'email' => 'tech@soillab.com',
            'password' => 'password',
            'phone' => '9999999997',
            'is_active' => true,
            'lab_id' => 1,
        ]);
        $tech->assignRole('Lab Technician');

        $field = User::create([
            'name' => 'Field Official',
            'email' => 'field@soillab.com',
            'password' => 'password',
            'phone' => '9999999996',
            'is_active' => true,
            'lab_id' => 1,
        ]);
        $field->assignRole('Field Official');
    }
}

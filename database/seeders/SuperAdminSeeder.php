<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'username' => 'superadmin',
            'password' => bcrypt('p@ssword'),
            'email' => 'admin@example.com',
            'role' => 'super_admin',
        ]);
    }
}

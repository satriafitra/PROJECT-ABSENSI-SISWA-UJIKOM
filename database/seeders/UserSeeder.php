<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ADMIN
        User::create([
            'name' => 'Admin Absensi',
            'email' => 'admin@absensi.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // GURU
        User::create([
            'name' => 'Guru Absensi',
            'email' => 'guru@absensi.com',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
        ]);
    }
}

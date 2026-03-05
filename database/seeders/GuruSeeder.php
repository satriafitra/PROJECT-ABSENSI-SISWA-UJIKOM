<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guru;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 20; $i++) {
            Guru::create([
                'nama'     => 'Guru ' . $i,
                'email'    => 'guru'.$i.'@mail.com',
                'password' => Hash::make('123456'),
                'status'   => 'aktif'
            ]);
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classes;

class ClassesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'X RPL 1'],
            ['name' => 'X RPL 2'],
            ['name' => 'XI TJKT 1'],
            ['name' => 'XI TJKT 2'],
            ['name' => 'XII RPL 1'],
        ];

        foreach ($data as $class) {
            Classes::create($class);
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Guru;
use App\Models\Classes;

class ScheduleSeeder extends Seeder
{
    public function run()
    {
        $gurus = Guru::where('status', 'aktif')->get();
        $classes = Classes::all();

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        $subjects = [
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'Pemrograman Web',
            'Basis Data',
            'Jaringan Komputer',
            'PKN',
            'Sejarah',
            'PJOK',
            'Produktif RPL'
        ];

        $timeSlots = [
            ['07:00:00', '08:30:00'],
            ['08:30:00', '10:00:00'],
            ['10:15:00', '11:45:00'],
            ['12:30:00', '14:00:00'],
        ];

        foreach ($classes as $class) {

            foreach ($days as $day) {

                foreach ($timeSlots as $slot) {

                    $guru = $gurus->random();

                    Schedule::create([
                        'guru_id'   => $guru->id,
                        'class_id'  => $class->id,
                        'day'       => $day,
                        'subject'   => $subjects[array_rand($subjects)],
                        'time_start'=> $slot[0],
                        'time_end'  => $slot[1],
                        'room'      => $class->name,
                        'is_break'  => false,
                    ]);
                }
            }
        }
    }
}
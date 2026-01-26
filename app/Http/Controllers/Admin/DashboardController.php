<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Classes;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Student::count();
        $totalGuru  = User::where('role', 'guru')->count();
        $totalKelas = Classes::count();

        $today = Carbon::today();

        $hadir = Attendance::whereDate('date', $today)
            ->where('status', 'hadir')
            ->count();

        $telat = Attendance::whereDate('date', $today)
            ->where('status', 'telat')
            ->count();

        $alpha = Attendance::whereDate('date', $today)
            ->where('status', 'alfa')
            ->count();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalGuru',
            'totalKelas',
            'hadir',
            'telat',
            'alpha'
        ));
    }
}

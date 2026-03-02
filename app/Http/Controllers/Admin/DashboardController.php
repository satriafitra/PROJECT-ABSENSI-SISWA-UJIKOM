<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Classes;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request; // Tambahkan ini untuk menangani filter

class DashboardController extends Controller
{
    /**
     * Menampilkan Halaman Dashboard Utama Admin
     */
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

    /**
     * Menampilkan Halaman Rekap Absensi untuk Admin
     */
    public function rekapAbsensi(Request $request)
    {
        // 1. Mengambil filter tanggal dari input, default adalah hari ini
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        
        // 2. Mengambil input pencarian nama siswa
        $search = $request->input('search');

        // 3. Query data absensi
        // Menggunakan 'with' untuk eager loading agar performa cepat (menghindari N+1 query)
        $attendances = Attendance::with(['student.class', 'guru'])
            ->whereDate('date', $date)
            ->when($search, function($query) use ($search) {
                // Filter berdasarkan nama siswa di tabel students
                $query->whereHas('student', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->latest() // Data terbaru di atas
            ->paginate(10); // Menggunakan pagination agar sesuai dengan file Blade

        // 4. Mengirimkan variabel ke view admin/rekapabsensi.blade.php
        return view('admin.rekapabsensi', compact('attendances', 'date'));
    }
}
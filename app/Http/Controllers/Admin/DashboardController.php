<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Classes;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan Halaman Dashboard Utama Admin
     */
    public function index()
    {
        // Statistik Dasar
        $totalSiswa = Student::count();
        $totalGuru  = User::where('role', 'guru')->count();
        $totalKelas = Classes::count();

        $today = Carbon::today();

        // Mengambil data kehadiran real-time hari ini
        $hadir = Attendance::whereDate('date', $today)
            ->where('status', 'hadir')
            ->count();

        $izin = Attendance::whereDate('date', $today)
            ->where('status', 'izin')
            ->count();

        $sakit = Attendance::whereDate('date', $today)
            ->where('status', 'sakit')
            ->count();

        // Opsi: Jika Anda ingin mendeteksi status 'telat' juga
        $telat = Attendance::whereDate('date', $today)
            ->where('status', 'telat')
            ->count();

        // Logika Alpha: 
        // Mengambil data yang statusnya 'alfa' di DB
        $alpha = Attendance::whereDate('date', $today)
            ->where('status', 'alfa')
            ->count();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalGuru',
            'totalKelas',
            'hadir',
            'izin',
            'sakit',
            'alpha',
            'telat'
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
        // Menggunakan eager loading 'student.class' dan 'guru' agar performa ringan
        $attendances = Attendance::with(['student.class', 'guru'])
            ->whereDate('date', $date)
            ->when($search, function ($query) use ($search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('admin.rekapabsensi', compact('attendances', 'date'));
    }

    public function totalPenilaian()
    {
        // 1. Ambil data kategori dan rata-rata skor global untuk chart
        $categoryData = DB::table('assessment_categories')
            ->leftJoin('assessment_details', 'assessment_categories.id', '=', 'assessment_details.category_id')
            ->select(
                'assessment_categories.name',
                DB::raw('COALESCE(AVG(assessment_details.score), 0) as average_score')
            )
            ->groupBy('assessment_categories.id', 'assessment_categories.name')
            ->get();

        $chartLabels = $categoryData->pluck('name')->toArray();
        $chartScores = $categoryData->pluck('average_score')->map(function ($score) {
            return round($score, 1);
        })->toArray();

        // 2. Data Siswa untuk tabel di bawah (sama seperti sebelumnya)
        $students = Student::has('assessments_received')
            ->with(['class', 'assessments_received.details'])
            ->get()
            ->map(function ($student) {
                $lastAssessment = $student->assessments_received->last();
                $student->avg_score = $lastAssessment ? $lastAssessment->details->avg('score') : 0;
                $student->last_period = $lastAssessment->period ?? '-';
                return $student;
            })->sortByDesc('avg_score');

        return view('admin.total_penilaian', compact('students', 'chartLabels', 'chartScores'));
    }
}

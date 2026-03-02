<?php

namespace App\Http\Controllers;

use App\Models\Attendance; // Pastikan Model Attendance di-import
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function rekapAbsensi(Request $request)
    {
        // 1. Ambil filter tanggal (default ke hari ini jika kosong)
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        
        // 2. Ambil input pencarian nama siswa
        $search = $request->input('search');

        // 3. Query data untuk Admin (melihat semua siswa)
        $attendances = Attendance::with(['student.class', 'guru'])
            ->whereDate('date', $date)
            ->when($search, function($query) use ($search) {
                $query->whereHas('student', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10); // Wajib paginate agar {{ $attendances->links() }} tidak error

        // 4. Kirim data ke View
        return view('admin.rekapabsensi', compact('attendances', 'date'));
    }
}
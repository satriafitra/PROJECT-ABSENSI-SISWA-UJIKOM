<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // Wajib di-import karena kita ada di sub-folder
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class ManualAttendanceController extends Controller
{
    /**
     * Menampilkan daftar absensi manual (Sakit & Izin) di Dashboard Admin
     */
    public function index()
    {
        // Mengambil data yang statusnya 'sakit' atau 'izin'
        // 'student' adalah nama relasi di model Attendance
        $manualAbsences = Attendance::with('student')
            ->whereIn('status', ['sakit', 'izin'])
            ->orderByDesc('date')
            ->get();

        return view('admin.absensi-manual.index', compact('manualAbsences'));
    }

    /**
     * API untuk menerima laporan manual dari Flutter
     */
    public function storeManual(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'status'     => 'required|in:sakit,izin',
            'keterangan' => 'required|string|max:255',
        ]);

        $today = Carbon::now()->toDateString();

        // 2. Cek duplikasi laporan hari ini
        $alreadyExists = Attendance::where('student_id', $request->student_id)
            ->where('date', $today)
            ->exists();

        if ($alreadyExists) {
            return response()->json([
                'status'  => false,
                'message' => 'Sudah ada catatan kehadiran untuk hari ini.',
            ], 409);
        }

        // 3. Simpan ke Database
        $attendance = Attendance::create([
            'student_id' => $request->student_id,
            'date'       => $today,
            'status'     => $request->status,
            'keterangan' => $request->keterangan,
            'guru_id'    => null, // Manual tidak lewat guru
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Laporan ' . ucfirst($request->status) . ' berhasil dikirim!',
            'data'    => $attendance
        ], 201);
    }
}
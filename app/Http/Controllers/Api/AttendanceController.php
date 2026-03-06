<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'guru_id'    => 'required|exists:guru,id',
        ]);

        $now   = Carbon::now();
        $today = $now->toDateString();
        $time  = $now->format('H:i:s');

        // Cek apakah siswa sudah absen hari ini
        $alreadyAbsent = Attendance::where('student_id', $request->student_id)
            ->where('date', $today)
            ->exists();

        if ($alreadyAbsent) {
            return response()->json([
                'status'  => false,
                'message' => 'Kamu sudah absen hari ini',
            ], 409);
        }

        Attendance::create([
            'student_id' => $request->student_id,
            'guru_id'    => $request->guru_id,
            'date'       => $today,
            'check_in'   => $time,
            'status'     => 'hadir',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Absensi berhasil dicatat',
            'time'    => $time,
        ], 201);
    }

    public function history($student_id)
    {
        $attendances = Attendance::with('guru')
            ->where('student_id', $student_id)
            ->orderByDesc('date')
            ->get();

        return response()->json([
            'status' => true,
            'data'   => $attendances,
        ]);
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'status'     => 'required|in:sakit,izin',
            'keterangan' => 'required|string|max:255',
        ]);

        $today = Carbon::now()->toDateString();

        // Cek apakah sudah ada catatan (Hadir/Sakit/Izin) hari ini
        $alreadyExists = Attendance::where('student_id', $request->student_id)
            ->where('date', $today)
            ->exists();

        if ($alreadyExists) {
            return response()->json([
                'status'  => false,
                'message' => 'Sudah ada catatan kehadiran untuk hari ini',
            ], 409);
        }

        Attendance::create([
            'student_id' => $request->student_id,
            'date'       => $today,
            'status'     => $request->status,
            'keterangan' => $request->keterangan, // Pastikan kolom ini ada di database kamu
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Laporan ' . $request->status . ' berhasil dikirim',
        ], 201);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance; // ⬅️ WAJIB
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        // validasi request dari Flutter
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'guru_id'    => 'required|exists:gurus,id',
        ]);

        $today = Carbon::today()->toDateString();

        // ❌ cegah absen dobel di hari yang sama
        $alreadyAbsent = Attendance::where('student_id', $request->student_id)
            ->where('date', $today)
            ->first();

        if ($alreadyAbsent) {
            return response()->json([
                'status'  => false,
                'message' => 'Kamu sudah absen hari ini'
            ], 409);
        }

        // ✅ simpan absensi
        Attendance::create([
            'student_id' => $request->student_id,
            'guru_id'    => $request->guru_id,
            'date'       => $today,
            'check_in'   => Carbon::now()->format('H:i:s'),
            'status'     => 'hadir',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Absensi berhasil dicatat',
            'time'    => Carbon::now()->format('H:i:s'),
        ], 201);
    }
}

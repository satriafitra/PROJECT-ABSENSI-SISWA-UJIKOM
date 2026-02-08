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

        $today = Carbon::today()->toDateString();

        $alreadyAbsent = Attendance::where('student_id', $request->student_id)
            ->where('date', $today)
            ->first();

        if ($alreadyAbsent) {
            return response()->json([
                'status'  => false,
                'message' => 'Kamu sudah absen hari ini'
            ], 409);
        }

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

    public function history($student_id)
    {
        $attendances = Attendance::with('guru')
            ->where('student_id', $student_id)
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $attendances
        ]);
    }
}

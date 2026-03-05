<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\Student;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'guru_id'    => 'required|exists:guru,id',
        ]);

        $student = Student::findOrFail($request->student_id);

        $now = Carbon::now();
        $today = $now->format('Y-m-d');
        $currentDay = $now->locale('id')->isoFormat('dddd');
        $currentTime = $now->format('H:i:s');

        // 🔎 Cari jadwal sesuai guru, kelas, hari & jam
        $schedule = Schedule::where('guru_id', $request->guru_id)
            ->where('class_id', $student->class_id)
            ->where('day', $currentDay)
            ->whereTime('time_start', '<=', $currentTime)
            ->whereTime('time_end', '>=', $currentTime)
            ->where('is_break', false)
            ->first();

        if (!$schedule) {
            return response()->json([
                'status' => false,
                'message' => 'Bukan jadwal mengajar guru ini sekarang'
            ], 403);
        }

        // Cek sudah absen atau belum (per guru & tanggal)
        $alreadyAbsent = Attendance::where('student_id', $student->id)
            ->where('guru_id', $request->guru_id)
            ->where('date', $today)
            ->first();

        if ($alreadyAbsent) {
            return response()->json([
                'status' => false,
                'message' => 'Kamu sudah absen di jam ini'
            ], 409);
        }

        $attendance = Attendance::create([
            'student_id' => $student->id,
            'guru_id'    => $request->guru_id,
            'date'       => $today,
            'check_in'   => $currentTime,
            'status'     => 'hadir',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Absensi berhasil',
            'data'    => [
                'subject' => $schedule->subject,
                'room'    => $schedule->room,
                'time'    => $currentTime,
            ]
        ]);
    }

    public function history($student_id)
    {
        $attendances = Attendance::with('guru')
            ->where('student_id', $student_id)
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data'   => $attendances
        ]);
    }
}
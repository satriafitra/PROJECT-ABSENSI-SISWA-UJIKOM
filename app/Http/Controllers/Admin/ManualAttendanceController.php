<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ManualAttendanceController extends Controller
{
    public function index()
    {
        $pendingAbsences = Attendance::with('student')
            ->whereIn('status', ['sakit', 'izin'])
            ->where('is_verified', 'pending')
            ->orderByDesc('date')
            ->get();

        $approvedAbsences = Attendance::with('student')
            ->whereIn('status', ['sakit', 'izin'])
            ->where('is_verified', 'approved')
            ->orderByDesc('date')
            ->get();

        return view('admin.absensi-manual.index', compact('pendingAbsences', 'approvedAbsences'));
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'status'     => 'required|in:sakit,izin',
            'keterangan' => 'required|string|max:255',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validasi foto
        ]);

        $today = Carbon::now()->toDateString();

        // Cek duplikasi
        $alreadyExists = Attendance::where('student_id', $request->student_id)
            ->where('date', $today)
            ->exists();

        if ($alreadyExists) {
            return response()->json(['status' => false, 'message' => 'Sudah ada catatan hari ini.'], 409);
        }

        // Upload Gambar
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('absensi_bukti', 'public');
        }

        $attendance = Attendance::create([
            'student_id'  => $request->student_id,
            'date'        => $today,
            'status'      => $request->status,
            'keterangan'  => $request->keterangan,
            'image'       => $imagePath,
            'is_verified' => 'pending', // Default nunggu admin
            'guru_id'     => null,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Laporan berhasil dikirim, menunggu verifikasi Admin.',
            'data'    => $attendance
        ], 201);
    }

    // Fungsi untuk verifikasi di Web Admin
    public function verify(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approved,rejected'
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update([
            'is_verified' => $request->action
        ]);

        return back()->with('success', 'Status absensi berhasil diperbarui!');
    }
}
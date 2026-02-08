<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\Attendance;
use Carbon\Carbon;

class ScanQrController extends Controller
{
    /**
     * Halaman QR Guru (WEB)
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'guru') {
            abort(403, 'Akses ditolak');
        }

        $guru = Guru::where('email', $user->email)->firstOrFail();

        // pastikan token ada
        if (!$guru->qr_token) {
            $guru->qr_token = bin2hex(random_bytes(16));
            $guru->save();
        }

        // QR DATA (AMAN)
        $qrData = json_encode([
            'type'     => 'attendance',
            'qr_token' => $guru->qr_token,
            'date'     => Carbon::today()->toDateString(),
        ]);

        return view('guru.scan-qr', compact('qrData', 'guru'));
    }

    /**
     * ABSENSI SISWA (API - Flutter)
     */
    public function absen(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'qr_token'   => 'required|exists:guru,qr_token', // 🔥 FIX
        ]);

        $guru = Guru::where('qr_token', $request->qr_token)->firstOrFail();

        $today = Carbon::now('Asia/Jakarta')->toDateString();

        // cegah absen dobel
        $already = Attendance::where('student_id', $request->student_id)
            ->where('guru_id', $guru->id) // ✅ tambahkan ini
            ->where('date', $today)
            ->exists();

        if ($already) {
            return response()->json([
                'status'  => false,
                'message' => 'Kamu sudah absen hari ini'
            ], 409);
        }

        Attendance::create([
            'student_id' => $request->student_id,
            'guru_id'    => $guru->id,
            'date'       => $today,
            'check_in' => Carbon::now('Asia/Jakarta')->format('H:i:s'),
            'status'     => 'hadir',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Absensi berhasil',
            'guru'    => $guru->nama,
            'tanggal' => $today,
            'waktu'   => Carbon::now('Asia/Jakarta')->format('H:i:s'),
        ], 201);
    }
}

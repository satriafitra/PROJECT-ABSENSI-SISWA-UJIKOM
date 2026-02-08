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
     * Halaman QR Guru (guru login)
     */
    public function index()
    {
        $user = Auth::user();

        // pastikan guru
        if ($user->role !== 'guru') {
            abort(403, 'Akses ditolak');
        }

        // ambil data guru
        $guru = Guru::where('email', $user->email)->first();
        if (!$guru) {
            abort(404, 'Data guru tidak ditemukan');
        }

        // QR guru (aman pakai token)
        $qrData = json_encode([
            'type'     => 'guru',
            'qr_token' => $guru->qr_token,
        ]);

        return view('guru.scan-qr', compact('qrData', 'guru'));
    }

    /**
     * ABSENSI SISWA (dipanggil dari Flutter)
     * siswa scan QR guru
     */
    public function absen(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'qr_token'   => 'required|exists:gurus,qr_token',
        ]);

        // cari guru dari qr_token
        $guru = Guru::where('qr_token', $request->qr_token)->first();
        if (!$guru) {
            return response()->json([
                'status' => false,
                'message' => 'QR guru tidak valid'
            ], 404);
        }

        $today = Carbon::today()->toDateString();

        // cegah absen dobel
        $exists = Attendance::where('student_id', $request->student_id)
            ->where('date', $today)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'Kamu sudah absen hari ini'
            ], 409);
        }

        // simpan absensi
        Attendance::create([
            'student_id' => $request->student_id,
            'guru_id'    => $guru->id,
            'date'       => $today,
            'check_in'   => now()->format('H:i:s'),
            'status'     => 'hadir',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Absensi berhasil',
            'guru'    => $guru->nama,
            'tanggal' => $today,
            'waktu'   => now()->format('H:i:s'),
        ], 201);
    }
}

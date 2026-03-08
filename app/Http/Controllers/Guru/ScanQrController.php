<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\Attendance;
use App\Models\JadwalGuru; // Pastikan model diimport
use Carbon\Carbon;

class ScanQrController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'guru') {
            abort(403);
        }

        $guru = Guru::where('email', $user->email)->firstOrFail();

        // Pastikan locale ke Indonesia agar hari sesuai dengan database (Senin, Selasa, dst)
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');

        $hariIni = Carbon::now('Asia/Jakarta')->translatedFormat('l');

        // Mengambil jadwal hari ini untuk ditampilkan di blade (opsional)
        $jadwalSekarang = JadwalGuru::where('guru_id', $guru->id)
            ->where('hari', $hariIni)
            ->get();

        $qrData = json_encode([
            'type'       => 'attendance',
            'qr_token'   => $guru->qr_token,
            'created_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
        ]);

        return view('guru.scan-qr', compact('qrData', 'guru', 'jadwalSekarang'));
    }

    public function absen(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'qr_token'   => 'required|exists:guru,qr_token',
            'created_at' => 'required'
        ]);

        $guru = Guru::where('qr_token', $request->qr_token)->firstOrFail();
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();
        $jamSekarang = $now->format('H:i:s');

        // Set Locale ke ID agar hari cocok dengan isi table 'jadwal_guru'
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        $dayName = $now->translatedFormat('l');

        // 2. Logika Validasi Jadwal (Pengganti 5 Menit)
        // Mencari jadwal guru yang sedang aktif saat ini
        $jadwal = JadwalGuru::where('guru_id', $guru->id)
            ->where('hari', $dayName)
            ->where('jam_mulai', '<=', $jamSekarang)
            ->where('jam_selesai', '>=', $jamSekarang)
            ->first();

        if (!$jadwal) {
            return response()->json([
                'status'  => false,
                'message' => "QR Tidak Aktif. Saat ini bukan jam mengajar atau jadwal tidak ditemukan untuk hari $dayName."
            ], 403);
        }

        // 3. Cek apakah siswa sudah absen di MAPEL dan RENTANG JAM yang sama hari ini
        $already = Attendance::where('student_id', $request->student_id)
            ->where('guru_id', $guru->id)
            ->where('date', $today)
            // Cek apakah ada absen masuk di antara jam mulai & selesai mapel tersebut
            ->whereBetween('check_in', [$jadwal->jam_mulai, $jadwal->jam_selesai])
            ->exists();

        if ($already) {
            return response()->json([
                'status'  => false,
                'message' => 'Anda sudah melakukan absensi di mata pelajaran ' . $jadwal->mata_pelajaran
            ], 409);
        }

        // 4. Simpan Absensi
        Attendance::create([
            'student_id' => $request->student_id,
            'guru_id'    => $guru->id,
            'date'       => $today,
            'check_in'   => $jamSekarang,
            'status'     => 'hadir',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Berhasil Absen: ' . $jadwal->mata_pelajaran,
            'guru'    => $guru->nama,
            'waktu'   => $jamSekarang,
        ], 201);
    }
}

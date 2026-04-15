<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Guru;
use App\Models\Attendance;
use App\Models\JadwalGuru;
use App\Models\PointLedger; // Import model Ledger Poin
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

        // Pastikan locale ke Indonesia
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');

        $hariIni = Carbon::now('Asia/Jakarta')->translatedFormat('l');

        // Mengambil jadwal hari ini
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

        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        $dayName = $now->translatedFormat('l');

        // 2. Logika Validasi Jadwal
        $jadwal = JadwalGuru::where('guru_id', $guru->id)
            ->where('hari', $dayName)
            ->where('jam_mulai', '<=', $jamSekarang)
            ->where('jam_selesai', '>=', $jamSekarang)
            ->first();

        if (!$jadwal) {
            return response()->json([
                'status'  => false,
                'message' => "QR Tidak Aktif. Saat ini bukan jam mengajar atau jadwal tidak ditemukan."
            ], 403);
        }

        // 3. Cek apakah siswa sudah absen di MAPEL dan RENTANG JAM yang sama
        $already = Attendance::where('student_id', $request->student_id)
            ->where('guru_id', $guru->id)
            ->where('date', $today)
            ->whereBetween('check_in', [$jadwal->jam_mulai, $jadwal->jam_selesai])
            ->exists();

        if ($already) {
            return response()->json([
                'status'  => false,
                'message' => 'Anda sudah melakukan absensi di mata pelajaran ' . $jadwal->mata_pelajaran
            ], 409);
        }

        // --- 4. PROSES SIMPAN ABSENSI & REWARD POIN (DATABASE TRANSACTION) ---
        return DB::transaction(function () use ($request, $guru, $today, $jamSekarang, $jadwal) {

            // 1. Simpan Absensi
            Attendance::create([
                'student_id' => $request->student_id,
                'guru_id'    => $guru->id,
                'date'       => $today,
                'check_in'   => $jamSekarang,
                'status'     => 'hadir',
            ]);

            // 2. Update Saldo Poin di Tabel Students
            $rewardAmount = 5;
            $student = \App\Models\Student::find($request->student_id);
            $student->increment('points', $rewardAmount); // Ini yang bikin nambah di database!

            // 3. Simpan Riwayat di PointLedger (Untuk Log)
            PointLedger::create([
                'student_id'       => $request->student_id,
                'transaction_type' => 'EARN',
                'amount'           => $rewardAmount,
                'current_balance'  => $student->points, // Ambil saldo terbaru setelah increment
                'description'      => "Reward Absensi: " . $jadwal->mata_pelajaran
            ]);

            // 4. Response (Kirim total_poin_skrg ke Flutter)
            return response()->json([
                'status'  => true,
                'message' => 'Berhasil Absen! Poin bertambah +5',
                'detail'  => [
                    'total_poin_skrg' => $student->points // Ini dikirim ke Flutter
                ]
            ], 201);
        });
    }
}

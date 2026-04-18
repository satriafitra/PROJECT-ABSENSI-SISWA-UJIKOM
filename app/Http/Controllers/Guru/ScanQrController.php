<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Guru;
use App\Models\Attendance;
use App\Models\JadwalGuru;
use App\Models\PointLedger;
use App\Models\UserToken;
use App\Models\Student;
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

        config(['app.locale' => 'id']);
        Carbon::setLocale('id');

        $hariIni = Carbon::now('Asia/Jakarta')->translatedFormat('l');

        $jadwalSekarang = JadwalGuru::where('guru_id', $guru->id)
            ->where('hari', $hariIni)
            ->get();

        $qrData = json_encode([
            'type' => 'attendance',
            'qr_token' => $guru->qr_token,
            'created_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
        ]);

        return view('guru.scan-qr', compact('qrData', 'guru', 'jadwalSekarang'));
    }

    public function absen(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'qr_token' => 'required|exists:guru,qr_token',
            'created_at' => 'required'
        ]);

        $guru = Guru::where('qr_token', $request->qr_token)->firstOrFail();

        $now = Carbon::now('Asia/Jakarta');
        if ($now->isWeekend()) {
            return response()->json([
                'status' => false,
                'message' => 'Hari libur, tidak bisa absen hari ini.'
            ], 403);
        }

        $today = $now->toDateString();
        $jamSekarang = $now->format('H:i:s');

        config(['app.locale' => 'id']);
        Carbon::setLocale('id');

        $dayName = $now->translatedFormat('l');

        // ================= JADWAL =================
        $menitToleransi = 30;
        $jamBatasToleransi = $now->copy()->subMinutes($menitToleransi)->format('H:i:s');

        $jadwal = JadwalGuru::where('guru_id', $guru->id)
            ->where('hari', $dayName)
            ->where('jam_mulai', '<=', $jamSekarang)
            ->whereTime('jam_selesai', '>=', $jamBatasToleransi)
            ->first();

        if (!$jadwal) {
            return response()->json([
                'status' => false,
                'message' => 'QR Tidak Aktif. Batas toleransi telah berakhir.'
            ], 403);
        }

        // ================= TRANSAKSI =================
        return DB::transaction(function () use (
            $request,
            $guru,
            $now,
            $today,
            $jamSekarang,
            $jadwal
        ) {

            // 🔒 AMBIL VOUCHER AKTIF
            $activeVoucher = UserToken::where('student_id', $request->student_id)
                ->where('status', 'ACTIVE')
                ->whereNull('used_at_attendance_id')
                ->lockForUpdate()
                ->first();

            // Menentukan Batas Waktu Telat (sesuai setting jadwal)
            $menitTelat = $jadwal->batas_telat ?? 5;
            $jamMulai = Carbon::createFromFormat('H:i:s', $jadwal->jam_mulai, 'Asia/Jakarta');
            $batasTelat = $jamMulai->copy()->addMinutes($menitTelat)->format('H:i:s');

            if ($jamSekarang > $jadwal->jam_selesai) {
                Attendance::create([
                    'student_id' => $request->student_id,
                    'guru_id' => $guru->id,
                    'date' => $today,
                    'check_in' => $jamSekarang,
                    'status' => 'alfa',
                ]);
                
                return response()->json([
                    'status' => false,
                    'message' => 'Anda sudah melewati jam pelajaran',
                    'detail' => [
                        'status' => 'alfa',
                        'total_poin_skrg' => Student::findOrFail($request->student_id)->points,
                        'voucher_used' => false
                    ]
                ], 403);
            } elseif ($jamSekarang > $batasTelat) {
                $statusAbsen = 'telat';
            } else {
                $statusAbsen = 'hadir';
            }

            // ================= STATUS =================
            // 🔥 voucher = ALWAYS HADIR
            if ($activeVoucher) {
                $statusAbsen = 'hadir';
            }

            // ================= POINT FIX =================
            $student = Student::findOrFail($request->student_id);

            if ($statusAbsen == 'hadir') {
                $reward = 50;
                $type = 'EARN';
                $desc = "Reward Absensi: " . $jadwal->mata_pelajaran;

                $student->increment('points', $reward);
            } else {
                $reward = 5;
                $type = 'PENALTY';
                $desc = "Potongan Telat: " . $jadwal->mata_pelajaran;

                $student->decrement('points', $reward);
            }

            // ================= ATTENDANCE =================
            $attendance = Attendance::create([
                'student_id' => $request->student_id,
                'guru_id' => $guru->id,
                'date' => $today,
                'check_in' => $jamSekarang,
                'status' => $statusAbsen,
            ]);

            // ================= LEDGER =================
            PointLedger::create([
                'student_id' => $request->student_id,
                'transaction_type' => $type,
                'amount' => $reward,
                'current_balance' => $student->points,
                'description' => $desc
            ]);

            // ================= VOUCHER USED =================
            if ($activeVoucher) {
                $activeVoucher->update([
                    'status' => 'USED',
                    'used_at_attendance_id' => $attendance->id
                ]);
            }

            // ================= MESSAGE =================
            if ($statusAbsen == 'hadir') {
                $message = "Berhasil Absen! Poin +$reward";
            } else {
                $message = "Absen Telat! Poin -$reward";
            }

            if ($activeVoucher) {
                $message .= " (1 voucher telah digunakan)";
            }

            return response()->json([
                'status' => true,
                'message' => $message,
                'detail' => [
                    'status' => $statusAbsen,
                    'total_poin_skrg' => $student->points,
                    'voucher_used' => $activeVoucher ? true : false
                ]
            ], 201);
        });
    }
}
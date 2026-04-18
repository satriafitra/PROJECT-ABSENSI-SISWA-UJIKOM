<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Guru;
use App\Models\Lokasi;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input (Tambahkan lat/long & qr_token)
        $request->validate([
            'student_id'      => 'required|exists:students,id',
            'qr_token'        => 'required', // Kita pakai token untuk cari Guru
            'latitude_siswa'  => 'required',
            'longitude_siswa' => 'required',
        ]);

        // 2. Cari Guru berdasarkan QR Token
        $guru = Guru::where('qr_token', $request->qr_token)->first();
        if (!$guru) {
            return response()->json([
                'status'  => false,
                'message' => 'QR Code tidak valid atau sudah kedaluwarsa',
            ], 404);
        }

        // 3. LOGIC GPS: Cek Radius
        $lokasiPusat = Lokasi::first(); // Ambil koordinat sekolah dari tabel lokasis
        if ($lokasiPusat) {
            $jarak = $this->calculateDistance(
                $request->latitude_siswa,
                $request->longitude_siswa,
                $lokasiPusat->latitude,
                $lokasiPusat->longitude
            );

            if ($jarak > $lokasiPusat->radius) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Anda berada di luar zona absensi! Jarak: ' . round($jarak) . 'm',
                ], 403);
            }
        }

        $now   = Carbon::now();
        $today = $now->toDateString();
        $time  = $now->format('H:i:s');

        // 4. Cek apakah siswa sudah absen hari ini
        $alreadyAbsent = Attendance::where('student_id', $request->student_id)
            ->where('date', $today)
            ->exists();

        if ($alreadyAbsent) {
            return response()->json([
                'status'  => false,
                'message' => 'Kamu sudah absen hari ini',
            ], 409);
        }

        // 5. Simpan Absensi
        Attendance::create([
            'student_id' => $request->student_id,
            'guru_id'    => $guru->id, // Pakai ID guru yang ditemukan dari token
            'date'       => $today,
            'check_in'   => $time,
            'status'     => 'hadir',
            'latitude'   => $request->latitude_siswa, // Opsional: simpan posisi siswa di tabel attendance
            'longitude'  => $request->longitude_siswa,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Absensi berhasil dicatat di lokasi',
            'time'    => $time,
            'jarak'   => round($jarak ?? 0) . ' meter'
        ], 201);
    }

    /**
     * Fungsi Helper Haversine untuk hitung jarak (Meter)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
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

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('absensi_bukti', 'public');
        }

        Attendance::create([
            'student_id' => $request->student_id,
            'date'       => $today,
            'status'     => $request->status,
            'keterangan' => $request->keterangan, // Pastikan kolom ini ada di database kamu
            'image'      => $imagePath,
            'is_verified'=> 'pending',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Laporan ' . $request->status . ' berhasil dikirim',
        ], 201);
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FetchSiswa extends Command
{
    protected $signature = 'siswa:fetch';
    protected $description = 'Ambil data siswa dari API Zieapi dan simpan ke table students';

    public function handle()
    {
        $this->info("Mengambil data siswa dari API Zieapi...");

        $response = Http::timeout(60)->get(
            'https://zieapi.zielabs.id/api/getsiswa',
            ['tahun' => 2025]
        );

        if (!$response->successful()) {
            $this->error("Gagal mengambil data siswa");
            return 1;
        }

        $siswaArray = $response->json('data') ?? [];
        if (empty($siswaArray)) {
            $this->warn("Data kosong");
            return 0;
        }

        $processedNisn = [];
        $count = 0;

        foreach ($siswaArray as $item) {

            $nisn = $item['nisn'] ?? null;
            if (!$nisn) continue;

            // 🔥 SKIP DUPLIKAT API
            if (in_array($nisn, $processedNisn)) continue;
            $processedNisn[] = $nisn;

            // Cari class_id
            $classId = null;
            if (!empty($item['kelas'])) {
                $class = DB::table('classes')->where('name', $item['kelas'])->first();
                $classId = $class?->id;
            }

            // 🔍 cek apakah siswa sudah ada
            $exists = DB::table('students')->where('nis', $nisn)->exists();

            if ($exists) {
                // 🔁 UPDATE SAJA (qr_token TIDAK DIUBAH)
                DB::table('students')
                    ->where('nis', $nisn)
                    ->update([
                        'name' => $item['nama'] ?? '',
                        'class_id' => $classId,
                        'updated_at' => now(),
                    ]);
            } else {
                // ➕ INSERT BARU (qr_token BARU)
                DB::table('students')->insert([
                    'nis' => $nisn,
                    'name' => $item['nama'] ?? '',
                    'class_id' => $classId,
                    'qr_token' => Str::uuid(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $count++;
        }

        $this->info("Berhasil sinkron {$count} siswa");
        return 0;
    }
}

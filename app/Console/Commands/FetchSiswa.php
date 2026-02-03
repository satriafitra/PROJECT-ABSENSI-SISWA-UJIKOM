<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FetchSiswa extends Command
{
    protected $signature = 'siswa:fetch';
    protected $description = 'Sinkron data siswa dari API Zieapi';

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
            $this->warn("Data siswa kosong");
            return 0;
        }

        $processedNisn = [];
        $count = 0;

        foreach ($siswaArray as $item) {

            $nisn = $item['nisn'] ?? null;
            if (!$nisn) continue;

            // skip duplikat dari API
            if (in_array($nisn, $processedNisn)) continue;
            $processedNisn[] = $nisn;

            /* ======================
               KELAS (nama_rombel)
            ====================== */
            $classId = null;
            $kelasName = $item['nama_rombel'] ?? null;

            if ($kelasName) {
                $kelasName = trim($kelasName);

                $class = DB::table('classes')->where('name', $kelasName)->first();

                if (!$class) {
                    $classId = DB::table('classes')->insertGetId([
                        'name' => $kelasName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $classId = $class->id;
                }
            }

            /* ======================
               STUDENT
            ====================== */
            $exists = DB::table('students')->where('nis', $nisn)->exists();

            if ($exists) {
                DB::table('students')
                    ->where('nis', $nisn)
                    ->update([
                        'name' => $item['nama'] ?? '',
                        'class_id' => $classId,
                        'updated_at' => now(),
                    ]);
            } else {
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

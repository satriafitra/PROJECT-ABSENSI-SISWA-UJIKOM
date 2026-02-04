<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FetchGuru extends Command
{
    protected $signature = 'guru:fetch';
    protected $description = 'Sinkron data guru dari API Zieapi';

    public function handle()
    {
        $this->info("Mengambil data guru dari API Zieapi...");

        $response = Http::timeout(60)->get(
            'https://zieapi.zielabs.id/api/getguru'
        );

        if (!$response->successful()) {
            $this->error("Gagal mengambil data guru");
            return 1;
        }

        $guruArray = $response->json() ?? [];

        if (empty($guruArray)) {
            $this->warn("Data guru kosong");
            return 0;
        }

        $count = 0;

        foreach ($guruArray as $item) {

            $nip   = $item['nip'] ?? null;
            $email = $item['email'] ?? null;

            if (!$nip && !$email) continue;

            // cari guru existing (prioritas nip)
            $guru = DB::table('guru')
                ->when($nip, fn ($q) => $q->where('nip', $nip))
                ->when(!$nip && $email, fn ($q) => $q->where('email', $email))
                ->first();

            $data = [
                'nama'           => $item['nama'] ?? '',
                'nip'            => $nip,
                'email'          => $email,
                'jenis_kelamin'  => $item['jenis_kelamin'] ?? null,
                'status'         => 'aktif',
                'updated_at'     => now(),
            ];

            if ($guru) {
                // UPDATE
                DB::table('guru')->where('id', $guru->id)->update($data);
            } else {
                // INSERT
                DB::table('guru')->insert(array_merge($data, [
                    'password'   => Hash::make(Str::random(8)),
                    'created_at' => now(),
                ]));
            }

            $count++;
        }

        $this->info("Berhasil sinkron {$count} guru");
        return 0;
    }
}

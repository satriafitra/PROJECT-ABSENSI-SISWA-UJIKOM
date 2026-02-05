<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class FetchGuru extends Command
{
    protected $signature = 'guru:fetch';
    protected $description = 'Sinkron data guru dari API Zieapi';

    public function handle()
    {
        $this->info("Mengambil data guru dari API Zieapi...");

        $response = Http::timeout(120)->get(
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

            $email = trim($item['email'] ?? '');

            if (!$email) continue;

            $nip = trim($item['nip'] ?? '');
            $nip = $nip === '' ? null : $nip;

            // =========================
            // GURU
            // =========================
            DB::table('guru')->updateOrInsert(
                ['email' => $email],
                [
                    'nama'          => $item['nama'] ?? '',
                    'nip'           => $nip,
                    'jenis_kelamin' => $item['jenis_kelamin'] ?? null,
                    'status'        => 'aktif',
                    'password'      => Hash::make('12345'),
                    'qr_token'      => DB::raw("COALESCE(qr_token, '" . Str::uuid() . "')"),
                    'updated_at'    => now(),
                    'created_at'    => now(),
                ]
            );

            // =========================
            // USER (LOGIN)
            // =========================
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name'     => $item['nama'],
                    'password' => Hash::make('12345'),
                    'role'     => 'guru',
                ]
            );

            $count++;
        }

        $this->info("Berhasil sinkron {$count} guru");
        return 0;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;


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

            if (!$email) continue; // LOGIN BUTUH EMAIL

            // =========================
            // GURU
            // =========================
            $guru = DB::table('guru')->where('email', $email)->first();

            $passwordPlain = $nip ? substr($nip, -6) : Str::random(8);

            $guruData = [
                'nama'          => $item['nama'] ?? '',
                'nip'           => $nip,
                'email'         => $email,
                'jenis_kelamin' => $item['jenis_kelamin'] ?? null,
                'status'        => 'aktif',
                'updated_at'    => now(),
            ];

            if ($guru) {
                DB::table('guru')->where('id', $guru->id)->update($guruData);
            } else {
                DB::table('guru')->insert(array_merge($guruData, [
                    'password'   => Hash::make($passwordPlain),
                    'created_at' => now(),
                ]));
            }

            // =========================
            // USER (LOGIN)
            // =========================
            $user = User::where('email', $email)->first();

            if (!$user) {
                User::create([
                    'name'     => $item['nama'],
                    'email'    => $email,
                    'password' => Hash::make($passwordPlain),
                    'role'     => 'guru',
                ]);
            }

            $count++;
        }


        $this->info("Berhasil sinkron {$count} guru");
        return 0;
    }
}

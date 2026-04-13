<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JadwalGuru extends Model
{
    use HasFactory;

    protected $table = 'jadwal_guru';

    protected $fillable = [
        'guru_id',
        'hari',
        'mata_pelajaran',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    /**
     * Mengecek apakah jadwal sedang berlangsung saat ini
     */
    public function isNow()
    {
        // Set locale ke Indonesia agar format 'l' menghasilkan Senin, Selasa, dst.
        Carbon::setLocale('id');
        $hariIni = Carbon::now()->translatedFormat('l');
        $jamSekarang = Carbon::now()->format('H:i:s');

        return $this->hari === $hariIni && 
               $jamSekarang >= $this->jam_mulai && 
               $jamSekarang <= $this->jam_selesai;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
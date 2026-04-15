<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'student_id',
        'guru_id',     // ⬅️ WAJIB
        'date',
        'status',
        'keterangan', // Tambahkan ini jika belum ada!
        'check_in',
        'check_out',
        'status',
        'latitude',  // ⬅️ Tambahkan ini untuk GPS
        'longitude', // ⬅️ Tambahkan ini untuk GPS
    ];

    // relasi ke siswa
    public function student()
    {

        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    // relasi ke guru
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}

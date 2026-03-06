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
    ];

    // relasi ke siswa
    public function student()
    {
        // Sesuaikan 'student_id' dengan nama kolom di tabel attendances
        // dan 'id' dengan primary key di tabel students
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    // relasi ke guru
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}

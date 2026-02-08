<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'student_id',
        'guru_id',     // ⬅️ WAJIB
        'date',
        'check_in',
        'check_out',
        'status',
    ];

    // relasi ke siswa
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // relasi ke guru
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}

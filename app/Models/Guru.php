<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
        'nama',
        'nip',
        'email',
        'password',       // ⬅️ WAJIB
        'status',
        'jenis_kelamin',
    ];

    // otomatis hash password kalau di-set
    protected $hidden = [
        'password',
    ];
}

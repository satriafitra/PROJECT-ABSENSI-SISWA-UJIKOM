<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';

    protected $fillable = [
        'nis',
        'name',
        'class_id',
        'password',
        'qr_token',
    ];

    // 🔒 supaya password tidak ikut ke response API
    protected $hidden = [
        'password',
    ];

    // Relasi ke tabel classes
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
}

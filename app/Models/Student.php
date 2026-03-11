<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

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

    /**
     * Relasi ke tabel classes
     */
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    /**
     * Relasi ke tabel assessments (Penilaian yang DITERIMA siswa)
     * Tambahkan fungsi ini untuk memperbaiki error BadMethodCallException
     */
    public function assessments_received()
    {
        // Pastikan foreign key di tabel assessments adalah 'evaluatee_id'
        return $this->hasMany(Assessment::class, 'evaluatee_id');
    }
}
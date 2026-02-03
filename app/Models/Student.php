<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['nis', 'name', 'class_id', 'qr_token'];

    // Relasi ke kelas
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
}

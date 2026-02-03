<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes'; // nama table
    protected $fillable = ['name']; // kolom yang bisa diisi mass-assignment

    // Relasi: 1 kelas bisa punya banyak siswa
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }
}

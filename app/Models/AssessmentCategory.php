<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentCategory extends Model
{
    protected $table = 'assessment_categories';
    protected $fillable = ['name', 'description', 'type', 'is_active']; 

    // Relasi ke Pertanyaan (PENTING: Ini yang bikin error tadi)
    public function questions()
    {
        return $this->hasMany(AssessmentQuestion::class, 'category_id');
    }

    // Relasi ke detail penilaian (skor)
    public function details()
    {
        return $this->hasMany(AssessmentDetail::class, 'category_id');
    }
}
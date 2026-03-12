<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentQuestion extends Model
{
    protected $table = 'assessment_questions';
    
    // category_id harus masuk fillable supaya bisa simpan manual dari Admin
    protected $fillable = ['category_id', 'question_text']; 

    // Kebalikan relasi: satu pertanyaan milik satu kategori
    public function category()
    {
        return $this->belongsTo(AssessmentCategory::class, 'category_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = [
        'evaluator_id', 
        'evaluatee_id', 
        'assessment_date', 
        'period', 
        'general_notes'
    ]; 

    // Relasi ke User (Guru/Penilai)
    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    // Relasi ke User (Siswa/Yang dinilai)
    public function evaluatee()
    {
        return $this->belongsTo(User::class, 'evaluatee_id');
    }

    // Relasi ke rincian nilai per kategori
    public function details()
    {
        return $this->hasMany(AssessmentDetail::class, 'assessment_id');
    }
}
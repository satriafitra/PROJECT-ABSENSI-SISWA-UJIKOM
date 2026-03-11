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

    // Relasi ke User (Guru/Penilai tetap menggunakan User karena Guru login sebagai User)
    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    /**
     * PERBAIKAN: Relasi ke Student (Yang dinilai)
     * Diarahkan ke model Student, bukan User
     */
    public function evaluatee()
    {
        return $this->belongsTo(Student::class, 'evaluatee_id');
    }

    // Relasi ke rincian nilai per kategori
    public function details()
    {
        return $this->hasMany(AssessmentDetail::class, 'assessment_id');
    }
}
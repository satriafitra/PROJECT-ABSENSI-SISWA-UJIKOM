<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentDetail extends Model
{
    protected $fillable = ['assessment_id', 'category_id', 'score'];

    // Menghubungkan kembali ke header
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    // Mengambil nama kategori (Misal untuk label di grafik)
    public function category()
    {
        return $this->belongsTo(AssessmentCategory::class, 'category_id');
    }
}
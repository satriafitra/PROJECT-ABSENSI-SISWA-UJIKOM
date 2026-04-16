<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    protected $fillable = [
        'student_id',
        'item_id',
        'status',
        'used_at_attendance_id',
        'points_spent'
    ];

    // ================= RELATION =================
    public function item()
    {
        return $this->belongsTo(FlexibilityItem::class, 'item_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class, 'used_at_attendance_id');
    }

    // ================= HELPER =================

    public function isAvailable()
    {
        return $this->status === 'AVAILABLE' && is_null($this->used_at_attendance_id);
    }

    public function isUsed()
    {
        return $this->status === 'USED';
    }
}
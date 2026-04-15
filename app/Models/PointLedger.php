<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointLedger extends Model
{
    protected $fillable = ['student_id', 'transaction_type', 'amount', 'current_balance', 'description'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'guru_id',
        'day',
        'subject',
        'time_start',
        'time_end',
        'room',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}

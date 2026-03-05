<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'guru_id',
        'class_id',
        'day',
        'subject',
        'time_start',
        'time_end',
        'room',
        'is_break'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
}
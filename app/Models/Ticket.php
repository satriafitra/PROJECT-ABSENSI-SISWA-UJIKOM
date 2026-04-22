<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'subject',
        'description',
        'priority',
        'status',
    ];

    public function reporter()
    {
        return $this->belongsTo(Student::class, 'reporter_id');
    }

    public function responses()
    {
        return $this->hasMany(TicketResponse::class, 'ticket_id');
    }

    public function rating()
    {
        return $this->hasOne(SatisfactionRating::class, 'ticket_id');
    }
}

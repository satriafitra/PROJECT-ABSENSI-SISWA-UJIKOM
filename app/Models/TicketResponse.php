<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'sender_id',
        'sender_type',
        'message',
        'is_auto_reply',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    // Custom accessor for sender name if needed
}

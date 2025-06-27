<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'ticket_id',
        'order_id',
        'quantity',
        'total_amount',
        'status',
        'payment_method',
        'transaction_id',
        'snap_token',
        'payment_info',
    ];

    protected $casts = [
        'payment_info' => 'array',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}

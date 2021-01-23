<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deal extends UuidModel
{
    use HasFactory;

    protected $fillable = ['amount_id', 'event_id', 'payer', 'partner', 'amount'];
    protected $with = ['line_friend'];

    public function amount()
    {
        return $this->belongsTo(
            Amount::class
        );
    }

    public function line_friend()
    {
        return $this->belongsTo(
            LineFriend::class, 
            'partner',
            'line_id'
        );
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amount extends UuidModel
{
    use HasFactory;

    protected $fillable = ['friend_id', 'event_id', 'amount', 'note'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function line_friend()
    {
        return $this->belongsTo(LineFriend::class);
    }
}

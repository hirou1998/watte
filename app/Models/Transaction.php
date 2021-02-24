<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends UuidModel
{
    use HasFactory;
    protected $fillable = ['from_user', 'to_user', 'amount'];

    public function event()
    {
        return $this->belongsTo(
            Event::class
        );
    }
}

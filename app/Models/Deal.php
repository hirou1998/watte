<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deal extends UuidModel
{
    use HasFactory;

    protected $fillable = ['amount_id', 'payer', 'partner'];

    public function amount()
    {
        return $this->belongsTo(
            Amount::class
        );
    }
}

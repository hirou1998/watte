<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends UuidModel
{
    use HasFactory;

    protected $fillable = ['event_name', 'creator_id', 'group_id'];

    public function line_friends()
    {
        return $this->belongsToMany(LineFriend::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'group_id');
    }
}

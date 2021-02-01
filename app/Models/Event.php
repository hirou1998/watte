<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends UuidModel
{
    use HasFactory;

    protected $fillable = ['event_name', 'creator_id', 'group_id', 'notification', 'file_name', 'file_path'];

    public function line_friends()
    {
        return $this->belongsToMany(
            LineFriend::class,
            'event_line_friend',
            'event_id',
            'line_friend_id'
        )->withPivot('ratio');
    }

    public function group()
    {
        return $this->belongsTo(
            Group::class,
        );
    }

    public function amounts()
    {
        return $this->hasMany(
            Amount::class,
        );
    }
}
